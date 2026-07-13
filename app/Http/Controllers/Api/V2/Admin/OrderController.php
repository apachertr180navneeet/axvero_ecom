<?php

namespace App\Http\Controllers\Api\V2\Admin;

use App\Http\Controllers\Api\V2\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class OrderController extends Controller
{
    protected function adminUser(Request $request): User
    {
        $bearer = $request->bearerToken();
        if (!$bearer) {
            abort(401, json_encode(['result' => false, 'message' => translate('Unauthenticated')]));
        }
        $user = PersonalAccessToken::findToken($bearer)?->tokenable;
        if (!$user || !($user instanceof User) || $user->user_type !== 'admin') {
            abort(403, json_encode(['result' => false, 'message' => translate('Forbidden')]));
        }
        return $user;
    }

    public function index(Request $request)
    {
        $this->adminUser($request);
        $query = Order::with('orderDetails', 'user');

        if ($request->delivery_status) {
            $query->where('delivery_status', $request->delivery_status);
        }

        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->type === 'inhouse') {
            $adminId = User::where('user_type', 'admin')->first()->id;
            $query->where('seller_id', $adminId);
        } elseif ($request->type === 'seller') {
            $query->where('seller_id', '!=', User::where('user_type', 'admin')->first()->id);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('code', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($u) use ($request) {
                      $u->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate($request->per_page ?? 20);

        return response()->json(['result' => true, 'data' => $orders]);
    }

    public function show(Request $request, $id)
    {
        $this->adminUser($request);
        $order = Order::with('orderDetails.product', 'user', 'address', 'shippingAddress', 'orderUpdates')->find($id);
        if (!$order) {
            return response()->json(['result' => false, 'message' => translate('Order not found')], 404);
        }

        return response()->json(['result' => true, 'data' => $order]);
    }

    public function updateDeliveryStatus(Request $request, $id)
    {
        $this->adminUser($request);
        $request->validate(['delivery_status' => 'required|string']);

        $order = Order::find($id);
        if (!$order) {
            return response()->json(['result' => false, 'message' => translate('Order not found')], 404);
        }
        $order->delivery_status = $request->delivery_status;
        $order->save();

        return response()->json(['result' => true, 'message' => translate('Delivery status updated successfully')]);
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $this->adminUser($request);
        $request->validate(['payment_status' => 'required|string']);

        $order = Order::find($id);
        if (!$order) {
            return response()->json(['result' => false, 'message' => translate('Order not found')], 404);
        }
        $order->payment_status = $request->payment_status;
        $order->save();

        return response()->json(['result' => true, 'message' => translate('Payment status updated successfully')]);
    }

    public function destroy(Request $request, $id)
    {
        $this->adminUser($request);
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['result' => false, 'message' => translate('Order not found')], 404);
        }
        $order->delete();

        return response()->json(['result' => true, 'message' => translate('Order deleted successfully')]);
    }
}
