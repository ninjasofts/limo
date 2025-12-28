<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class B2bAccount extends Model
{
    protected $fillable = [
        'company_name','company_type','vat_number','billing_email',
        'discount_type','discount_value','credit_limit','currency','status'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'b2b_account_users')
            ->withPivot('role');
    }
}
