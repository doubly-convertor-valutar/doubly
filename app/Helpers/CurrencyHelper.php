<?php namespace App\Helpers;

use App;
use App\Country;
use App\Settings;
use App\Currency;
use Grizzlyapps\Shopify\ShopifyAuth;

class CurrencyHelper
{
  protected $auth;

	public function __construct(ShopifyAuth $auth)
  {
      $this->auth = $auth;
  }

    public function init() {
        $redirect = 'settings';
        $shop = $this->auth->getShop();
        $accessToken = $this->auth->getAccessToken();
        $shopify = App::make('ShopifyAPI');
        $shopify->setup(['SHOP_DOMAIN' => $shop, 'ACCESS_TOKEN' => $accessToken]);

        //Add default values
        $user = $this->auth->user();
        $currentSettings = $user->settings()->lists('value', 'key')->toArray();
        $firstInit = false;

        if (!isset($currentSettings['enabled'])) {
            $setting = new Settings;
            $setting->key = 'enabled';
            $setting->value = '1';
            $user->settings()->save($setting);
            $redirect = 'faq';
        }  
        //Select Currencies
        if (!isset($currentSettings['default_currency'])) {
            $data = $shopify->call(['URL' => '/admin/shop.json', 'RETURNARRAY' => true, 'DATA' => ['fields'=>'currency']]);           
            $setting = new Settings;
            $setting->key = 'default_currency';
            $setting->value = $data['shop']['currency'];
            $user->settings()->save($setting);

            if ($user->currencies()->count()==0) {
                $currency = new Currency;
                $currency->currency = $data['shop']['currency'];
                $currency->position = 1;
                $user->currencies()->save($currency);
                $firstInit = true;
            }
            $redirect = 'faq';
        }
        if (!isset($currentSettings['auto_switch'])) {
            $setting = new Settings;
            $setting->key = 'auto_switch';
            $setting->value = '1';
            $user->settings()->save($setting);
        }        
        if (!isset($currentSettings['cookie_name'])) {
            $settings = new Settings;
            $settings->key = 'cookie_name';
            $settings->value = 'currency';
            $user->settings()->save($settings);
        }

        //Design
        if (!isset($currentSettings['currency_switcher_theme'])) {
            $setting = new Settings;
            $setting->key = 'currency_switcher_theme';
            $setting->value = 'flags_theme';
            $user->settings()->save($setting);
        }

        //Layered Theme Design
        if (!isset($currentSettings['layered_background_color'])) {
            $setting = new Settings;
            $setting->key = 'layered_background_color';
            $setting->value = 'FFFFFF';
            $user->settings()->save($setting);
        }
        if (!isset($currentSettings['layered_text_color'])) {
            $setting = new Settings;
            $setting->key = 'layered_text_color';
            $setting->value = 'BFBFBF';
            $user->settings()->save($setting);
        }
        if (!isset($currentSettings['layered_hover_background_color'])) {
            $setting = new Settings;
            $setting->key = 'layered_hover_background_color';
            $setting->value = 'DDF6CF';
            $user->settings()->save($setting);
        }
        if (!isset($currentSettings['layered_hover_text_color'])) {
            $setting = new Settings;
            $setting->key = 'layered_hover_text_color';
            $setting->value = '89B171';
            $user->settings()->save($setting);
        }
        if (!isset($currentSettings['layered_selected_background_color'])) {
            $setting = new Settings;
            $setting->key = 'layered_selected_background_color';
            $setting->value = 'DE4C39';
            $user->settings()->save($setting);
        }
        if (!isset($currentSettings['layered_selected_text_color'])) {
            $setting = new Settings;
            $setting->key = 'layered_selected_text_color';
            $setting->value = 'FFFFFF';
            $user->settings()->save($setting);
        }

        //Flags Theme Design
        if (!isset($currentSettings['flag_background_color'])) {
            $setting = new Settings;
            $setting->key = 'flag_background_color';
            $setting->value = 'FFFFFF';
            $user->settings()->save($setting);
        }
        if (!isset($currentSettings['flag_text_color'])) {
            $setting = new Settings;
            $setting->key = 'flag_text_color';
            $setting->value = '403F3F';
            $user->settings()->save($setting);
        }
        if (!isset($currentSettings['flag_hover_color'])) {
            $setting = new Settings;
            $setting->key = 'flag_hover_color';
            $setting->value = 'F6F6F6';
            $user->settings()->save($setting);
        }

        //Price Configuration
        if (!isset($currentSettings['display_currency_name'])) {
            $setting = new Settings;
            $setting->key = 'display_currency_name';
            $setting->value = '1';
            $user->settings()->save($setting);
        }
        // if (!isset($currentSettings['round_conversion_price'])) {
        //     $setting = new Settings;
        //     $setting->key = 'round_conversion_price';
        //     $setting->value = '0';
        //     $user->settings()->save($setting);
        // }
        if (!isset($currentSettings['remove_decimals'])) {
            $setting = new Settings;
            $setting->key = 'remove_decimals';
            $setting->value = '0';
            $user->settings()->save($setting);
        }

        //Extra Features
        if (!isset($currentSettings['show_original_price_on_hover'])) {
            $setting = new Settings;
            $setting->key = 'show_original_price_on_hover';
            $setting->value = '0';
            $user->settings()->save($setting);
        }
        if (!isset($currentSettings['show_on_hover_background_color'])) {
            $setting = new Settings;
            $setting->key = 'show_on_hover_background_color';
            $setting->value = '333333';
            $user->settings()->save($setting);
        }
        if (!isset($currentSettings['show_on_hover_price_color'])) {
            $setting = new Settings;
            $setting->key = 'show_on_hover_price_color';
            $setting->value = 'FFFFFF';
            $user->settings()->save($setting);
        }
        if (!isset($currentSettings['check_out_curr_notify'])) {
            $setting = new Settings;
            $setting->key = 'check_out_curr_notify';
            $setting->value = '0';
            $user->settings()->save($setting);
        }
        if (!isset($currentSettings['message'])) {
            $setting = new Settings;
            $setting->key = 'message';
            $setting->value = 'All orders are processed in '.$data['shop']['currency'].'. While the content of your cart is currently displayed in {{ doubly.currency }}, you will checkout using '.$data['shop']['currency'].' at the most current exchange rate.';
            $user->settings()->save($setting);
        }
        if (!isset($currentSettings['check_out_curr_background_color'])) {
            $setting = new Settings;
            $setting->key = 'check_out_curr_background_color';
            $setting->value = '333333';
            $user->settings()->save($setting);
        }
        if (!isset($currentSettings['check_out_curr_message_color'])) {
            $setting = new Settings;
            $setting->key = 'check_out_curr_message_color';
            $setting->value = 'FFFFFF';
            $user->settings()->save($setting);
        }

        //Add assets
        $shopify->addAsset('assets/jquery.doubly.min.js', env('APP_URL').'/assets/js/jquery.doubly.min.js');
        $shopify->addAsset('assets/jquery.nice-select.min.js', env('APP_URL').'/assets/js/jquery.nice-select.min.js');
        $shopify->addAsset('assets/doubly.css', env('APP_URL').'/assets/css/doubly.css');
        $shopify->addAsset('assets/nice-select.css', env('APP_URL').'/assets/css/nice-select.css');

        //Add script tags
        $shopify->addScriptTag('https://cdn.shopify.com/s/javascripts/currencies.js');

        //Add assets
        $shopify->addAsset('assets/flags.png', env('APP_URL').'/assets/images/flags.png');

        //Add currency switcher liquid file
        if ($firstInit) {
            $this->createCurrencySwitcherLiquid();
        }
        
        //Add uninstall webhook
        $shopify->addWebhook('app/uninstalled', env('APP_URL').'/uninstall'); 

        return $redirect;
    }

