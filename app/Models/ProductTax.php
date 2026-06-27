<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductTax extends Model
{
    use HasFactory, PreventDemoModeChanges;

    //
}
