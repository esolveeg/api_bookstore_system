<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $guarded = [];
    public function productsPivot()
    {
        return $this->hasMany('App\TransactionProduct' , 'transaction_id');
    }
    public function branch()
    {
    	return $this->belongsTo('App\Branch');
    }
    public function products()
    {
    	return $this->belongsToMany('App\Product' , 'transaction_product' , 'transaction_id' , 'product_id')->withPivot('qty');
    }

    public static function search($value){
        return self::where('approved', 'like', '%' . $value . '%');                
    }
}
