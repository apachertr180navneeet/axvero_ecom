<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, PreventDemoModeChanges;

    protected $fillable = [
        'is_split_payment', 'advance_payment_amount', 'advance_payment_status',
        'advance_payment_details', 'cod_amount', 'cod_payment_status',
        'advance_paid_at', 'cod_paid_at'
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function refund_requests()
    {
        return $this->hasMany(RefundRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->hasOne(Shop::class, 'user_id', 'seller_id');
    }

    public function pickup_point()
    {
        return $this->belongsTo(PickupPoint::class);
    }

    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }

    public function affiliate_log()
    {
        return $this->hasMany(AffiliateLog::class);
    }

    public function club_point()
    {
        return $this->hasMany(ClubPoint::class);
    }

    public function delivery_boy()
    {
        return $this->belongsTo(User::class, 'assign_delivery_boy', 'id');
    }

    public function proxy_cart_reference_id()
    {
        return $this->hasMany(ProxyPayment::class)->select('reference_id');
    }

    public function commissionHistory()
    {
        return $this->hasOne(CommissionHistory::class);
    }

    // Split Payment Methods
    public function isSplitPayment()
    {
        return $this->is_split_payment == 1;
    }

    public function getTotalPaidAttribute()
    {
        if ($this->is_split_payment) {
            $paid = 0;
            if ($this->advance_payment_status == 'paid') $paid += $this->advance_payment_amount;
            if ($this->cod_payment_status == 'paid') $paid += $this->cod_amount;
            return $paid;
        }
        return $this->grand_total;
    }
}
