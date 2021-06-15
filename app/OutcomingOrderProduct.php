<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutComingOrderProduct extends Model
{
    //
    protected $guarded = [];
    protected $table = "outcoming_order_product";

    public function product(){
    	return $this->belongsTo('App\Product');
    }
    public function order(){
    	return $this->belongsTo('App\OutcomingOrder' , 'outcoming_order_id');
    }
}
