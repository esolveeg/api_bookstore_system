<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    protected $guarded = [];
    //relations 
    public function user()
    {
    	return $this->hasOne('App\User');
    }
    public function branch()
    {
    	return $this->belongsTo('App\Branch');
    }

    public static function search($value){
        return self::where('email', 'like', '%' . $value . '%')
                ->orWhere('phone', 'like', '%' . $value . '%')
                ->orWhere('salary', 'like', '%' . $value . '%')
                ->orWhere('name', 'like', '%' . $value . '%');
    }
    public function rebates()
    {
        return $this->belongsTo('App\Rebate');
    }
}
