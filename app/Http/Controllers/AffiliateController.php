<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AffiliateOption;
use App\Models\AffiliateConfig;
use App\Models\AffiliateUser;
use App\Models\AffiliatePayment;
use App\Models\AffiliateWithdrawRequest;
use App\Models\AffiliateLog;
use App\Models\AffiliateStats;
use App\Models\AffiliateEarningDetail;
use App\Models\AffiliatePaymentSetting;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Str;

class AffiliateController extends Controller
{
    public function index()
    {
        $affiliate_options = AffiliateOption::all();
        return view('backend.affiliate.index', compact('affiliate_options'));
    }

    public function affiliate_option_store(Request $request)
    {
        $affiliate_option = AffiliateOption::where('type', $request->type)->first();
        if (!$affiliate_option) {
            $affiliate_option = new AffiliateOption;
            $affiliate_option->type = $request->type;
        }
        $affiliate_option->details = $request->details;
        $affiliate_option->status = $request->status;
        $affiliate_option->save();

        flash(translate('Affiliate option has been updated successfully'))->success();
        return back();
    }

    public function configs()
    {
        $categories = \App\Models\Category::all();
        return view('backend.affiliate.configs', compact('categories'));
    }

    public function config_store(Request $request)
    {
        if ($request->has('configs') && is_array($request->configs)) {
            foreach ($request->configs as $type => $value) {
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                $affiliate_config = AffiliateConfig::where('type', $type)->first();
                if (!$affiliate_config) {
                    $affiliate_config = new AffiliateConfig;
                    $affiliate_config->type = $type;
                }
                $affiliate_config->value = $value;
                $affiliate_config->save();
            }
            flash(translate('Affiliate configs have been updated successfully'))->success();
            return back();
        }

        $affiliate_config = AffiliateConfig::where('type', $request->type)->first();
        if (!$affiliate_config) {
            $affiliate_config = new AffiliateConfig;
            $affiliate_config->type = $request->type;
        }
        $affiliate_config->value = $request->value;
        $affiliate_config->save();

        flash(translate('Affiliate config has been updated successfully'))->success();
        return back();
    }

    public function users()
    {
        $affiliate_users = AffiliateUser::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('backend.affiliate.users', compact('affiliate_users'));
    }

    public function show_verification_request($id)
    {
        $affiliate_user = AffiliateUser::with('user')->findOrFail($id);
        return view('backend.affiliate.show_verification_request', compact('affiliate_user'));
    }

    public function approve_user($id)
    {
        $affiliate_user = AffiliateUser::findOrFail($id);
        $affiliate_user->status = 1;
        $affiliate_user->save();

        flash(translate('Affiliate user has been approved successfully'))->success();
        return back();
    }

    public function reject_user($id)
    {
        $affiliate_user = AffiliateUser::findOrFail($id);
        $affiliate_user->status = 0;
        $affiliate_user->save();

        flash(translate('Affiliate user has been rejected'))->success();
        return back();
    }

    public function updateApproved(Request $request)
    {
        $affiliate_user = AffiliateUser::findOrFail($request->id);
        $affiliate_user->status = $request->status;
        $affiliate_user->save();

        return 1;
    }

    public function payment_modal(Request $request)
    {
        $affiliate_user = AffiliateUser::with('user')->findOrFail($request->id);
        return view('backend.affiliate.payment_modal', compact('affiliate_user'));
    }

    public function payment_store(Request $request)
    {
        $affiliate_payment = new AffiliatePayment;
        $affiliate_payment->affiliate_user_id = $request->affiliate_user_id;
        $affiliate_payment->amount = $request->amount;
        $affiliate_payment->payment_method = $request->payment_method;
        $affiliate_payment->payment_details = $request->payment_details;
        $affiliate_payment->save();

        $affiliate_user = AffiliateUser::findOrFail($request->affiliate_user_id);
        $user = $affiliate_user->user;
        if ($user) {
            $user->balance -= $request->amount;
            $user->save();
        }

        flash(translate('Payment has been made successfully'))->success();
        return back();
    }

    public function payment_history($id)
    {
        $affiliate_user = AffiliateUser::with('user')->findOrFail($id);
        $affiliate_payments = AffiliatePayment::where('affiliate_user_id', $id)->orderBy('created_at', 'desc')->paginate(15);
        return view('backend.affiliate.payment_history', compact('affiliate_user', 'affiliate_payments'));
    }

