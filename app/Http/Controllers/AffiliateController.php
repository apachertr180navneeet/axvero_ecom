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
        return view('backend.affiliate.configs');
    }

    public function config_store(Request $request)
    {
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
        $affiliate_withdraw_request->save();

        $affiliate_payment = new AffiliatePayment;
        $affiliate_payment->affiliate_user_id = AffiliateUser::where('user_id', $affiliate_withdraw_request->user_id)->first()->id;
        $affiliate_payment->amount = $affiliate_withdraw_request->amount;
        $affiliate_payment->payment_method = $request->payment_method;
        $affiliate_payment->payment_details = $request->payment_details;
        $affiliate_payment->save();

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

    public function user_index()
    {
        $affiliate_user = AffiliateUser::where('user_id', auth()->user()->id)->first();
        $affiliate_logs = AffiliateLog::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(10);
        $affiliate_stats = AffiliateStats::where('user_id', auth()->user()->id)->first();
        return view('frontend.affiliate.index', compact('affiliate_user', 'affiliate_logs', 'affiliate_stats'));
    }

    public function user_payment_history()
    {
        $affiliate_user = AffiliateUser::where('user_id', auth()->user()->id)->first();
        $affiliate_payments = AffiliatePayment::where('affiliate_user_id', $affiliate_user->id)->orderBy('created_at', 'desc')->paginate(15);
        return view('frontend.affiliate.payment_history', compact('affiliate_payments'));
    }

    public function user_withdraw_request_history()
    {
        $affiliate_withdraw_requests = AffiliateWithdrawRequest::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(15);
        return view('frontend.affiliate.withdraw_request_history', compact('affiliate_withdraw_requests'));
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
        if ($user->balance < $request->amount) {
            flash(translate('Insufficient balance'))->error();
            return back();
        }

        $affiliate_withdraw_request = new AffiliateWithdrawRequest;
        $affiliate_withdraw_request->user_id = $user->id;
        $affiliate_withdraw_request->amount = $request->amount;
        $affiliate_withdraw_request->status = 0;
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
            // Check if this is the buyer's first paid order
            $paid_orders_count = Order::where('user_id', $buyer->id)
                ->where('payment_status', 'paid')
                ->count();

            if ($paid_orders_count <= 1 && $buyer->referred_by) {
                $referrer = User::find($buyer->referred_by);
                if ($referrer) {
                    $referrer_affiliate = AffiliateUser::where('user_id', $referrer->id)->where('status', 1)->first();
                    if ($referrer_affiliate) {
                        // Check if commission is already awarded for user registration first purchase
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
                                $log->referral_amount = $commission;
                                $log->log_type = 2; // Registration First Purchase
                                $log->save();

                                // Update referrer balance
                                $referrer->balance += $commission;
                                $referrer->save();

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

        // 2. Product Sharing / Category Wise Affiliate Commission
        $category_wise_option = AffiliateOption::where('type', 'category_wise_affiliate')->first();
        $product_sharing_option = AffiliateOption::where('type', 'product_sharing')->first();

        foreach ($order->orderDetails as $order_detail) {
            if ($order_detail->product_referral_code) {
                $referrer = User::where('referral_code', $order_detail->product_referral_code)->first();
                if ($referrer && $referrer->id != $buyer->id) {
                    $referrer_affiliate = AffiliateUser::where('user_id', $referrer->id)->where('status', 1)->first();
                    if ($referrer_affiliate) {
                        // Check if commission is already awarded for this detail
                        $already_logged = AffiliateLog::where('user_id', $referrer->id)
                            ->where('order_detail_id', $order_detail->id)
                            ->whereIn('log_type', [0, 1])
                            ->exists();

                        if (!$already_logged) {
                            $commission = 0;
                            $calculated = false;

                            // 2a. Category Wise Commission check
                            if ($category_wise_option && $category_wise_option->status == 1) {
                                $details = json_decode($category_wise_option->details, true) ?? [];
                                $product = $order_detail->product;
                                if ($product && isset($details[$product->category_id])) {
                                    $rate = doubleval($details[$product->category_id]);
                                    $commission = ($order_detail->price - $order_detail->coupon_discount) * $rate / 100;
                                    $calculated = true;
                                }
                            }

                            // 2b. Product Sharing Commission check
                            if (!$calculated && $product_sharing_option && $product_sharing_option->status == 1) {
                                $details = json_decode($product_sharing_option->details, true) ?? [];
                                $rate = doubleval($details['commission_rate'] ?? 0);
                                $commission_type = $details['commission_type'] ?? 'percent';

                                if ($commission_type == 'percent') {
                                    $commission = ($order_detail->price - $order_detail->coupon_discount) * $rate / 100;
                                } else {
                                    $commission = $rate * $order_detail->quantity;
                                }
                            }

                            if ($commission > 0) {
                                // Create Affiliate Log
                                $log = new AffiliateLog;
                                $log->user_id = $referrer->id;
                                $log->order_id = $order->id;
                                $log->order_detail_id = $order_detail->id;
                                $log->referral_amount = $commission;
                                $log->log_type = 0; // 0 = Sale
                                $log->save();

                                // Update referrer balance
                                $referrer->balance += $commission;
                                $referrer->save();

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
    }
}
