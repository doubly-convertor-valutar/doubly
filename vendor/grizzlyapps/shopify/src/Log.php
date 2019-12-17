<?php

namespace Grizzlyapps\Shopify;

// use Grizzlyapps\Shopify\ShopifyAuth;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'user_id',
    	'type'
    ];

    public function user() 
    {
        return $this->belongsTo('Grizzlyapps\Shopify\User');
    }

    //user clicked on review
    public function review($userId) 
    {
        $this->create(['user_id'=>$userId,'type'=>'review']);
    }

    //user clicked on review
    public function hasReviewed($userId) 
    {
        $log = $this->where('type', '=', 'review')->where('user_id', '=', $userId)->first();

        return !is_null($log) ? true : false;
    }
}
