<?php

namespace Grizzlyapps\Shopify\Http\Middleware;

// use App;
use Closure;
use Grizzlyapps\Shopify\ShopifyAuth;

class ShopifyAuthenticated
{
    //pages that are visible without login or charge_id
    protected $_guestExceptions = [
        'login',
        'postLogin',
        'auth',
        
        'charge',
        'declined',

        'uninstall',
        'test',
        'getCountry'
    ];

    //pages that don't require a shop session
    protected $_shopExceptions = [
        'login',
        'postLogin',
        'auth',

        'uninstall',
        'test',
        'getCountry'
    ];

    protected $auth;

    public function __construct(ShopifyAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (($this->auth->guest() && !in_array($request->segment(1), $this->_guestExceptions)) || ($this->auth->getShop()=='' && !in_array($request->segment(1), $this->_shopExceptions))) {
        // if (($this->auth->guest() && !in_array($request->segment(1), $this->_guestExceptions))) {
            if ($request->segment(1)=='logout') {
                // flash()->success('You\'ve been successfully logged out. Hope you have an awesome, bear free day!');
            } else {
                \Log::error('Security Token expired, please re-connect to your store.');   
                flash()->error('Security Token expired, please re-connect to your store.');    
            }
            
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect('login');
            }
        }

        // get shopify connection
        // $session = $request->getSession();
        // $auth = new ShopifyAuth($session);
        // $shop = $auth->getShop();
        // $accessToken = $auth->getAccessToken();
        // $sh = App::make('ShopifyAPI');
        // $sh->setup(['SHOP_DOMAIN' => $shop, 'ACCESS_TOKEN' => $accessToken]);
        // $request->shopify = $sh;

        return $next($request);
    }
}