    public function refferal_users()
    {
        $referral_users = User::whereNotNull('referral_code')->orderBy('created_at', 'desc')->paginate(15);
        return view('backend.affiliate.refferal_users', compact('referral_users'));
    }

    public function affiliate_withdraw_requests()
    {
        $affiliate_withdraw_requests = AffiliateWithdrawRequest::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('backend.affiliate.withdraw_requests', compact('affiliate_withdraw_requests'));
    }

    public function affiliate_withdraw_modal(Request $request)
    {
        $affiliate_withdraw_request = AffiliateWithdrawRequest::with('user')->findOrFail($request->id);
        return view('backend.affiliate.affiliate_withdraw_modal', compact('affiliate_withdraw_request'));
    }

    public function withdraw_request_payment_store(Request $request)
    {
        $affiliate_withdraw_request = AffiliateWithdrawRequest::findOrFail($request->id);
        $affiliate_withdraw_request->status = 1;
        $affiliate_withdraw_request->transaction_id = $request->transaction_id;
        $affiliate_withdraw_request->remarks = $request->remarks;
        $affiliate_withdraw_request->approved_by = auth()->user()->id;
        $affiliate_withdraw_request->approved_at = now();
        $affiliate_withdraw_request->save();

        $affiliate_user = AffiliateUser::where('user_id', $affiliate_withdraw_request->user_id)->first();

        $affiliate_payment = new AffiliatePayment;
        $affiliate_payment->affiliate_user_id = $affiliate_user->id;
        $affiliate_payment->amount = $affiliate_withdraw_request->amount;
        $affiliate_payment->payment_method = $request->payment_method;
        $affiliate_payment->payment_details = $request->payment_details;
        $affiliate_payment->save();

        // Update Affiliate Wallet Ledger
        if ($affiliate_user) {
            $wallet = \App\Models\AffiliateWallet::firstOrCreate(
                ['affiliate_id' => $affiliate_user->id],
                ['total_earned' => 0, 'total_withdrawn' => 0, 'available_balance' => 0, 'pending_balance' => 0]
            );
            $wallet->available_balance = max(0, $wallet->available_balance - $affiliate_withdraw_request->amount);
            $wallet->total_withdrawn += $affiliate_withdraw_request->amount;
            $wallet->save();
        }

        $user = $affiliate_withdraw_request->user;
        if ($user) {
            $user->balance -= $affiliate_withdraw_request->amount;
            $user->save();
        }

        flash(translate('Withdraw request has been paid successfully'))->success();
        return back();
    }

    public function reject_withdraw_request($id)
    {
        $affiliate_withdraw_request = AffiliateWithdrawRequest::findOrFail($id);
        $affiliate_withdraw_request->status = 2;
        $affiliate_withdraw_request->save();

        flash(translate('Withdraw request has been rejected'))->success();
        return back();
    }

    public function affiliate_logs_admin()
    {
        $affiliate_logs = AffiliateLog::with('user', 'order', 'order_detail')->orderBy('created_at', 'desc')->paginate(15);
        return view('backend.affiliate.logs', compact('affiliate_logs'));
    }

    public function apply_for_affiliate()
    {
        if (auth()->check()) {
            $affiliate_user = AffiliateUser::where('user_id', auth()->user()->id)->first();
            if ($affiliate_user) {
                flash(translate('You have already applied for affiliate'))->warning();
                return redirect()->route('home');
            }
        }
        return view('frontend.affiliate.apply');
    }

    public function store_affiliate_user(Request $request)
    {
        if (!auth()->check()) {
            flash(translate('Please login first'))->warning();
            return redirect()->route('user.login');
        }
        $affiliate_user = new AffiliateUser;
        $affiliate_user->user_id = auth()->user()->id;
        $affiliate_user->status = 0;
        $affiliate_user->save();

        if (auth()->user()->referral_code == null) {
            auth()->user()->referral_code = substr(auth()->user()->id . Str::random(10), 0, 10);
            auth()->user()->save();
        }

        flash(translate('Your affiliate account has been created. Wait for approval.'))->success();
        return redirect()->route('home');
    }

