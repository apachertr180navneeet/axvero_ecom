<?php

namespace App\Http\Controllers\Api\V2\Admin;

use App\Http\Controllers\Api\V2\Controller;
use App\Models\Order;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class SellerController extends Controller
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
        $sellers = User::where('user_type', 'seller')
            ->with('shop')
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json(['result' => true, 'data' => $sellers]);
    }

    public function show(Request $request, $id)
    {
        $this->adminUser($request);
        $seller = User::where('user_type', 'seller')->with('shop')->find($id);
        if (!$seller) {
            return response()->json(['result' => false, 'message' => translate('Seller not found')], 404);
        }

        $total_sales = Order::where('seller_id', $id)->where('delivery_status', 'delivered')->sum('grand_total');
        $total_orders = Order::where('seller_id', $id)->count();

        return response()->json([
            'result' => true,
            'data' => [
                'seller' => $seller,
                'total_sales' => (float) $total_sales,
                'total_orders' => (int) $total_orders,
            ],
        ]);
    }

    public function approve(Request $request, $id)
    {
        $this->adminUser($request);
        $seller = User::where('user_type', 'seller')->find($id);
        if (!$seller) {
            return response()->json(['result' => false, 'message' => translate('Seller not found')], 404);
        }
        $seller->approved = $request->approved ?? 1;
        $seller->save();

        return response()->json(['result' => true, 'message' => translate('Seller approval updated successfully')]);
    }

    public function ban(Request $request, $id)
    {
        $this->adminUser($request);
        $seller = User::where('user_type', 'seller')->find($id);
        if (!$seller) {
            return response()->json(['result' => false, 'message' => translate('Seller not found')], 404);
        }
        $seller->banned = $request->banned ?? 1;
        $seller->save();

        return response()->json(['result' => true, 'message' => translate('Seller ban status updated successfully')]);
    }

    public function destroy(Request $request, $id)
    {
        $this->adminUser($request);
        $seller = User::where('user_type', 'seller')->find($id);
        if (!$seller) {
            return response()->json(['result' => false, 'message' => translate('Seller not found')], 404);
        }
        $seller->delete();

        return response()->json(['result' => true, 'message' => translate('Seller deleted successfully')]);
    }

    public function pendingRegistrations(Request $request)
    {
        $this->adminUser($request);
        $sellers = User::where('user_type', 'seller')
            ->whereDoesntHave('shop', function ($q) {
                $q->where('verification_status', 1);
            })
            ->with('shop')
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json(['result' => true, 'data' => $sellers]);
    }
}
