<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateCustomer extends Model
{
    protected $fillable = ['affiliate_id', 'customer_id'];

    public function affiliate()
    {
        return $this->belongsTo(AffiliateUser::class, 'affiliate_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
