<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model 
{
    /*
    * @var string
    */
    protected $table = 'user_profiles';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'avatar','firstname', 'lastname','gender',   
    ];

}
