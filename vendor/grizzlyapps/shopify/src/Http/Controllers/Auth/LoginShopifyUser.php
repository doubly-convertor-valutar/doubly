<?php 

namespace Grizzlyapps\Shopify\Http\Controllers\Auth;

use App;
use Input;
use Grizzlyapps\Shopify\Http\Requests\LoginRequest;

trait LoginShopifyUser
{
    use RedirectsUsers;

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        if ($shop = Input::get('shop')) {
            return $this->_login($shop);    
        }
        
        return view('shopify::login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(LoginRequest $request)
    {
        $shop = $request->only('shop')['shop'];
        
        return $this->_login($shop);
    }

    protected function _login($_shop) 
    {
        if (strpos($_shop, '.myshopify.com') === false) {
            $_shop .= '.myshopify.com';
        }

        $sh = App::make('ShopifyAPI');
        $sh->setup(['SHOP_DOMAIN' => $_shop]);
        $this->redirectPath = $sh->installURL(['permissions' => explode(',',env('PERMISSIONS')), 'redirect' => env('APP_URL').'/auth']);

        return "<script type='text/javascript'>
                    window.top.location.href = '".$this->redirectPath."';
                </script>";
        // return redirect($this->redirectPath());
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {        
        $this->auth->logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/login');
    }

}
