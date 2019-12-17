<?php

namespace Grizzlyapps\Shopify\Http\Controllers;

use App;
use CurrencyHelper;
use Input;
use Carbon\Carbon;
use Grizzlyapps\Shopify\Shopify;
use Grizzlyapps\Shopify\ShopifyAuth;
use App\Http\Controllers\Controller;
use Grizzlyapps\Shopify\Http\Controllers\Auth\LoginShopifyUser;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use ShopifyLog;

class ShopifyController extends Controller
{

    protected $redirectTo = '/';
    protected $_loginErrorMsg = 'An error occurred please re-login. If you are experiencing any issues feel free to contact us at ';

    use LoginShopifyUser;

    protected $auth;

    public function __construct(ShopifyAuth $auth, Shopify $shopify, SessionInterface $session)
    {
        $this->auth = $auth;
        $this->shopify = $shopify;
        $this->session = $session;
    }

    //authorize and verify charge
    public function authorize() 
    {
        if (!Input::get('shop')) {
            \Log::error('No shop provided on authorization.');
            return redirect('login');
        } else {
            $shop = Input::get('shop');
        }

        $shopify = App::make('ShopifyAPI');
        $shopify->setup(['SHOP_DOMAIN' => $shop, 'ACCESS_TOKEN' => '']);

        try
        {
            $verify = $shopify->verifyRequest(Input::all());
            if ($verify)
            {
                $code = Input::get('code');
                $shop = Input::get('shop');
                $accessToken = $shopify->getAccessToken($code);
                $shopify->setup(['ACCESS_TOKEN' => $accessToken]);

                //update user token and login; if no user then create user and login
                if ($this->auth->attempt($shopify, ['shop' => $shop, 'token' => $accessToken])) {
                    // flash()->success('Login successful!');
                } else {
                    \Log::error('Login attempt failed.');
                    // flash()->error($this->_loginErrorMsg);
                    return redirect('login');                    
                }
            } else {
                \Log::error('Shopify verify request failed.');
                // flash()->error($this->_loginErrorMsg);
                return redirect('login');
            }
        } catch (\Exception $e) {
            \Log::error($e);
            // flash()->error($this->_loginErrorMsg);
            return redirect('login');
        }

        $user = $this->auth->getUser();
        if (!is_null($user) && $user->getChargeId()==0) {
            
            return $this->_processCharge($user, $shopify);
        }

        return redirect('init');
    }

    //create charge
    public function charge() 
    {
        $shop = $this->auth->getShop();
        $accessToken = $this->auth->getAccessToken();
        if (is_null($shop) || is_null($accessToken)) {
            // flash()->error('Security Token expired, please re-connect to your store.');
            \Log::error('No shop or token session on charge.');
            return redirect('login');
        }

        $shopify = App::make('ShopifyAPI');
        $shopify->setup(['SHOP_DOMAIN' => $shop, 'ACCESS_TOKEN' => $accessToken]);
        $user = $this->auth->user();

        if (isset($_GET['charge_id'])) {
            $chargeId = $_GET['charge_id'];
            $charge = $shopify->call(['URL' => '/admin/recurring_application_charges/'.$chargeId.'.json', 'RETURNARRAY' => true]);           

            if ($charge['recurring_application_charge']['status'] == 'accepted') {
                //activate charge
                $shopify->call(['URL' => '/admin/recurring_application_charges/'.$chargeId.'/activate.json', 'METHOD' => 'POST']);     

                $test = 0;
                if ($charge['recurring_application_charge']['test']) {
                    $test = 1;
                }

                $trialDays = $user->getRemainingTrialDays();

                date_default_timezone_set('Europe/Bucharest');
                $activatedOn = date("Y-m-d H:i:s", strtotime($charge['recurring_application_charge']['created_at']));
         
                $data = [
                    'charge_id' => $chargeId,
                    'is_test' => $test,
                    'activated_on' => $activatedOn,
                    'trial_days' => $trialDays,
                    'price' => $charge['recurring_application_charge']['price']
                ];
                
                $user->update($data);

                return redirect('init');
            } else {                
                // $this->auth->logout();
                return redirect('declined');
            }
        } else {
            return $this->_processCharge($user, $shopify);
        }

        return redirect('init');
    }

    protected function _processCharge($_user, $_shopify, $_redirectWithJs = false)
    {
        $price = env('APP_PRICE');
        
        $trialDays = $_user->getRemainingTrialDays();
        $isTest = $this->shopify->getIsTest($_user->getId());

        $call = $_shopify->call(['URL' => '/admin/recurring_application_charges.json', 'METHOD' => 'POST', 'RETURNARRAY' => true, 'DATA' => ['recurring_application_charge' => ['name'=> env('APP_NAME').' Membership','price'=>$price, 'return_url'=>env('APP_URL').'/charge', 'trial_days'=>$trialDays, 'test'=>$isTest]]]);           
        
        if (isset($call['recurring_application_charge']['confirmation_url'])) {
            if (!$_redirectWithJs) {
                return redirect($call['recurring_application_charge']['confirmation_url']);
            } else {
                return "<script type='text/javascript'>
                            window.top.location.href = '".$call['recurring_application_charge']['confirmation_url']."';
                        </script>";
            }
        }
    } 

    public function getDeclinedCharge()
    {       
        $user = $this->auth->user();        
        $returnUrl = env('APP_URL').'/login?shop='.$user->getShop();
        
        return view('shopify::declined', compact('returnUrl'));
    }

    //add assets, webhooks etc.
    public function init() 
    {
        $redirect = CurrencyHelper::init();
      
        return redirect($redirect);
    }

    public function review()
    {
        $user = $this->auth->user();
        if (!ShopifyLog::hasReviewed($user->getId())) {
            ShopifyLog::review($user->getId());
        }

        return json_encode(array('success'=>true));
    }

    public function uninstall() 
    {
        if (!isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256']) || !isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'])) {
            \Log::error('Uninstall tried to be accessed by a user.');
            return redirect('login');
        }

        $hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
        $shop = $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'];
        $data = file_get_contents('php://input');
        //convert the json to array
        $decodedData = json_decode($data, true);
        $verified = $this->shopify->verifyWebhook($data, $hmac_header);

        if ($verified) {
            //get user
            $user = $this->auth->user($shop);

            //update remaining trial days
            $remainingDays = $user->getRemainingTrialDays();
            //remove charge
            $user->update(['charge_id' => 0, 'store_down' => 0, 'trial_days' => $remainingDays, 'last_uninstall' => Carbon::now('Europe/Bucharest')->format('Y-m-d H:i:s')]);
        }
    }

    public function getTest(Request $request)
    {   
        return;   
    }
}
// 