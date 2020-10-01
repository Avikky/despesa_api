<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OpeningBalance extends Model
{
    protected $fillable = ['amount', 'date_created', 'created_at', 'updated_at'];
}
