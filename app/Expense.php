<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Expense extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'title', 'description','made_by', 'amount'
    ];


}
