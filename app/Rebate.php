<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rebate extends Model
{
    //
    public function employee()
    {
    	return $this->belongsTo('App\Employee');
    }

    public static function search($value){
        return self::where('cause', 'like', '%' . $value . '%')
                ->orWhere('value', 'like', '%' . $value . '%');
    }
}
