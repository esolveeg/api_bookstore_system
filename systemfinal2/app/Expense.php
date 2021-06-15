<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    //
    protected $guarded = [];
    //relations

    public function branch()
    {
    	return $this->belongsTo('App\Branch');
    }

    public static function search($value){
        return self::where('value', 'like', '%' . $value . '%')
                ->orWhere('note', 'like', '%' . $value . '%');
    }
}
