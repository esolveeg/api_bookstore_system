<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    protected $guarded=[];

    //relations
    public function permissions()
    {
    	return $this->belongsToMany('App\Permission');
    }
    public function users(){
    	return $this->belongsToMany('App\User');
    }
    public static function search($value){
        return self::where('description', 'like', '%' . $value . '%')
                ->orWhere('name', 'like', '%' . $value . '%');
    }
}
