<?php 

namespace Grizzlyapps\Shopify;

use Crypt;
use Carbon\Carbon;
use Grizzlyapps\Shopify\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Illuminate\Database\Eloquent\Model;
use ThemeHelper;

class ShopifyAuth extends Model
{
    /**
     * The currently authenticated user.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    
    protected $user;
     /**
     * The session used by ShopifyAuth.
     *
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Return the currently cached user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the current user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user($_id = null)
    {
        // If we have already retrieved the user for the current request we can just
        // return it back immediately. We do not want to pull the user data every
        // request into the method because that would tremendously slow an app.
        if (!is_null($this->user)) {
            return $this->user;
        }

        $shop = $this->getShop();
        $token = $this->getAccessToken();

        $user = null;

        //load user from session info
        if (!is_null($shop) && !is_null($token)) {
            $user = User::where('shop', $shop)->first();
        }

        //load user by id or shop if provided
        if (!is_null($_id)) {
            if (is_numeric($_id)) {
                $user = User::find($_id);    
            } else {
                $user = User::where('shop', $_id)->first();
            }            
        }

        return $this->user = $user;
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function guest()
    {
        if (is_null($this->user()) || (!$this->hasCharge())) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function hasCharge()
    {
        return ($this->user->getChargeId()!=0) ? true : false;
    }

    public function attempt($sh, $data)
    {
    	try {
            $user = User::where('shop', $data['shop'])->firstOrFail();
            $now = Carbon::now('Europe/Bucharest')->format('Y-m-d H:i:s');
            $data['last_login'] = $now;
            // $data['token'] = $data['token'];
            $call = $sh->call(['URL' => 'shop.json', 'DATA' => ['fields'=>'shop_owner,email,name,domain,plan_name']]);
            $data['plan_name'] = $call->shop->plan_name;
            $data['charge_id'] = $user->getChargeId();
            
            if ($data['charge_id']==0) {
                $data['email'] = $call->shop->email;
                $data['name'] = $call->shop->shop_owner;
                $data['shop_name'] = $call->shop->name;
                if ($user->getDomain()!='') {
                    $domains = explode(',',$user->getDomain());
                } else {
                    $domains = array();
                }
                $newDomain = str_replace('www.','',$call->shop->domain);
                if (!in_array($newDomain, $domains)) {
                    $domains[] = $newDomain;
                }
                $data['domain'] = implode(',', $domains);

                $theme = $sh->call(['URL' => 'themes.json', 'RETURNARRAY' => true, 'DATA' => ['role'=>'main','fields'=>'theme_store_id,name']]);
                $id = $theme['themes'][0]['theme_store_id'];

                $data['theme_store_id'] = $id;
                $data['theme_name'] = $theme['themes'][0]['name'];
                $data['total_hits'] = 0;
                $data['daily_hits'] = 0;

                // $sh->removeAsset('assets/doubly.js');
            }
            $user->update($data);
        } catch (\Exception $e) {
            //if user doesn't exist, create him
            if ($e->getMessage()=='No query results for model [Grizzlyapps\Shopify\User].') {
                $call = $sh->call(['URL' => 'shop.json', 'DATA' => ['fields'=>'shop_owner,email,name,domain,plan_name']]);
                $data['email'] = $call->shop->email;
                $data['name'] = $call->shop->shop_owner;
                $data['shop_name'] = $call->shop->name;
                $data['plan_name'] = $call->shop->plan_name;
                $data['domain'] = str_replace('www.','',$call->shop->domain);
                $now = Carbon::now('Europe/Bucharest')->format('Y-m-d H:i:s');
                $data['last_login'] = $now;

                $theme = $sh->call(['URL' => 'themes.json', 'RETURNARRAY' => true, 'DATA' => ['role'=>'main','fields'=>'theme_store_id,name']]);
                $id = $theme['themes'][0]['theme_store_id'];

                $data['theme_store_id'] = $id;
                $data['theme_name'] = $theme['themes'][0]['name'];                

                $user = User::create($data);
            } else {
                return false;
            }
        }

        $this->login($user);

        return true;
    }

    /**
     * Log a user into the application.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  bool  $remember
     * @return void
     */
    public function login(User $user)
    {
        $this->updateSession(['shop' => $user['shop'], 'token' => $user['token']]);
        $this->setUser($user);
    }

    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout()
    {
        // If we have an event dispatcher instance, we can fire off the logout event
        // so any further processing can be done. This allows the developer to be
        // listening for anytime a user signs out of this application manually.
        $this->clearUserDataFromStorage();

        $this->user = null;
    }

    //session functions
    public function getAccessToken()
    {
        if (!is_null($this->session->get($this->getSessionName('token')))) {
            return Crypt::decrypt($this->session->get($this->getSessionName('token')));
        } else {
            return null;
        }
    }

    public function getShop()
    {
        return $this->session->get($this->getSessionName('shop'));
    }

    public function getSessionName($name)
    {
        return $name.'_'.base64_encode('app_name');
    }

    protected function updateSession($data)
    {
        $this->session->set($this->getSessionName('shop'), $data['shop']);
        $this->session->migrate(true);

        $this->session->set($this->getSessionName('token'), Crypt::encrypt($data['token']));
        $this->session->migrate(true);
    }

    protected function clearUserDataFromStorage()
    {
        $this->session->remove($this->getSessionName('shop'));
        $this->session->remove($this->getSessionName('token'));
    }

}
