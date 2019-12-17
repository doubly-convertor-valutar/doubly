<?php namespace App\Helpers;

use Grizzlyapps\Shopify\ShopifyAuth;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ThemeHelper
{
    use DispatchesJobs;

    public function __construct(ShopifyAuth $auth)
    {
        $this->auth = $auth;
    }

    public function getThemes()
    {
        $themes = array(
        );

        return $themes;
    }

    public function getThemeScript($themeStoreId, $currencySwitcherTheme)
    {
        $themes = $this->getThemes();

        if (in_array($themeStoreId, $themes)) {
            $script = '';
                
            return $script;
        } else {
            return false;
        }
    }
}