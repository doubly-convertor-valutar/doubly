<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CurrencyByCountry extends Model {

	protected $table = 'currency_by_country';
	protected $fillable = ['currency','country_code'];
	protected $hidden = ['user_id'];
	public $timestamps = false;

}
