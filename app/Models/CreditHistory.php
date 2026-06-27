<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CreditHistory extends Model
{
    use HasFactory;

    protected $table = 'credit_histories';

    protected $fillable = [
        'user_id',
        'amount',
        'type'
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    /**
     * Relation with user
     */
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}