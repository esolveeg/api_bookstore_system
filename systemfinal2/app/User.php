<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'employee_id' , 'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

   
    //relations 

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    public function employee()
    {
        return $this->belongsTo('App\Employee');
        
    }
    

    public static function search($value){
        return self::where('email', 'like', '%' . $value . '%')
                ->orWhere('name', 'like', '%' . $value . '%');
    }
}
