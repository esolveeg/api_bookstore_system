<?php

namespace App;

use App\Balance;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    
    protected $guarded = [];
    public function stocks()
    {
        return $this->hasMany('App\Stock');
    }
    public function balances()
    {
        return $this->hasMany('App\Balance');
    }
    

    public function expenses()
    {
        return $this->hasMany('App\Expense');
    }
    
    public function orders()
    {
        return $this->hasMany('App\Order');
    }
    public function outcomingOrders()
    {
        return $this->hasMany('App\OutcomingOrder');
    }
    public function employees()
    {
        return $this->hasMany('App\Employee');
    }

    public function getBalanceAttribute()
    {
        return Balance::where('branch_id' , $this->id)->orderBy('created_at' , 'DESC')->first()->balance;
    }

    public static function search($value){
        return self::where('email', 'like', '%' . $value . '%')
                ->orWhere('name', 'like', '%' . $value . '%')
                ->orWhere('phone', 'like', '%' . $value . '%')
                ->orWhere('rent', 'like', '%' . $value . '%')
                ->orWhere('address', 'like', '%' . $value . '%');
    }
}
