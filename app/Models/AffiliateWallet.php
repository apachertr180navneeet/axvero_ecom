<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateWallet extends Model
{
    protected $fillable = ['affiliate_id', 'total_earned', 'total_withdrawn', 'available_balance', 'pending_balance'];

    public function affiliate()
    {
        return $this->belongsTo(AffiliateUser::class, 'affiliate_id');
    }
}
