<?php

namespace Grizzlyapps\Shopify;

// use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class User extends Model
{
    public $timestamps = false;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['shop', 'token', 'plan_name', 'shop_name', 'domain', 'email', 'name', 'last_login', 'charge_id', 'store_down', 'is_test', 'activated_on', 'last_uninstall', 'trial_days', 'theme_store_id', 'theme_name', 'daily_hits', 'total_hits', 'price'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['shop', 'token', 'plan_name', 'shop_name', 'domain', 'email', 'name', 'last_login', 'charge_id', 'store_down', 'is_test', 'activated_on', 'last_uninstall', 'trial_days', 'theme_store_id', 'theme_name', 'daily_hits', 'total_hits', 'price'];

    public function getId() {
        return $this->id;
    }

    public function getShop() {
        return $this->shop;
    }

    public function getToken() {
        return $this->token;
    }

    public function getPlanName() {
        return $this->plan_name;
    }

    public function getShopName() {
        return $this->shop_name;
    }

    public function getDomain() {
        return $this->domain;
    }

    public function getLastLogin() {
        return $this->last_login;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getName() {
        return $this->name;
    }

    public function getChargeId() {
        if (!isset($this->charge_id)) {
            return 0;
        }
        
        return $this->charge_id;
    }

    public function getStoreDown() {        
        return $this->store_down;
    }

    public function getTrialDays() {
        if (isset($this->trial_days) && ($this->trial_days!=0 || ($this->trial_days==0 && $this->activated_on!='0000-00-00 00:00:00'))) {
            return $this->trial_days;
        }

        return env('TRIAL_DAYS');
    }

    //remaining trial days on current date
    public function getRemainingTrialDays() {
        if ($this->getChargeId()!=0) {
            $trialStartedOn = new Carbon($this->getActivatedOn(), 'Europe/Bucharest');
            $now = Carbon::now('Europe/Bucharest');
            $trialDays = $this->getTrialDays() + $now->diffInDays($trialStartedOn, false);
            $trialDays = ($trialDays<0) ? 0 : $trialDays;
        } else {
            $trialDays = $this->getTrialDays();
        }

        return $trialDays;
    }

    public function getActivatedOn() {
        return $this->activated_on;
    }

    public function getThemeStoreId() {
        return $this->theme_store_id;
    }

    public function getThemeName() {
        return $this->theme_name;
    }

    public function getDailyHits() {
        return $this->daily_hits;
    }

    public function getTotalHits() {
        return $this->total_hits;
    }

    public function getPrice() {
        return $this->price;
    }

    //connect user to logs
    public function logs()
    {
        return $this->hasMany('Grizzlyapps\Shopify\Log');
    }

    //connect user to logs
    public function settings()
    {
        return $this->hasMany('App\Settings');
    }

    //connect user to logs
    public function currencies()
    {
        return $this->hasMany('App\Currency');
    }

    //connect user to logs
    public function countries()
    {
        return $this->hasMany('App\CurrencyByCountry');
    }
}
