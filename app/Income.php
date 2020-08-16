<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = ['source', 'customer_id', 'type', 'description', 'mop', 'amount'];
}
