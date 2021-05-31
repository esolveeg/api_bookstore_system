<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    //
    protected $guarded = [];
    //relations

    public function branch()
    {
    	return $this->belongsTo('App\Branch');
    }
}
