<?php

namespace App\Models;

use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = [
        'address_id',
        'price',
        'tax',
        'shipping_cost',
        'discount',
        'online_pay_discount', 
        'product_referral_code',
        'coupon_code',
        'is_online_pay',
        'coupon_applied',
        'quantity',
        'user_id',
        'temp_user_id',
        'owner_id',
        'product_id',
        'variation'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
