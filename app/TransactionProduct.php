<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionProduct extends Model
{
    //
    protected $table = 'transaction_product';
    protected $guarded = [];

    public function transactions()
    {
    	return $this->hasMany('App\Transaction');
    }

    public function products()
    {
    	return $this->hasMany('App\Product');
    }
}
