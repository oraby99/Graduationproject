<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    protected $fillable = [
        'name', 'address', 'phone', 'country', 'relationship', 'quantity','email','code','type'
    ];
}
