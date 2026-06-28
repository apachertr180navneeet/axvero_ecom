<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\V2\MyfatoorahController as ApiMyfatoorahController;

class MyfatoorahController extends Controller
{
    public function callback()
    {
        return (new ApiMyfatoorahController)->callback();
    }
}
