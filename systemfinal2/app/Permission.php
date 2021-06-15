<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    //
    protected $guarded=[];

    //relations
    public function roles()
    {
    	return $this->belongsToMany('App\Role');
    }
}
