<?php
//shopify routes
Route::get('login', 'grizzlyapps\shopify\Http\Controllers\ShopifyController@getLogin');
Route::post('postLogin', 'grizzlyapps\shopify\Http\Controllers\ShopifyController@postLogin');
Route::get('logout', 'grizzlyapps\shopify\Http\Controllers\ShopifyController@getLogout');
Route::get('auth', 'grizzlyapps\shopify\Http\Controllers\ShopifyController@authorize');
Route::get('charge', 'grizzlyapps\shopify\Http\Controllers\ShopifyController@charge');
Route::get('declined', 'grizzlyapps\shopify\Http\Controllers\ShopifyController@getDeclinedCharge');
Route::get('init', 'grizzlyapps\shopify\Http\Controllers\ShopifyController@init');
Route::post('uninstall', 'grizzlyapps\shopify\Http\Controllers\ShopifyController@uninstall');
Route::get('test', 'grizzlyapps\shopify\Http\Controllers\ShopifyController@getTest');
Route::post('review','grizzlyapps\shopify\Http\Controllers\ShopifyController@review');



