<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model {

	protected $table = 'settings';
	protected $fillable = ['key','value'];
	protected $hidden = ['user_id'];
	public $timestamps = false;

}
