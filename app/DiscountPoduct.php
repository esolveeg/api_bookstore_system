<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountProduct extends Model
{
    //
    protected $table = "discount_product";
    protected $guarded = [];

    public function discounts()
    {
    	return $this->hasMany('App\Discount');
    }

    public function products()
    {
    	return $this->hasMany('App\Product');
    }
}
