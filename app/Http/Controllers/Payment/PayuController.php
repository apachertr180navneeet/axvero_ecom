<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerPackage;
use App\Models\SellerPackage;
use App\Models\CombinedOrder;
use App\Http\Controllers\CustomerPackageController;
use App\Http\Controllers\SellerPackageController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\CheckoutController;
use App\Models\Order;
use Session;
use Auth;

class PayuController extends Controller
{
    public function pay()
    {
      \Log::info('PAYU PAY METHOD HIT');
        $email = Auth::user()->email ?? 'customer@example.com';

        $amount = 0;
        if (Session::has('payment_type')) {
            $paymentType = Session::get('payment_type');
            $paymentData = Session::get('payment_data');

            if ($paymentType == 'cart_payment') {
                $combined_order = CombinedOrder::findOrFail(Session::get('combined_order_id'));
                // $amount = number_format($combined_order->grand_total, 2, '.', '');
                $amount = $paymentData['amount'] ?? number_format($combined_order->grand_total, 2, '.', '');
           if (Session::get('bulk_cod') === true && Auth::user()->is_bulk_buyer == 1) {
                $amount = round((float)$amount * 0.40, 2);
            } else {
                $amount = round((float)$amount, 2);
            }
            
            // PayU ke liye final format
            $amount = number_format($amount, 2, '.', '');

            } elseif ($paymentType == 'order_re_payment') {
                $order = Order::findOrFail($paymentData['order_id']);
                $amount = number_format($order->grand_total, 2, '.', '');
            } elseif ($paymentType == 'wallet_payment') {
                $amount = number_format($paymentData['amount'], 2, '.', '');
            } elseif ($paymentType == 'customer_package_payment') {
                $customer_package = CustomerPackage::findOrFail($paymentData['customer_package_id']);
                $amount = number_format($customer_package->amount, 2, '.', '');
            } elseif ($paymentType == 'seller_package_payment') {
                $seller_package = SellerPackage::findOrFail($paymentData['seller_package_id']);
                $amount = number_format($seller_package->amount, 2, '.', '');
            }
        }
        $key  = env('PAYU_MERCHANT_KEY');
        $salt = env('PAYU_SALT');
        
        $payuUrl = env('PAYU_BASE_URL', 'https://secure.payu.in/_payment');


        $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
        $firstname = Auth::user()->name;
        $productinfo = 'Order';
        $amount = number_format((float)$amount, 2, '.', '');
        
        $hashString = $key . '|' . $txnid . '|' . $amount . '|' . $productinfo . '|' . $firstname . '|' . $email . '|||||||||||' . $salt;

        $hash = strtolower(hash('sha512', $hashString));


        return view('frontend.payu.redirect', compact(
            'payuUrl',
            'key',
            'txnid',
            'amount',
            'productinfo',
            'firstname',
            'email',
            'hash'
        ));
    }

    public function success(Request $request)
    {
        $payment_type = Session::get('payment_type');
        $payment_data = Session::get('payment_data');
        Session::forget('bulk_cod');
        if ($payment_type == 'cart_payment') {
            return (new CheckoutController)->checkout_done(
                Session::get('combined_order_id'),
                json_encode($request->all())
            );
        } elseif ($payment_type == 'order_re_payment') {
            return (new CheckoutController)->orderRePaymentDone(
                $payment_data,
                json_encode($request->all())
            );
        } elseif ($payment_type == 'wallet_payment') {
            return (new WalletController)->wallet_payment_done(
                $payment_data,
                json_encode($request->all())
            );
        } elseif ($payment_type == 'customer_package_payment') {
            return (new CustomerPackageController)->purchase_payment_done(
                $payment_data,
                json_encode($request->all())
            );
        } elseif ($payment_type == 'seller_package_payment') {
            return (new SellerPackageController)->purchase_payment_done(
                $payment_data,
                json_encode($request->all())
            );
        }
    }

    public function fail()
    {
        Session::forget('bulk_cod');
        flash(translate('Payment failed'))->error();
        return redirect()->route('cart');
    }
}
