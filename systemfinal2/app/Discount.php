<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    //
    protected $guarded = [];

    public function products()
    {
    	return $this->belongsToMany('App\Product');
    }

    public function branch()
    {
    	return $this->belongsTo('App\Branch');
    }

    public static function search($value){
        return self::where('type', 'like', '%' . $value . '%')
        		->orWhere('title' , 'like', '%' . $value . '%' );
    }
}
