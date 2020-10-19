<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = ['source',  'vat_percentage', 'description', 'mop', 'amount', 'date_received'];
}
