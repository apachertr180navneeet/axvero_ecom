<?php

namespace App\Http\Controllers\Api\V2\Admin;

use App\Http\Controllers\Api\V2\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class CustomerController extends Controller
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
        $customers = User::where('user_type', 'customer')
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json(['result' => true, 'data' => $customers]);
    }

    public function show(Request $request, $id)
    {
        $this->adminUser($request);
        $customer = User::where('user_type', 'customer')->find($id);
        if (!$customer) {
            return response()->json(['result' => false, 'message' => translate('Customer not found')], 404);
        }

        $total_orders = Order::where('user_id', $id)->count();
        $total_spent = Order::where('user_id', $id)->where('delivery_status', 'delivered')->sum('grand_total');

        return response()->json([
            'result' => true,
            'data' => [
                'customer' => $customer,
                'total_orders' => (int) $total_orders,
                'total_spent' => (float) $total_spent,
            ],
        ]);
    }

    public function ban(Request $request, $id)
    {
        $this->adminUser($request);
        $customer = User::where('user_type', 'customer')->find($id);
        if (!$customer) {
            return response()->json(['result' => false, 'message' => translate('Customer not found')], 404);
        }
        $customer->banned = $request->banned ?? 1;
        $customer->save();

        return response()->json(['result' => true, 'message' => translate('Customer ban status updated successfully')]);
    }

    public function destroy(Request $request, $id)
    {
        $this->adminUser($request);
        $customer = User::where('user_type', 'customer')->find($id);
        if (!$customer) {
            return response()->json(['result' => false, 'message' => translate('Customer not found')], 404);
        }
        $customer->delete();

        return response()->json(['result' => true, 'message' => translate('Customer deleted successfully')]);
    }
}
