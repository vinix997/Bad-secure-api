<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Transaction extends Model 
{
    const BOOKED = 1;
    const PAID = 2;
    const CANCELLED = 3;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'transaction';

    protected $fillable = [
        'user_id', 'ticket_id', 'total', 'status_id',
    ];

    
    
   
}
