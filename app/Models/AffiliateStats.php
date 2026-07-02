<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class AffiliateStats extends Model
{
    use PreventDemoModeChanges;

    protected $fillable = ['user_id', 'no_of_click', 'no_of_item_sold', 'no_of_delivered', 'no_of_canceled', 'total_amount'];
}