    public function user_index(Request $request)
    {
        $affiliate_user = AffiliateUser::where('user_id', auth()->user()->id)->first();
        
        $query = AffiliateLog::with('customer', 'order')
            ->where('user_id', auth()->user()->id);

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $affiliate_logs = $query->orderBy('created_at', 'desc')->paginate(10);
        $affiliate_stats = AffiliateStats::where('user_id', auth()->user()->id)->first();

        $wallet = \App\Models\AffiliateWallet::firstOrCreate(
            ['affiliate_id' => $affiliate_user->id],
            ['total_earned' => 0, 'total_withdrawn' => 0, 'available_balance' => 0, 'pending_balance' => 0]
        );
        $total_referrals = \App\Models\AffiliateCustomer::where('affiliate_id', $affiliate_user->id)->count();
        $total_orders = AffiliateLog::where('user_id', auth()->user()->id)
            ->whereNotNull('order_id')
            ->distinct('order_id')
            ->count('order_id');

        return view('frontend.affiliate.index', compact(
            'affiliate_user',
            'affiliate_logs',
            'affiliate_stats',
            'wallet',
            'total_referrals',
            'total_orders'
        ));
    }

    public function user_payment_history()
    {
        $affiliate_user = AffiliateUser::where('user_id', auth()->user()->id)->first();
        $affiliate_payments = AffiliatePayment::where('affiliate_user_id', $affiliate_user->id)->orderBy('created_at', 'desc')->paginate(15);
        return view('frontend.affiliate.payment_history', compact('affiliate_payments'));
    }

    public function user_withdraw_request_history()
    {
        $affiliate_user = AffiliateUser::where('user_id', auth()->user()->id)->first();
        $wallet = \App\Models\AffiliateWallet::firstOrCreate(
            ['affiliate_id' => $affiliate_user->id],
            ['total_earned' => 0, 'total_withdrawn' => 0, 'available_balance' => 0, 'pending_balance' => 0]
        );
        $affiliate_withdraw_requests = AffiliateWithdrawRequest::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(15);
        return view('frontend.affiliate.withdraw_request_history', compact('affiliate_withdraw_requests', 'wallet'));
    }

    public function payment_settings()
    {
        $payment_settings = AffiliatePaymentSetting::firstOrNew(['user_id' => auth()->user()->id]);
        return view('frontend.affiliate.payment_settings', compact('payment_settings'));
    }

    public function payment_settings_store(Request $request)
    {
        $payment_settings = AffiliatePaymentSetting::updateOrCreate(
            ['user_id' => auth()->user()->id],
            [
                'bank_name' => $request->bank_name,
                'bank_acc_name' => $request->bank_acc_name,
                'bank_acc_no' => $request->bank_acc_no,
                'bank_iban' => $request->bank_iban,
                'bank_routing_no' => $request->bank_routing_no,
            ]
        );

        flash(translate('Payment settings has been updated successfully'))->success();
        return back();
    }

