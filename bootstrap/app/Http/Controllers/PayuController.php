<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PayuController extends Controller
{
    public function paymentForm()
    {
        return view('payu');
    }

    public function pay(Request $request)
    {
        $key  = env('PAYU_KEY');
        $salt = env('PAYU_SALT');

        $txnid = uniqid();
        $amount = $request->amount;
        $productinfo = "Test Product";
        $firstname = $request->name;
        $email = $request->email;

        $hash = strtolower(hash('sha512',
            $key.'|'.$txnid.'|'.$amount.'|'.$productinfo.'|'.$firstname.'|'.$email.'|||||||||||'.$salt
        ));

        return view('payu_redirect', compact(
            'key','txnid','amount','productinfo',
            'firstname','email','hash'
        ));
    }

    public function response(Request $request)
    {
        if ($request->status == 'success') {
            return "Payment Successful. Txn ID: ".$request->txnid;
        } else {
            return "Payment Failed";
        }
    }
}
