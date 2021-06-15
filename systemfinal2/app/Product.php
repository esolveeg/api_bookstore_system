<?php

namespace App;

use App\Stock;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $guarded = [];

    
    //relations

    public function orders()
    {
    	return $this->belongsToMany('App\Order');
    }
    public function getFooAttribute()
    {
        return Stock::where('product_id' , $this->id)->orderBy('created_at' , 'DESC')->first()->qty;
    }

    public function stocks()
    {
        return $this->hasMany('App\Stock');
    }

    public static function search($value){
        return self::where('isbn', 'like', '%' . $value . '%')
                ->orWhere('name', 'like', '%' . $value . '%')
                ->orWhere('price', 'like', '%' . $value . '%');
    }
}