    public function withdraw_request_store(Request $request)
    {
        $user = auth()->user();
        $affiliate_user = AffiliateUser::where('user_id', $user->id)->first();
        if (!$affiliate_user) {
            flash(translate('Affiliate account not found.'))->error();
            return back();
        }

        // Get wallet
        $wallet = \App\Models\AffiliateWallet::firstOrCreate(
            ['affiliate_id' => $affiliate_user->id],
            ['total_earned' => 0, 'total_withdrawn' => 0, 'available_balance' => 0, 'pending_balance' => 0]
        );

        $amount = doubleval($request->amount);

        // Check if user has sufficient available balance (accounting for existing pending requests)
        $pending_total = AffiliateWithdrawRequest::where('user_id', $user->id)->where('status', 0)->sum('amount');
        if ($wallet->available_balance < ($pending_total + $amount)) {
            flash(translate('Insufficient available affiliate balance'))->error();
            return back();
        }

        // 1. Min/Max Limit Checks
        $min_limit_config = AffiliateConfig::where('type', 'minimum_withdrawal_amount')->first();
        $max_limit_config = AffiliateConfig::where('type', 'maximum_withdrawal_amount')->first();

        if ($min_limit_config && $min_limit_config->value !== null && $amount < doubleval($min_limit_config->value)) {
            flash(translate('Amount is less than the minimum withdrawal limit of ') . single_price($min_limit_config->value))->error();
            return back();
        }

        if ($max_limit_config && $max_limit_config->value !== null && $amount > doubleval($max_limit_config->value)) {
            flash(translate('Amount exceeds the maximum withdrawal limit of ') . single_price($max_limit_config->value))->error();
            return back();
        }

        // 2. Frequency Limit Check
        $frequency_config = AffiliateConfig::where('type', 'withdrawal_frequency')->first();
        if ($frequency_config && $frequency_config->value !== null) {
            $days = intval($frequency_config->value);
            if ($days > 0) {
                $last_request = AffiliateWithdrawRequest::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($last_request && $last_request->created_at->diffInDays(now()) < $days) {
                    flash(translate('You can only make one withdraw request every ') . $days . translate(' days.'))->error();
                    return back();
                }
            }
        }

        $affiliate_withdraw_request = new AffiliateWithdrawRequest;
        $affiliate_withdraw_request->user_id = $user->id;
        $affiliate_withdraw_request->amount = $amount;
        $affiliate_withdraw_request->payment_method = $request->payment_method;
        $affiliate_withdraw_request->account_details = $request->account_details;
        $affiliate_withdraw_request->status = 0; // pending
        $affiliate_withdraw_request->save();

        flash(translate('Withdraw request has been sent successfully'))->success();
        return back();
    }

    public function processAffiliateStats($userId, $type, $quantity, $noOfDelivered, $noOfCanceled)
    {
        $affiliate_stats = AffiliateStats::firstOrNew(['user_id' => $userId]);
        
        if ($type == 1) {
            $affiliate_stats->no_of_click += 1;
        } elseif ($type == 0) {
            $affiliate_stats->no_of_item_sold += $quantity;
            $affiliate_stats->no_of_delivered += $noOfDelivered;
            $affiliate_stats->no_of_canceled += $noOfCanceled;
        }

        $affiliate_stats->save();
    }

    public function processAffiliatePoints($order)
    {
        $buyer = $order->user;
        if (!$buyer) {
            return;
        }

        // 1. User Registration First Purchase Commission
        $first_purchase_option = AffiliateOption::where('type', 'user_registration_first_purchase')->first();
        if ($first_purchase_option && $first_purchase_option->status == 1) {
            // Check if this is the buyer's first order
            $orders_count = Order::where('user_id', $buyer->id)->count();

            if ($orders_count <= 1 && $buyer->referred_by) {
                $referrer = User::find($buyer->referred_by);
                if ($referrer) {
                    $referrer_affiliate = AffiliateUser::where('user_id', $referrer->id)->where('status', 1)->first();
                    if ($referrer_affiliate) {
                        // Check if commission is already logged for this order
                        $already_logged = AffiliateLog::where('user_id', $referrer->id)
                            ->where('order_id', $order->id)
                            ->where('log_type', 2) // 2 = Registration First Purchase
                            ->exists();

                        if (!$already_logged) {
                            $details = json_decode($first_purchase_option->details, true) ?? [];
                            $commission_rate = doubleval($details['commission_rate'] ?? 0);
                            $commission_type = $details['commission_type'] ?? 'percent';

                            if ($commission_type == 'percent') {
                                $commission = ($order->grand_total) * $commission_rate / 100;
                            } else {
                                $commission = $commission_rate;
                            }

                            if ($commission > 0) {
                                // Create Affiliate Log
                                $log = new AffiliateLog;
                                $log->user_id = $referrer->id;
                                $log->order_id = $order->id;
                                $log->customer_id = $buyer->id;
                                $log->referral_amount = $commission;
                                $log->commission_type = $commission_type;
                                $log->commission_value = $commission_rate;
                                $log->status = 'pending';
                                $log->log_type = 2; // Registration First Purchase
                                $log->save();

                                // Update pending wallet balance
                                $this->updateWalletBalance($referrer->id, $commission, 'pending');

                                // Update referrer stats
                                $stats = AffiliateStats::firstOrNew(['user_id' => $referrer->id]);
                                $stats->total_amount += $commission;
                                $stats->save();
                            }
                        }
                    }
                }
            }
        }

        // 2. Product Sharing / Category / Global Affiliate Commission (Priority Hierarchy)
        foreach ($order->orderDetails as $order_detail) {
            $referrer = null;
            
            // Determine referrer
            if ($order_detail->product_referral_code) {
                $referrer = User::where('referral_code', $order_detail->product_referral_code)->first();
            } elseif ($buyer->referred_by) {
                $referrer = User::find($buyer->referred_by);
            }

            if ($referrer && $referrer->id != $buyer->id) {
                $referrer_affiliate = AffiliateUser::where('user_id', $referrer->id)->where('status', 1)->first();
                if ($referrer_affiliate) {
                    // Check if commission is already logged for this order detail
                    $already_logged = AffiliateLog::where('user_id', $referrer->id)
                        ->where('order_detail_id', $order_detail->id)
                        ->exists();

                    if (!$already_logged) {
                        $product = $order_detail->product;
                        if ($product) {
                            $commission_info = $this->getCommissionRateAndType($referrer->id, $product->id, $product->category_id);
                            $rate = $commission_info['rate'];
                            $commission_type = $commission_info['type'];

                            $commission = 0;
                            if ($commission_type == 'percent') {
                                $commission = ($order_detail->price - $order_detail->coupon_discount) * $rate / 100;
                            } else {
                                $commission = $rate * $order_detail->quantity;
                            }

                            if ($commission > 0) {
                                // Create Affiliate Log
                                $log = new AffiliateLog;
                                $log->user_id = $referrer->id;
                                $log->order_id = $order->id;
                                $log->order_detail_id = $order_detail->id;
                                $log->customer_id = $buyer->id;
                                $log->referral_amount = $commission;
                                $log->commission_type = $commission_type;
                                $log->commission_value = $rate;
                                $log->status = 'pending';
                                $log->log_type = 0; // 0 = Sale
                                $log->save();

                                // Update pending wallet balance
                                $this->updateWalletBalance($referrer->id, $commission, 'pending');

                                // Update stats
                                $stats = AffiliateStats::firstOrNew(['user_id' => $referrer->id]);
                                $stats->total_amount += $commission;
                                $stats->save();
                            }
                        }
                    }
                }
            }
        }
    }

