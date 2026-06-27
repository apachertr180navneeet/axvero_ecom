<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CreditHistory;
use App\Models\User;

class CreditLimitController extends Controller
{

    /**
     * Wallet Page
     */
    public function index()
    {
        $user = auth()->user();

        // Remaining wallet balance (users table)
        $remainingCredit = $user->credit_balance ?? 0;

        // Transaction history
        $history = CreditHistory::where('user_id', $user->id)
                    ->latest()
                    ->get();

        return view('seller.credit_limit.index', compact('remainingCredit','history'));
    }


    /**
     * Start PayU Payment
     */
    public function addCredit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1100'
        
        ]);

        $user = auth()->user();

        $amount = $request->amount;

        $txnid = uniqid();

        $key = env('PAYU_MERCHANT_KEY');
        $salt = env('PAYU_SALT');

        $firstname = $user->name;
        $email = $user->email;

        $productinfo = "Credit Recharge";

        $hash_string = $key.'|'.$txnid.'|'.$amount.'|'.$productinfo.'|'.$firstname.'|'.$email.'|||||||||||'.$salt;

        $hash = strtolower(hash('sha512', $hash_string));

        return view('seller.credit_limit.payu_redirect', [
            'key' => $key,
            'txnid' => $txnid,
            'amount' => $amount,
            'firstname' => $firstname,
            'email' => $email,
            'productinfo' => $productinfo,
            'hash' => $hash
        ]);
    }


    /**
     * PayU Success Callback
     */
  
        public function paymentSuccess(Request $request)
{
    if(strtolower($request->status) == "success"){

        $user = User::where('email',$request->email)->first();

        if($user){

            $amount = (float)$request->amount;

            $user->credit_balance += $amount;
            $user->save();

            CreditHistory::create([
                'user_id'=>$user->id,
                'amount'=>$amount,
                'type'=>'credit'
            ]);
        }

       return redirect('/seller/credit-limit')
       ->with('success','Credit Added Successfully');
    }

    return redirect('/seller/credit-limit')
        ->with('error','Payment Failed');
}
    /**
     * PayU Failed Callback
     */
    public function paymentFailed()
    {
        return redirect()->route('seller.credit.limit')
            ->with('error','Payment Failed');
    }

}