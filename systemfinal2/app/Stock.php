<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    //
    protected $guarded = [];
    //relations

    public function branch()
    {
    	return $this->belongsTo('App\Branch');
    }
    public function product()
    {
    	return $this->belongsTo('App\Product');
    }
}
