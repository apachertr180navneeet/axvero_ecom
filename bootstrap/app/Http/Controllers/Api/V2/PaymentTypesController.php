<?php


namespace App\Http\Controllers\Api\V2;

use App\Models\Cart;
use App\Models\ManualPaymentMethod;
use Illuminate\Http\Request;

class PaymentTypesController
{

    public function getList(Request $request)
    {
        $mode = "order";

        if ($request->has('mode')) {
            $mode = $request->mode; // wallet or other things , comes from query param ?mode=wallet
        }

        $list = "both";
        if ($request->has('list')) {
            $list = $request->list; // ?list=offline
        }

        $payment_types = array();

        if ($list == "online" || $list == "both") {
            $all_online_payment_methods = get_activate_payment_methods();
            if (count($all_online_payment_methods) > 0) {
                $available_online_payment_methods = [
                    "paypal", "stripe", "instamojo", "razorpay", "paystack", "iyzico", "bkash", "nagad", "sslcommerz", "aamarpay", "flutterwave", "payfast", "paytm", "khalti", "myfatoorah", "phonepe", "cybersource"
                ];
                if (get_setting('phonepe_version', '1') != 2) {
                    $available_online_payment_methods = array_diff($available_online_payment_methods, ['phonepe']);
                }
                $online_payment_methods = $all_online_payment_methods->toQuery()->whereIn('name', $available_online_payment_methods)->get();

                foreach ($online_payment_methods as $online_payment_method){
                    if ($online_payment_method->active == 1) {
                        $payment_type = array();
                        $payment_type['payment_type'] = $online_payment_method->name;
                        $payment_type['payment_type_key'] = $online_payment_method->name;
                        $payment_type['image'] = static_asset('assets/img/cards/'.$online_payment_method->name.'.png');
                        $payment_type['name'] = ucfirst($online_payment_method->name);
                        $payment_type['title'] = translate("Checkout with ".$online_payment_method->name);
                        $payment_type['offline_payment_id'] = 0;
                        $payment_type['details'] = "";
                        if ($mode == 'wallet') {
                            $payment_type['title'] = translate("Recharge with ".$online_payment_method->name);
                        }

                        $payment_types[] = $payment_type;
                    }
                }
            }


        }

        // you cannot recharge wallet by wallet or cash payment
        if ($mode != 'wallet' && $mode != 'seller_package' && $list != "offline") {
            if (get_setting('wallet_system') == 1) {
                $payment_type = array();
                $payment_type['payment_type'] = 'wallet_system';
                $payment_type['payment_type_key'] = 'wallet';
                $payment_type['image'] = static_asset('assets/img/cards/wallet.png');
                $payment_type['name'] = "Wallet";
                $payment_type['title'] = translate("Wallet Payment");
                $payment_type['offline_payment_id'] = 0;
                $payment_type['details'] = "";

                $payment_types[] = $payment_type;
            }

            $haveDigitalProduct = false;
            $cash_on_delivery = false;

            if ($mode == "order") {
                $user   = auth()->user();
                $carts = ($user != null) ?
                        Cart::where('user_id', $user->id)->active()->get() :
                        ($request->has('temp_user_id') ? Cart::where('temp_user_id', $request->temp_user_id)->active()->get() : [] );

                foreach ($carts as $key => $cart) {
                    $haveDigitalProduct =  $cart->product->digital == 1;
                    $cash_on_delivery =  $cart->product->cash_on_delivery == 0;
                    if ($haveDigitalProduct || $cash_on_delivery) {
                        break;
                    }
                }
            }

            if (get_setting('cash_payment') == 1  && !$haveDigitalProduct && !$cash_on_delivery) {
                $payment_type = array();
                $payment_type['payment_type'] = 'cash_payment';
                $payment_type['payment_type_key'] = 'cash_on_delivery';
                $payment_type['image'] = static_asset('assets/img/cards/cod.png');
                $payment_type['name'] = "Cash Payment";
                $payment_type['title'] = translate("Cash on delivery");
                $payment_type['offline_payment_id'] = 0;
                $payment_type['details'] = "";

                $payment_types[] = $payment_type;
            }
        }

        if ($list == 'offline' || $list == "both") {
            foreach (ManualPaymentMethod::all() as $method) {

                $bank_list = "";
                $bank_list_item = "";

                if ($method->bank_info != null) {
                    foreach (json_decode($method->bank_info) as $key => $info) {
                        $bank_list_item .= "<li>" . 'Bank Name' . " -  {$info->bank_name} ," .  'Account Name' . "  -  $info->account_name , " . 'Account Number' . " - {$info->account_number} , " . 'Routing Number' . " - {$info->routing_number}</li>";
                    }
                    $bank_list = "<ul> $bank_list_item <ul>";
                }

                $payment_type = array();
                $payment_type['payment_type'] = 'manual_payment';
                $payment_type['payment_type_key'] = 'manual_payment_' . $method->id;
                $payment_type['image'] = uploaded_asset($method->photo);
                $payment_type['name'] = $method->heading;
                $payment_type['title'] = $method->heading;
                $payment_type['offline_payment_id'] = $method->id;
                $payment_type['details'] = "<div> {$method->description} $bank_list  </div>";

                $payment_types[] = $payment_type;
            }
        }

        return response()->json($payment_types);
    }
}