    public function getCommissionRateAndType($referrerId, $productId, $categoryId)
    {
        // 1. Affiliate Custom Rate
        $affiliate_rate_config = AffiliateConfig::where('type', 'affiliate_rate_' . $referrerId)->first();
        if ($affiliate_rate_config && $affiliate_rate_config->value !== null) {
            $details = json_decode($affiliate_rate_config->value, true);
            if ($details && isset($details['rate'])) {
                return [
                    'rate' => doubleval($details['rate']),
                    'type' => $details['type'] ?? 'percent'
                ];
            }
        }

        // 2. Product Custom Rate
        $product_rate_config = AffiliateConfig::where('type', 'product_rate_' . $productId)->first();
        if ($product_rate_config && $product_rate_config->value !== null) {
            $details = json_decode($product_rate_config->value, true);
            if ($details && isset($details['rate'])) {
                return [
                    'rate' => doubleval($details['rate']),
                    'type' => $details['type'] ?? 'percent'
                ];
            }
        }

        // 3. Category Rate
        $category_rate_config = AffiliateConfig::where('type', 'category_rate_' . $categoryId)->first();
        if ($category_rate_config && $category_rate_config->value !== null) {
            $details = json_decode($category_rate_config->value, true);
            if ($details && isset($details['rate'])) {
                return [
                    'rate' => doubleval($details['rate']),
                    'type' => $details['type'] ?? 'percent'
                ];
            }
        }

        // 4. Global Rate
        $global_rate = AffiliateConfig::where('type', 'global_commission_value')->first();
        $global_type = AffiliateConfig::where('type', 'global_commission_type')->first();
        if ($global_rate && $global_rate->value !== null) {
            return [
                'rate' => doubleval($global_rate->value),
                'type' => $global_type ? $global_type->value : 'percent'
            ];
        }

        // Fallback to legacy options
        $product_sharing_option = AffiliateOption::where('type', 'product_sharing')->first();
        if ($product_sharing_option && $product_sharing_option->status == 1) {
            $details = json_decode($product_sharing_option->details, true) ?? [];
            return [
                'rate' => doubleval($details['commission_rate'] ?? 0),
                'type' => $details['commission_type'] ?? 'percent'
            ];
        }

        return [
            'rate' => 0,
            'type' => 'percent'
        ];
    }

