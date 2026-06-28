<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Cart;
use App\Notifications\EmailVerificationNotification;
use App\Traits\PreventDemoModeChanges;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;


    public function sendEmailVerificationNotification()
    {
        $this->notify(new EmailVerificationNotification());
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'address', 'city', 'postal_code', 'phone', 'country', 'provider_id', 'email_verified_at', 'verification_code',
        'is_bulk_buyer', 'bulk_buyer_total_advance', 'bulk_buyer_total_pending', 'bulk_buyer_total_cod_received', 'bulk_buyer_since',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function shop()
    {
        return $this->hasOne(Shop::class);
    }
    public function seller()
    {
        return $this->hasOne(Seller::class);
    }


    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function seller_orders()
    {
        return $this->hasMany(Order::class, "seller_id");
    }
    public function seller_sales()
    {
        return $this->hasMany(OrderDetail::class, "seller_id");
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class)->orderBy('created_at', 'desc');
    }

    public function club_point()
    {
        return $this->hasOne(ClubPoint::class);
    }

    public function customer_package()
    {
        return $this->belongsTo(CustomerPackage::class);
    }

    public function customer_package_payments()
    {
        return $this->hasMany(CustomerPackagePayment::class);
    }

    public function customer_products()
    {
        return $this->hasMany(CustomerProduct::class);
    }

    public function seller_package_payments()
    {
        return $this->hasMany(SellerPackagePayment::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function product_bids()
    {
        return $this->hasMany(AuctionProductBid::class);
    }

    public function product_queries(){
        return $this->hasMany(ProductQuery::class,'customer_id');
    }

    public function uploads(){
        return $this->hasMany(Upload::class);
    }

    public function userCoupon(){
        return $this->hasOne(UserCoupon::class);
    }

    public function preorderProducts()
    {
        return $this->hasMany(PreorderProduct::class);
    }
    public function preorders()
    {
        return $this->hasMany(Preorder::class);
    }

    // Bulk Buyer Methods
    public function isBulkBuyer()
    {
        return $this->is_bulk_buyer == 1;
    }

    public function updateBulkBuyerTotals()
    {
        $this->bulk_buyer_total_advance = $this->orders()
            ->where('is_split_payment', 1)
            ->where('advance_payment_status', 'paid')
            ->sum('advance_payment_amount');
        
        $this->bulk_buyer_total_pending = $this->orders()
            ->where('is_split_payment', 1)
            ->where('cod_payment_status', 'unpaid')
            ->sum('cod_amount');
        
        $this->bulk_buyer_total_cod_received = $this->orders()
            ->where('is_split_payment', 1)
            ->where('cod_payment_status', 'paid')
            ->sum('cod_amount');
        
        $this->save();
    }
    

    

        
        
       
}
