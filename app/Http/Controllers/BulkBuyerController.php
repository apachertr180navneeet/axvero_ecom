<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;

class BulkBuyerController extends Controller
{
    public function __construct() {
        $this->middleware(['permission:manage_bulk_buyers']);
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $bulk_buyers = User::where('is_bulk_buyer', 1)
            ->when($search, function($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('bulk_buyer_since', 'desc')
            ->paginate(15);
        
        return view('backend.bulk_buyer.index', compact('bulk_buyers', 'search'));
    }

    public function customers(Request $request)
    {
        $search = $request->search;
        $customers = User::where('user_type', 'customer')
            ->where('is_bulk_buyer', 0)
            ->when($search, function($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->paginate(15);
        
        return view('backend.bulk_buyer.customers', compact('customers', 'search'));
    }

    public function makeBulkBuyer($id)
    {
        $user = User::findOrFail($id);
        $user->is_bulk_buyer = 1;
        $user->bulk_buyer_since = now();
        $user->save();
        
        flash(translate('Customer marked as Bulk Buyer successfully'))->success();
        return redirect()->route('bulk_buyers.index');
    }

    public function removeBulkBuyer($id)
    {
        $user = User::findOrFail($id);
        $user->is_bulk_buyer = 0;
        $user->save();
        
        flash(translate('Bulk Buyer status removed successfully'))->success();
        return redirect()->route('bulk_buyers.customers');
    }

    public function show($id)
    {
        $bulk_buyer = User::where('is_bulk_buyer', 1)->findOrFail($id);
        $orders = Order::where('user_id', $id)
            ->where('is_split_payment', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('backend.bulk_buyer.show', compact('bulk_buyer', 'orders'));
    }

    public function markCodPaid($order_id)
    {
        $order = Order::findOrFail($order_id);
        $order->cod_payment_status = 'paid';
        $order->cod_paid_at = now();
        $order->save();
        
        $order->user->updateBulkBuyerTotals();
        
        flash(translate('COD marked as paid successfully'))->success();
        return back();
    }
}