    public function updateWalletBalance($userId, $amount, $type = 'pending')
    {
        $affiliate_user = AffiliateUser::where('user_id', $userId)->first();
        if (!$affiliate_user) {
            return;
        }

        $wallet = \App\Models\AffiliateWallet::firstOrCreate(
            ['affiliate_id' => $affiliate_user->id],
            ['total_earned' => 0, 'total_withdrawn' => 0, 'available_balance' => 0, 'pending_balance' => 0]
        );

        if ($type == 'pending') {
            $wallet->pending_balance += $amount;
        } elseif ($type == 'approved') {
            $wallet->pending_balance = max(0, $wallet->pending_balance - $amount);
            $wallet->available_balance += $amount;
            $wallet->total_earned += $amount;

            // Sync to the user's main wallet balance
            $user = $affiliate_user->user;
            if ($user) {
                $user->balance += $amount;
                $user->save();
            }
        } elseif ($type == 'rejected') {
            $wallet->pending_balance = max(0, $wallet->pending_balance - $amount);
        }

        $wallet->save();
    }

    public function approveExpiredPendingCommissions()
    {
        $pending_logs = AffiliateLog::where('status', 'pending')->whereNotNull('order_id')->get();

        foreach ($pending_logs as $log) {
            $order = $log->order;
            if ($order && $order->delivery_status == 'delivered') {
                $refund_days = 7; // Default fallback
                if ($log->order_detail_id) {
                    $order_detail = $log->order_detail;
                    if ($order_detail && $order_detail->refund_days !== null) {
                        $refund_days = $order_detail->refund_days;
                    }
                }

                // If return period is expired
                $delivered_at = \Carbon\Carbon::parse($order->delivered_date);
                if (now()->diffInDays($delivered_at) >= $refund_days) {
                    $log->status = 'approved';
                    $log->approved_at = now();
                    $log->save();

                    $this->updateWalletBalance($log->user_id, $log->referral_amount, 'approved');
                }
            }
        }
    }

    public function orderDeliveredHook($orderId)
    {
        $pending_logs = AffiliateLog::where('order_id', $orderId)->where('status', 'pending')->get();
        foreach ($pending_logs as $log) {
            $refund_days = 7;
            if ($log->order_detail_id) {
                $order_detail = $log->order_detail;
                if ($order_detail && $order_detail->refund_days !== null) {
                    $refund_days = $order_detail->refund_days;
                }
            }

            if ($refund_days == 0) {
                $log->status = 'approved';
                $log->approved_at = now();
                $log->save();

                $this->updateWalletBalance($log->user_id, $log->referral_amount, 'approved');
            }
        }
    }

    public function rejectOrderCommissions($orderId)
    {
        $pending_logs = AffiliateLog::where('order_id', $orderId)->where('status', 'pending')->get();
        foreach ($pending_logs as $log) {
            $log->status = 'rejected';
            $log->save();

            $this->updateWalletBalance($log->user_id, $log->referral_amount, 'rejected');
        }
    }

    public function referred_customers()
    {
        $affiliate_user = AffiliateUser::where('user_id', auth()->user()->id)->first();
        if (!$affiliate_user) {
            flash(translate('Affiliate account not found.'))->error();
            return redirect()->route('home');
        }

        // Fetch referred customers with their purchase details
        $referred_customers = \App\Models\AffiliateCustomer::with('customer')
            ->where('affiliate_id', $affiliate_user->id)
            ->paginate(15);

        // For each customer, calculate total orders and total spent
        foreach ($referred_customers as $referred_customer) {
            $customer_user = $referred_customer->customer;
            if ($customer_user) {
                $referred_customer->total_orders = Order::where('user_id', $customer_user->id)->count();
                $referred_customer->total_spent = Order::where('user_id', $customer_user->id)->sum('grand_total');
            } else {
                $referred_customer->total_orders = 0;
                $referred_customer->total_spent = 0;
            }
        }

        return view('frontend.affiliate.referred_customers', compact('referred_customers'));
    }
}
