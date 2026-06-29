<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliatePaymentSetting extends Model
{
    protected $fillable = [
        'user_id',
        'bank_name',
        'bank_acc_name',
        'bank_acc_no',
        'bank_iban',
        'bank_routing_no',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