  public function createCurrencySwitcherLiquid() {
		$settings = $this->auth->user()->settings()->lists('value', 'key')->toArray();
		$currencies = $this->auth->user()->currencies()->lists('currency')->toArray();		

		//get country currencies
		$countriesJSON = 'var currencyByCountry = [];';
		if ($settings['auto_switch']) {
			$countries = Country::get()->lists('currency_code', 'country_code');
            $currencyByCountry = $this->auth->user()->countries;
            if (count($currencyByCountry)>0) {
                foreach ($currencyByCountry as $country) {
                    $userCountries[$country->country_code] = $country->currency;
                }
                }
            foreach ($countries as $country => $currency) {
                if (isset($userCountries[$country])) {
                    $countries[$country] = $userCountries[$country];
                } else {
                $countries[$country] = $currency;	
                }
            }
            $countriesJSON = 'var currencyByCountry = jQuery.parseJSON(\''.json_encode($countries).'\');';
        }
        $displayCurrencyName = ($settings['display_currency_name']) ? 'money_with_currency_format' : 'money_format';
        $removeDecimals = $settings['remove_decimals'];
        $showPriceOnHover = $settings['show_original_price_on_hover'];
        $doublyMessageStyle = ($settings['check_out_curr_notify']) ? '.doubly-message { background-color: #'.$settings['check_out_curr_background_color'].' !important; color: #'.$settings['check_out_curr_message_color'].' !important; border-radius:5px; padding:3px 10px; }' : '';

        if ($settings['currency_switcher_theme']=='layered_theme') {
            $currencySymbols = Currency::getCurrencySymbols();
        		$content = '<ul class="layered-currency-switcher">
                         <li>';
            foreach ($currencies as $currency) {
              if (isset($currencySymbols[$currency])) {
                  $currencySymbol = $currencySymbols[$currency];  
              } else {
                  $currencySymbol = '';
              }
              $content .= '<button class="currency-switcher-btn" data-currency="'.$currency.'" href="javascript:void(0)"><span>'.$currency.'</span> '.$currencySymbol.'</button>';
            }
            $content .= '</li>
                        </ul>
                        <style>
                          .currency-switcher li button.currency-switcher-btn { background: #'.$settings['layered_background_color'].' !important; border-color: #'.$settings['layered_text_color'].' !important; color: #'.$settings['layered_text_color'].' !important; }
                          .currency-switcher li button.currency-switcher-btn:hover { background: #'.$settings['layered_hover_background_color'].' !important; border-color: #'.$settings['layered_hover_text_color'].' !important; color: #'.$settings['layered_hover_text_color'].' !important; }
                          .currency-switcher li button.currency-switcher-btn.selected { background: #'.$settings['layered_selected_background_color'].' !important; border-color: #'.$settings['layered_selected_background_color'].' !important; color: #'.$settings['layered_selected_text_color'].' !important; }
                          .price-on-hover { background-color: #'.$settings['show_on_hover_background_color'].' !important; color: #'.$settings['show_on_hover_price_color'].' !important; }
                          .price-on-hover:after { border-bottom-color: #'.$settings['show_on_hover_background_color'].' !important;}
                          '.$doublyMessageStyle.'
                        </style>';

        		$content .= "{% if content_for_header contains 'currencies.js' %}
                          {{ \"//cdn.shopify.com/s/javascripts/currencies.js\" | script_tag }}
                          <script>
                            var DoublyCurrency = Currency;
                            var cookieName = '".$settings['cookie_name']."';
                            var countryCookieName = '".str_replace('currency', 'country', $settings['cookie_name'])."';
                          </script>
                          {{ \"jquery.doubly.min.js\" | asset_url | script_tag }}
                          {{ \"doubly.css\" | asset_url | stylesheet_tag }}
                        {% endif %}
                        <script>  
                            if (typeof DoublyCurrency !== 'undefined') {     
                              var shopCurrency = '{{ shop.currency }}';
                              var defaultCurrency = shopCurrency;
                              var allowedCurrencies = jQuery.parseJSON('".json_encode($currencies)."');
                              var removeDecimals = ".$removeDecimals.";
                              var showPriceOnHover = ".$showPriceOnHover.";
                              var showCurrencyMessage = ".$settings['check_out_curr_notify'].";
                              var currencyMessage = '".str_replace('{{ doubly.currency }}', '<span class="selected-currency"></span>', $settings['message'])."';
                              DoublyCurrency.format = '{{ '".$displayCurrencyName."' | default: 'money_with_currency_format' }}';

                              /* Sometimes merchants change their shop currency, let's tell our JavaScript file */
                              DoublyCurrency.moneyFormats[shopCurrency].money_with_currency_format = {{ shop.money_with_currency_format | strip_html | json }};
                              DoublyCurrency.moneyFormats[shopCurrency].money_format = {{ shop.money_format | strip_html | json }};

                              function initCurrencySwitcher() {
                                  initLayeredDesign();
                                  /* Cookie currency */
                                  var cookieCurrency = DoublyCurrency.cookie.read();
                                  var buttons = jQuery('.currency-switcher-btn');

                                  /* Set select value before document ready functions fire to avoid lag */
                                  if (cookieCurrency == null || cookieCurrency == 'undefined') {
                                      jQuery('.currency-switcher-btn[data-currency=' + defaultCurrency + ']').click();
                                  } else {
                                      jQuery('.currency-switcher-btn[data-currency=' + cookieCurrency + ']').click();
                                  }
                                  
                                  jQuery(document).ready(function(){
                                      /* Fix for customer account pages */
                                      jQuery('span.doubly span.doubly').each(function() {
                                        jQuery(this).parents('span.doubly').removeClass('doubly');
                                      });

                                      /* If there's no cookie. */
                                      if (cookieCurrency == null || cookieCurrency == 'undefined') {
                                          DoublyCurrency.convertAll(defaultCurrency);
                                      }
                                      /* If the cookie value does not correspond to any value in the currency dropdown. */
                                      else if (jQuery.inArray(cookieCurrency,allowedCurrencies)===-1) {
                                        DoublyCurrency.currentCurrency = shopCurrency;
                                        DoublyCurrency.cookie.write(shopCurrency);
                                      } else {
                                        DoublyCurrency.convertAll(cookieCurrency);
                                      }
                                      
                                      buttons.click(function() {
                                        var newCurrency =  jQuery(this).attr('data-currency');
                                        DoublyCurrency.convertAll(newCurrency);
                                        initExtraFeatures();
                                      });
                                      
                                      jQuery(document).ajaxComplete(function() {
                                        var newCurrency = jQuery('.currency-switcher-btn.selected').attr('data-currency');
                                        DoublyCurrency.convertAll(newCurrency);
                                        initExtraFeatures();
                                      });

                                      initExtraFeatures();
                                  });
                              }

                              function initLayeredDesign() {
                                  $('.currency-switcher-btn').unbind('click');
                                  var selectedOption;
                                  $('.currency-switcher-btn').click(function(){
                                      selectedOption = $(this).attr('data-currency');
                                      $('.layered-currency-switcher').each(function(){
                                          var currencySwitcher = $(this);
                                          var a_length = currencySwitcher.find('.currency-switcher-btn').length;
                                          var temp_length = a_length;
                                          currencySwitcher.find('.currency-switcher-btn').each(function(){
                                            $(this).css({'z-index':a_length});
                                            a_length--;
                                          });

                                          var current  = currencySwitcher.find('.currency-switcher-btn[data-currency=\"'+selectedOption+'\"]');
                                          var constant = temp_length;
                                          current.addClass('selected');
                                          currencySwitcher.find('.currency-switcher-btn').not(current).removeClass('selected');

                                          var i = 1;
                                          var success = 0;
                                          currencySwitcher.find('.currency-switcher-btn').each(function(){
                                            if(!$(this).hasClass('selected')){
                                              if(success == 0){
                                                $(this).css({'z-index':i});
                                                $(this).css('text-align','center');
                                                $(this).css('padding-right','49px ');
                                            $(this).css('padding-left','11px ');
                                                i++;
                                              } else {
                                                constant--;
                                                $(this).css({'z-index':constant});
                                                $(this).css('text-align','center');
                                                $(this).css('padding-left','49px ');
                                            $(this).css('padding-right','11px');
                                                }
                                              } else {
                                                $(this).css({'z-index':constant});
                                                success = 1;
                                              }
                                          });
                                      });
                                  });
                              }

                              function initExtraFeatures() {
                                  /* initPriceHover */
                                  jQuery('span.doubly').unbind('mouseenter mouseleave');
                                  if (showPriceOnHover && DoublyCurrency.currentCurrency !== shopCurrency) {
                                      jQuery('span.doubly').hover(function() {
                                          jQuery(this).append('<span class=\"price-on-hover-wrapper\"><span class=\"price-on-hover\">'+jQuery(this).text()+'</span></span>');
                                          DoublyCurrency.convertAll(shopCurrency, '.price-on-hover');
                                      },function() {
                                          jQuery('span').remove('.price-on-hover-wrapper');
                                      });
                                  }
                                  
                                  /* initCartMessage */
                                  if (showCurrencyMessage) {
                                      jQuery('.doubly-message').html(currencyMessage);
                                      jQuery('.selected-currency').text(DoublyCurrency.currentCurrency);
                                  }
                                  if (DoublyCurrency.currentCurrency == shopCurrency) {
                                      jQuery('.doubly-message').hide();
                                  } else {
                                      jQuery('.doubly-message').show();
                                  }
                              }

                              /* Country code */
                              var autoSwitch = ".$settings['auto_switch'].";
                              if (autoSwitch) {
                                var cookieCountry = Country.cookie.read();
                                ".$countriesJSON."
                                if (cookieCountry == null || cookieCountry == 'undefined') {
                                  jQuery.get('https://freegeoip.net/json/', function(data) {
                                    countryCode = data.country_code;
                                      if (countryCode in currencyByCountry) {
                                          if (jQuery.inArray(currencyByCountry[countryCode],allowedCurrencies)!=-1) {
                                              defaultCurrency = currencyByCountry[countryCode];
                                              DoublyCurrency.cookie.write(defaultCurrency);
                                              Country.cookie.write(countryCode);
                                          } else {
                                              Country.cookie.write('not-allowed');
                                          }
                                          initCurrencySwitcher();
                                      }
                                  }, 'jsonp');
                                } else {
                                  if (jQuery.inArray(currencyByCountry[cookieCountry],allowedCurrencies)!=-1) {
                                    defaultCurrency = currencyByCountry[cookieCountry];
                                  }
                                  initCurrencySwitcher();
                                }
                              } else {
                                initCurrencySwitcher();
                              }

                              jQuery(document).ready(function(){
                                  var original_selectCallback = window.selectCallback;
                                  selectCallback = function(variant, selector) {
                                      original_selectCallback(variant, selector);
                                      var newCurrency = jQuery('.currency-switcher-btn.selected').attr('data-currency');
                                      DoublyCurrency.convertAll(newCurrency);
                                      initExtraFeatures();
                                  };
                              });
                            }
                        </script>";
        } else if ($settings['currency_switcher_theme'] == 'flags_theme') {  
            $content = '<select class="currency-switcher right" name="currencies">';
            $countries = Country::get()->lists('name', 'currency_code');
            $countries['EUR'] = 'European-Union';
            $countries['USD'] = 'United-States';
            $countries['GBP'] = 'United-Kingdom';
            $countries['DKK'] = 'Denmark';
            $countries['EEK'] = 'Estonia';
            $countries['XAU'] = 'XAU';
            $countries['GGP'] = 'Guernsey';
            $countries['XDR'] = 'IMF';
            $countries['IMP'] = 'Isle-of-Man';
            $countries['LVL'] = 'Latvia';
            $countries['LTL'] = 'Lithuania';
            $countries['MAD'] = 'Morocco';
            $countries['ANG'] = 'Curacao';
            $countries['NZD'] = 'New-Zealand';
            $countries['NOK'] = 'Norway';
            $countries['NOK'] = 'Norway';
            $countries['XAG'] = 'XAG';
            $countries['SKK'] = 'Slovakia';
            $countries['TVD'] = 'Tuvalu';
            $countries['XBT'] = 'Bitcoin';
            $countries['XPT'] = 'XPT';
            $countries['AUD'] = 'Australia';
            $currencyNames = Currency::getCurrencyList();
            foreach ($currencies as $currency) {
                $flag = '';
                if (isset($countries[$currency])) {
                    $flag = str_replace(' ', '-', $countries[$currency]);
                }
                $content .= '<option value="'.$currency.'" data-country="'.$flag.'" data-display="'.$currency.'">'.substr($currencyNames[$currency], 0, -6).'</option>';
            }

            $content .= '</select>';
            $content .= "{% if content_for_header contains 'currencies.js' %}
                          {{ \"//cdn.shopify.com/s/javascripts/currencies.js\" | script_tag }}
                          <script>
                            var DoublyCurrency = Currency;
                            var cookieName = '".$settings['cookie_name']."';
                            var countryCookieName = '".str_replace('currency', 'country', $settings['cookie_name'])."';
                          </script>
                          {{ \"jquery.doubly.min.js\" | asset_url | script_tag }}
                          {{ \"doubly.css\" | asset_url | stylesheet_tag }}

                          {{ \"jquery.nice-select.min.js\" | asset_url | script_tag }}
                          {{ \"nice-select.css\" | asset_url | stylesheet_tag }}
                          <style>
                           .nice-select, .nice-select .list { background: #".$settings['flag_background_color']."; }
                           .nice-select .current, .nice-select .list .option { color: #".$settings['flag_text_color']."; }
                           .nice-select .option:hover, .nice-select .option.focus, .nice-select .option.selected.focus { background-color: #".$settings['flag_hover_color']."; }
                           .price-on-hover { background-color: #".$settings['show_on_hover_background_color']." !important; color: #".$settings['show_on_hover_price_color']." !important; }
                           .price-on-hover:after { border-bottom-color: #".$settings['show_on_hover_background_color']." !important;}
                           ".$doublyMessageStyle."
                         </style>
                      {% endif %}
                      <script>  
                          if (typeof DoublyCurrency !== 'undefined') {     
                            var shopCurrency = '{{ shop.currency }}';
                            var defaultCurrency = shopCurrency;
                            var allowedCurrencies = jQuery.parseJSON('".json_encode($currencies)."');
                            var removeDecimals = ".$removeDecimals.";
                            var showPriceOnHover = ".$showPriceOnHover.";
                            var showCurrencyMessage = ".$settings['check_out_curr_notify'].";
                            var currencyMessage = '".str_replace('{{ doubly.currency }}', '<span class="selected-currency"></span>', $settings['message'])."';
                            DoublyCurrency.format = '{{ '".$displayCurrencyName."' | default: 'money_with_currency_format' }}';

                            /* Sometimes merchants change their shop currency, let's tell our JavaScript file */
                            DoublyCurrency.moneyFormats[shopCurrency].money_with_currency_format = {{ shop.money_with_currency_format | strip_html | json }};
                            DoublyCurrency.moneyFormats[shopCurrency].money_format = {{ shop.money_format | strip_html | json }};

                            function initCurrencySwitcher() {
                                /* Cookie currency */
                                var cookieCurrency = DoublyCurrency.cookie.read();

                                /* Set select value before document ready functions fire to avoid lag */
                                if (cookieCurrency == null || cookieCurrency == 'undefined') {
                                    jQuery('[name=currencies]').val(defaultCurrency);
                                } else {
                                    jQuery('[name=currencies]').val(cookieCurrency);
                                }

                                jQuery(document).ready(function(){
                                    jQuery('.currency-switcher').niceSelect();

                                    /* Fix for customer account pages */
                                    jQuery('span.doubly span.doubly').each(function() {
                                      jQuery(this).parents('span.doubly').removeClass('doubly');
                                    });

                                    /* If there's no cookie. */
                                    if (cookieCurrency == null || cookieCurrency == 'undefined') {
                                        DoublyCurrency.convertAll(defaultCurrency);
                                    }
                                    /* If the cookie value does not correspond to any value in the currency dropdown. */
                                    else if (jQuery.inArray(cookieCurrency,allowedCurrencies)===-1) {
                                        DoublyCurrency.currentCurrency = shopCurrency;
                                        DoublyCurrency.cookie.write(shopCurrency);
                                    } else {
                                        DoublyCurrency.convertAll(cookieCurrency);
                                    }
                                    
                                    jQuery('[name=currencies]').change(function() {
                                        var newCurrency = jQuery(this).val();
                                        DoublyCurrency.convertAll(newCurrency);
                                        
                                        /* in case more than 1 currency switcher, update value of all of them */
                                        jQuery('[name=currencies]').val(jQuery(this).val());

                                        initExtraFeatures();
                                    });
                                    
                                    jQuery(document).ajaxComplete(function() {
                                        DoublyCurrency.convertAll(jQuery('[name=currencies]').val());
                                        initExtraFeatures();
                                    });

                                    initExtraFeatures();
                                });
                            }

                            function initExtraFeatures() {
                                /* initPriceHover */
                                jQuery('span.doubly').unbind('mouseenter mouseleave');
                                if (showPriceOnHover && DoublyCurrency.currentCurrency !== shopCurrency) {
                                    jQuery('span.doubly').hover(function() {
                                        jQuery(this).append('<span class=\"price-on-hover-wrapper\"><span class=\"price-on-hover\">'+jQuery(this).text()+'</span></span>');
                                        DoublyCurrency.convertAll(shopCurrency, '.price-on-hover');
                                    },function() {
                                        jQuery('span').remove('.price-on-hover-wrapper');
                                    });
                                }

                                /* initCartMessage */
                                if (showCurrencyMessage) {
                                    jQuery('.doubly-message').html(currencyMessage);
                                    jQuery('.selected-currency').text(DoublyCurrency.currentCurrency);
                                }
                                if (DoublyCurrency.currentCurrency == shopCurrency) {
                                    jQuery('.doubly-message').hide();
                                } else {
                                    jQuery('.doubly-message').show();
                                }
                            }

                            /* Country code */
                            var autoSwitch = ".$settings['auto_switch'].";
                            if (autoSwitch) {
                              var cookieCountry = Country.cookie.read();
                              ".$countriesJSON."
                              if (cookieCountry == null || cookieCountry == 'undefined') {
                                jQuery.get('https://freegeoip.net/json/', function(data) {
                                  countryCode = data.country_code;
                                    if (countryCode in currencyByCountry) {
                                        if (jQuery.inArray(currencyByCountry[countryCode],allowedCurrencies)!=-1) {
                                            defaultCurrency = currencyByCountry[countryCode];
                                            DoublyCurrency.cookie.write(defaultCurrency);
                                            Country.cookie.write(countryCode);
                                        } else {
                                            Country.cookie.write('not-allowed');
                                        }
                                        initCurrencySwitcher();
                                    }
                                }, 'jsonp');
                              } else {
                                if (jQuery.inArray(currencyByCountry[cookieCountry],allowedCurrencies)!=-1) {
                                    defaultCurrency = currencyByCountry[cookieCountry];
                                }
                                initCurrencySwitcher();
                              }
                            } else {
                              initCurrencySwitcher();
                            }

                            jQuery(document).ready(function(){
                                var original_selectCallback = window.selectCallback;
                                selectCallback = function(variant, selector) {
                                    original_selectCallback(variant, selector);
                                    DoublyCurrency.convertAll(jQuery('[name=currencies]').val());
                                    initExtraFeatures();
                                };
                            });
                          }
                      </script>";    
        } else {      
            $content = '<select class="currency-switcher" name="currencies">';
            foreach ($currencies as $currency) {
                $content .= '<option value="'.$currency.'">'.$currency.'</option>';
            }
            $content .= '</select>
                         <style>
                           .price-on-hover { background-color: #'.$settings['show_on_hover_background_color'].' !important; color: #'.$settings['show_on_hover_price_color'].' !important; }
                           .price-on-hover:after { border-bottom-color: #'.$settings['show_on_hover_background_color'].' !important;}
                           '.$doublyMessageStyle.'
                         </style>';
            $content .= "{% if content_for_header contains 'currencies.js' %}
                          {{ \"//cdn.shopify.com/s/javascripts/currencies.js\" | script_tag }}
                          <script>
                            var DoublyCurrency = Currency;
                            var cookieName = '".$settings['cookie_name']."';
                            var countryCookieName = '".str_replace('currency', 'country', $settings['cookie_name'])."';
                          </script>
                          {{ \"jquery.doubly.min.js\" | asset_url | script_tag }}
                          {{ \"doubly.css\" | asset_url | stylesheet_tag }}
                      {% endif %}
                      <script>  
                          if (typeof DoublyCurrency !== 'undefined') {   
                            var shopCurrency = '{{ shop.currency }}';
                            var defaultCurrency = shopCurrency;
                            var allowedCurrencies = jQuery.parseJSON('".json_encode($currencies)."');  
                            var removeDecimals = ".$removeDecimals.";
                            var showPriceOnHover = ".$showPriceOnHover.";
                            var showCurrencyMessage = ".$settings['check_out_curr_notify'].";
                            var currencyMessage = '".str_replace('{{ doubly.currency }}', '<span class="selected-currency"></span>', $settings['message'])."';
                            DoublyCurrency.format = '{{ '".$displayCurrencyName."' | default: 'money_with_currency_format' }}';

                            /* Sometimes merchants change their shop currency, let's tell our JavaScript file */
                            DoublyCurrency.moneyFormats[shopCurrency].money_with_currency_format = {{ shop.money_with_currency_format | strip_html | json }};
                            DoublyCurrency.moneyFormats[shopCurrency].money_format = {{ shop.money_format | strip_html | json }};

                            function initCurrencySwitcher() {
                                /* Cookie currency */
                                var cookieCurrency = DoublyCurrency.cookie.read();

                                /* Set select value before document ready functions fire to avoid lag */
                                if (cookieCurrency == null || cookieCurrency == 'undefined') {
                                    jQuery('[name=currencies]').val(defaultCurrency);
                                } else {
                                    jQuery('[name=currencies]').val(cookieCurrency);
                                }
                                
                                jQuery(document).ready(function(){
                                    /* Fix for customer account pages */
                                    jQuery('span.doubly span.doubly').each(function() {
                                      jQuery(this).parents('span.doubly').removeClass('doubly');
                                    });

                                    /* If there's no cookie. */
                                    if (cookieCurrency == null || cookieCurrency == 'undefined') {
                                        DoublyCurrency.convertAll(defaultCurrency);
                                    }
                                    /* If the cookie value does not correspond to any value in the currency dropdown. */
                                    else if (jQuery.inArray(cookieCurrency,allowedCurrencies)===-1) {
                                      DoublyCurrency.currentCurrency = shopCurrency;
                                      DoublyCurrency.cookie.write(shopCurrency);
                                    } else {
                                      DoublyCurrency.convertAll(cookieCurrency);
                                    }
                                    
                                    jQuery('[name=currencies]').change(function() {
                                      var newCurrency = jQuery(this).val();
                                      DoublyCurrency.convertAll(newCurrency);
                                      
                                      /* in case more than 1 currency switcher, update value of all of them */
                                      jQuery('[name=currencies]').val(jQuery(this).val());

                                      initExtraFeatures();
                                    });
                                    
                                    jQuery(document).ajaxComplete(function() {
                                      DoublyCurrency.convertAll(jQuery('[name=currencies]').val());
                                      initExtraFeatures();
                                    });

                                    initExtraFeatures();
                                });
                            }

                            function initExtraFeatures() {
                                /* initPriceHover */
                                jQuery('span.doubly').unbind('mouseenter mouseleave');
                                if (showPriceOnHover && DoublyCurrency.currentCurrency !== shopCurrency) {
                                    jQuery('span.doubly').hover(function() {
                                        jQuery(this).append('<span class=\"price-on-hover-wrapper\"><span class=\"price-on-hover\">'+jQuery(this).text()+'</span></span>');
                                        DoublyCurrency.convertAll(shopCurrency, '.price-on-hover');
                                    },function() {
                                        jQuery('span').remove('.price-on-hover-wrapper');
                                    });
                                }

                                /* initCartMessage */
                                if (showCurrencyMessage) {
                                    jQuery('.doubly-message').html(currencyMessage);
                                    jQuery('.selected-currency').text(DoublyCurrency.currentCurrency);
                                }
                                if (DoublyCurrency.currentCurrency == shopCurrency) {
                                    jQuery('.doubly-message').hide();
                                } else {
                                    jQuery('.doubly-message').show();
                                }
                            }

                            /* Country code */
                            var autoSwitch = ".$settings['auto_switch'].";
                            if (autoSwitch) {
                              var cookieCountry = Country.cookie.read();
                              ".$countriesJSON."
                              if (cookieCountry == null || cookieCountry == 'undefined') {
                                jQuery.get('https://freegeoip.net/json/', function(data) {
                                  countryCode = data.country_code;
                                    if (countryCode in currencyByCountry) {
                                        if (jQuery.inArray(currencyByCountry[countryCode],allowedCurrencies)!=-1) {
                                            defaultCurrency = currencyByCountry[countryCode];
                                            DoublyCurrency.cookie.write(defaultCurrency);
                                            Country.cookie.write(countryCode);
                                        } else {
                                            Country.cookie.write('not-allowed');
                                        }
                                        initCurrencySwitcher();
                                    }
                                }, 'jsonp');
                              } else {
                                if (jQuery.inArray(currencyByCountry[cookieCountry],allowedCurrencies)!=-1) {
                                  defaultCurrency = currencyByCountry[cookieCountry];
                                }
                                initCurrencySwitcher();
                              }
                            } else {
                              initCurrencySwitcher();
                            }

                            jQuery(document).ready(function(){
                                var original_selectCallback = window.selectCallback;
                                selectCallback = function(variant, selector) {
                                    original_selectCallback(variant, selector);
                                    DoublyCurrency.convertAll(jQuery('[name=currencies]').val());
                                    initExtraFeatures();
                                };
                            });
                          }
                      </script>";
        }

        $content = trim(preg_replace('/\s+/', ' ', $content));
		$tmpFileName = str_random(10);
		\File::put('tmp/'.$tmpFileName.'.liquid',$content);

		$shop = $this->auth->getShop();
        $accessToken = $this->auth->getAccessToken();
        $shopify = App::make('ShopifyAPI');
        $shopify->setup(['SHOP_DOMAIN' => $shop, 'ACCESS_TOKEN' => $accessToken]);
        
        $shopify->addAsset('snippets/doubly.liquid', env('APP_URL').'/tmp/'.$tmpFileName.'.liquid', true);
        
		\File::delete('tmp/'.$tmpFileName.'.liquid');
	}

}