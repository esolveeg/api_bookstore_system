<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    protected $guarded= [];
    public static function search($value){
        return self::where('email', 'like', '%' . $value . '%')
                ->orWhere('name', 'like', '%' . $value . '%')
                ->orWhere('phone', 'like', '%' . $value . '%');
    }

    public function branch()
    {
    	return $this->belongsTo('App\Branch');
    }
}
