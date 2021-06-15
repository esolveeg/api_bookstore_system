<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutcomingOrder extends Model
{
    protected $guarded = [];
    public function supplier()
    {
    	return $this->belongsTo('App\Supplier');
    }

    public function productsPivot()
    {
        return $this->hasMany('App\OutComingOrderProduct' , 'outcoming_order_id');
    }

    public function created_by()
    {
        return $this->belongsTo('App\User' , 'created_by');
    }
    public function branch()
    {
        return $this->belongsTo('App\Branch');
    }

    public function updated_by()
    {
        return $this->belongsTo('App\User' , 'updated_by');
    }
    public function products()
    {
    	return $this->belongsToMany('App\Product')->withPivot('qty');
    }

    public static function search($value){
        return self::where('total', 'like', '%' . $value . '%');
    }
}
