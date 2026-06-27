<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentJoin extends Model
{
    use HasFactory;

    protected $table = 'agent_joins'; // table name (default bhi same hoga)

    protected $fillable = [
        'user_id',
        'amount',
        'txnid',
        'name',
        'mobile',
        'email',

        'referred_person_name',
        'referred_person_mobile',
        'referred_person_email',

        'manager_name',
        'manager_company_email',

        'payment_status',
        'payment_id',
        'order_id',

        'status',
        
        'sales_name',
        'sales_email'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // User relation (important for future use)
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods (optional but useful)
    |--------------------------------------------------------------------------
    */

    public function isPaid()
    {
        return $this->payment_status === 'success';
    }

    public function isPending()
    {
        return $this->payment_status === 'pending';
    }
}