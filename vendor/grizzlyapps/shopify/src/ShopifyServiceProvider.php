<?php 

namespace Grizzlyapps\Shopify;

use Illuminate\Support\ServiceProvider;
use Grizzlyapps\Shopify\Http\Validators\ShopifyValidator;

class ShopifyServiceProvider extends ServiceProvider {

   /**
    * Bootstrap the application services.
    *
    * @return void
    */
   public function boot()
   {
      //change views directory
      $this->loadViewsFrom(__DIR__.'/views', 'shopify');

      //new validator for login form to include shopify_url validations
      $this->app->validator->resolver(function($translator, $data, $rules, $messages = array(), $customAttributes = array()) {
          return new ShopifyValidator($translator, $data, $rules, $messages, $customAttributes);
      });

      //link shopify log facade to model
      $this->app->bindShared('shopify.log', function () {
          return $this->app->make('Grizzlyapps\Shopify\Log');
      });

      view()->composer('app', 'Grizzlyapps\Shopify\Http\Composers\ShopifyComposer');
      view()->composer('shopify::login', 'Grizzlyapps\Shopify\Http\Composers\ShopifyComposer');
      view()->composer('shopify::declined', 'Grizzlyapps\Shopify\Http\Composers\ShopifyComposer');

      //add Shopify Helper
      $this->app->bindShared('shopify.helper', function () {
          return $this->app->make('Grizzlyapps\Shopify\Helpers\ShopifyHelper');
      });
   }

   /**
    * Register the application services.
    *
    * @return void
    */
   public function register()
   {
      include __DIR__.'/routes.php';
      $this->app->make('Grizzlyapps\Shopify\Http\Controllers\ShopifyController');
    
      $this->app->bind('ShopifyHelper', function()
      {
          return new Grizzlyapps\Shopify\Helpers\ShopifyHelper;
      });
   }

}