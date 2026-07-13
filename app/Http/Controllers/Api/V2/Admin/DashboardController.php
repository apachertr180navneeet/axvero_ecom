<?php

namespace App\Http\Controllers\Api\V2\Admin;

use App\Http\Controllers\Api\V2\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

class DashboardController extends Controller
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

    public function stats(Request $request)
    {
        $this->adminUser($request);
        $adminId = User::where('user_type', 'admin')->first()->id;

        return response()->json([
            'result' => true,
            'data' => [
                'total_customers' => (int) User::where('user_type', 'customer')->where('email_verified_at', '!=', null)->count(),
                'total_products' => (int) Product::where('approved', 1)->where('published', 1)->count(),
                'total_inhouse_products' => (int) Product::where('approved', 1)->where('published', 1)->where('added_by', 'admin')->count(),
                'total_sellers_products' => (int) Product::where('approved', 1)->where('published', 1)->where('added_by', '!=', 'admin')->count(),
                'total_categories' => (int) Category::count(),
                'total_brands' => (int) Brand::count(),
                'total_sellers' => (int) User::where('user_type', 'seller')->where('email_verified_at', '!=', null)->count(),
                'total_order' => (int) Order::count(),
                'total_placed_order' => (int) Order::where('delivery_status', '!=', 'cancelled')->count(),
                'total_pending_order' => (int) Order::where('delivery_status', 'pending')->count(),
                'total_confirmed_order' => (int) Order::where('delivery_status', 'confirmed')->count(),
                'total_picked_up_order' => (int) Order::where('delivery_status', 'picked_up')->count(),
                'total_shipped_order' => (int) Order::where('delivery_status', 'on_the_way')->count(),
                'total_sale' => (float) Order::where('delivery_status', 'delivered')->sum('grand_total'),
                'sale_this_month' => (float) Order::whereYear('created_at', Carbon::now()->year)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->sum('grand_total'),
                'total_inhouse_sale' => (float) Order::where('seller_id', $adminId)->sum('grand_total'),
                'total_inhouse_order' => (int) Order::where('seller_id', $adminId)->count(),
            ],
        ]);
    }

    public function topCustomers(Request $request)
    {
        $this->adminUser($request);
        $customers = User::select('users.id', 'users.name', 'users.avatar_original', DB::raw('SUM(grand_total) as total'))
            ->join('orders', 'orders.user_id', '=', 'users.id')
            ->groupBy('orders.user_id')
            ->where('users.user_type', 'customer')
            ->orderBy('total', 'desc')
            ->limit(6)
            ->get()->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'avatar' => uploaded_asset($u->avatar_original),
                    'total_spent' => (float) $u->total,
                ];
            });

        return response()->json(['result' => true, 'data' => $customers]);
    }

    public function topSellers(Request $request)
    {
        $this->adminUser($request);
        $sellers = Order::select('orders.seller_id', 'users.name', 'users.user_type', 'users.avatar_original', DB::raw('SUM(grand_total) as total'))
            ->leftJoin('users', 'orders.seller_id', '=', 'users.id')
            ->whereRaw('users.user_type = "seller"')
            ->groupBy('users.id')
            ->orderBy('total', 'desc')
            ->limit(6)
            ->get()->map(function ($u) {
                return [
                    'id' => $u->seller_id,
                    'name' => $u->name,
                    'avatar' => uploaded_asset($u->avatar_original),
                    'total_sales' => (float) $u->total,
                ];
            });

        return response()->json(['result' => true, 'data' => $sellers]);
    }

    public function topCategories(Request $request)
    {
        $this->adminUser($request);
        $categories = Product::select('categories.name', 'categories.id', DB::raw('SUM(grand_total) as total'))
            ->leftJoin('order_details', 'order_details.product_id', '=', 'products.id')
            ->leftJoin('orders', 'orders.id', '=', 'order_details.order_id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.delivery_status', 'delivered')
            ->groupBy('categories.id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get()->map(function ($c) {
                return [
                    'id' => $c->id,
                    'name' => $c->name,
                    'total_sales' => (float) $c->total,
                ];
            });

        return response()->json(['result' => true, 'data' => $categories]);
    }

    public function topBrands(Request $request)
    {
        $this->adminUser($request);
        $brands = Product::select('brands.name', 'brands.id', 'brands.logo', DB::raw('SUM(grand_total) as total'))
            ->leftJoin('order_details', 'order_details.product_id', '=', 'products.id')
            ->leftJoin('orders', 'orders.id', '=', 'order_details.order_id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->where('orders.delivery_status', 'delivered')
            ->groupBy('brands.id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get()->map(function ($b) {
                return [
                    'id' => $b->id,
                    'name' => $b->name,
                    'logo' => uploaded_asset($b->logo),
                    'total_sales' => (float) $b->total,
                ];
            });

        return response()->json(['result' => true, 'data' => $brands]);
    }

    public function salesChart(Request $request)
    {
        $this->adminUser($request);
        $sales = Order::select(DB::raw('SUM(grand_total) as total'), DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'))
            ->where('delivery_status', 'delivered')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return response()->json(['result' => true, 'data' => $sales]);
    }

    public function orderStats(Request $request)
    {
        $this->adminUser($request);
        $statuses = ['pending', 'confirmed', 'picked_up', 'on_the_way', 'delivered', 'cancelled'];
        $stats = [];
        foreach ($statuses as $status) {
            $stats[$status] = (int) Order::where('delivery_status', $status)->count();
        }

        return response()->json(['result' => true, 'data' => $stats]);
    }
}
