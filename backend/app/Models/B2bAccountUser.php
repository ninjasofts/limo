<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class B2bAccountUser extends Model
{
    public $timestamps = false;

    protected $fillable = ['b2b_account_id', 'user_id', 'role'];
}
