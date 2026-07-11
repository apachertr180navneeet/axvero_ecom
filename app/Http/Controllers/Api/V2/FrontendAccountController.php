<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\Address;
use App\Models\Cart;
use App\Models\FollowSeller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Upload;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Wishlist;
use App\Utility\CartUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class FrontendAccountController extends Controller
{
    protected function userFromBearer(Request $request): ?User
    {
        $bearer = $request->bearerToken();
        if (!$bearer) {
            return null;
        }
        $tokenable = PersonalAccessToken::findToken($bearer)?->tokenable;
        return $tokenable instanceof User ? $tokenable : null;
    }

    protected function authenticatedUser(Request $request): User
    {
        $user = $this->userFromBearer($request);
        if (!$user) {
            abort(401, json_encode(['result' => false, 'message' => translate('Unauthenticated')]));
        }
        return $user;
    }

    // ──────────────────────────────────────────────
    //  PROFILE
    // ──────────────────────────────────────────────

    /**
     * GET /api/v2/account/profile
     */
    public function profile(Request $request)
    {
        $user = $this->authenticatedUser($request);

        return response()->json([
            'result' => true,
            'data' => [
                'id' => $user->id,
                'user_type' => $user->user_type,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => uploaded_asset($user->avatar_original),
                'balance' => single_price($user->balance),
                'email_verified' => $user->email_verified_at !== null,
                'phone_verified' => $user->phone_verified_at !== null,
            ],
        ]);
    }

    /**
     * PUT /api/v2/account/profile
     */
    public function updateProfile(Request $request)
    {
        $user = $this->authenticatedUser($request);

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()->all(),
            ]);
        }

        if ($request->filled('name')) {
            $user->name = $request->name;
        }
        if ($request->filled('phone')) {
            $user->phone = $request->phone;
        }
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return response()->json([
            'result' => true,
            'message' => translate('Profile information updated'),
        ]);
    }

    /**
     * POST /api/v2/account/profile/image
     */
    public function updateProfileImage(Request $request)
    {
        $user = $this->authenticatedUser($request);

        $validator = Validator::make($request->all(), [
            'image' => 'required|string',
            'filename' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => false, 'message' => $validator->errors()->all()]);
        }

        $typeMap = [
            'jpg' => 'image', 'jpeg' => 'image', 'png' => 'image',
            'svg' => 'image', 'webp' => 'image', 'gif' => 'image',
        ];

        try {
            $image = base64_decode($request->image);
            $filename = $request->filename ?? 'avatar.jpg';

            $dir = public_path('uploads/all');
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
            $fullPath = "$dir/$filename";

            if (file_put_contents($fullPath, $image) === false) {
                return response()->json(['result' => false, 'message' => 'File uploading error', 'path' => '']);
            }

            $extension = strtolower(File::extension($fullPath));
            if (!isset($typeMap[$extension])) {
                unlink($fullPath);
                return response()->json(['result' => false, 'message' => 'Only image can be uploaded', 'path' => '']);
            }

            unlink($fullPath);
            $newFileName = rand(10000000000, 9999999999) . date('YmdHis') . '.' . $extension;
            $newFullPath = "$dir/$newFileName";

            if (file_put_contents($newFullPath, $image) === false) {
                return response()->json(['result' => false, 'message' => 'Uploading error', 'path' => '']);
            }

            $newPath = "uploads/all/$newFileName";

            if (env('FILESYSTEM_DRIVER') == 's3') {
                Storage::disk('s3')->put($newPath, file_get_contents(base_path('public/') . $newPath), ['visibility' => 'public']);
                unlink(base_path('public/') . $newPath);
            }

            $upload = new Upload();
            $upload->file_original_name = pathinfo($filename, PATHINFO_FILENAME);
            $upload->extension = $extension;
            $upload->file_name = $newPath;
            $upload->user_id = $user->id;
            $upload->type = $typeMap[$extension];
            $upload->file_size = File::size($newFullPath);
            $upload->save();

            $user->avatar_original = $upload->id;
            $user->save();

            return response()->json([
                'result' => true,
                'message' => translate('Image updated'),
                'path' => uploaded_asset($upload->id),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'result' => false,
                'message' => $e->getMessage(),
                'path' => '',
            ]);
        }
    }

    /**
     * POST /api/v2/account/delete
     */
    public function deleteAccount(Request $request)
    {
        $user = $this->authenticatedUser($request);

        Cart::where('user_id', $user->id)->delete();
        $user->tokens()->delete();
        $user->customer_products()->delete();
        $user->delete();

        return response()->json([
            'result' => true,
            'message' => translate('Your account deletion successfully done'),
        ]);
    }

    /**
     * GET /api/v2/account/counters
     */
    public function counters(Request $request)
    {
        $user = $this->authenticatedUser($request);

        return response()->json([
            'cart_item_count' => Cart::where('user_id', $user->id)->count(),
            'wishlist_item_count' => Wishlist::where('user_id', $user->id)->count(),
            'order_count' => Order::where('user_id', $user->id)->count(),
        ]);
    }

    // ──────────────────────────────────────────────
    //  ORDERS
    // ──────────────────────────────────────────────

    /**
     * GET /api/v2/account/orders
     */
    public function orders(Request $request)
    {
        $user = $this->authenticatedUser($request);

        $orderQuery = Order::where('user_id', $user->id);

        if ($request->payment_status) {
            $orderQuery->where('payment_status', $request->payment_status);
        }
        if ($request->delivery_status) {
            $orderQuery->whereIn('id', function ($q) use ($request) {
                $q->select('order_id')->from('order_details')
                    ->where('delivery_status', $request->delivery_status);
            });
        }

        $orders = $orderQuery->latest()->paginate($request->per_page ?? 10);

        $data = collect($orders->items())->map(function ($order) {
            $items = $order->orderDetails->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'product_id' => $detail->product_id,
                    'product_name' => $detail->product?->getTranslation('name'),
                    'product_thumbnail' => uploaded_asset($detail->product?->thumbnail_img),
                    'variation' => $detail->variation,
                    'quantity' => $detail->quantity,
                    'price' => single_price($detail->price),
                    'delivery_status' => $detail->delivery_status,
                    'payment_status' => $detail->payment_status,
                ];
            });

            return [
                'id' => $order->id,
                'code' => $order->code,
                'date' => $order->date,
                'grand_total' => single_price($order->grand_total),
                'payment_type' => $order->payment_type,
                'payment_status' => $order->payment_status,
                'delivery_status' => $order->delivery_status,
                'items' => $items,
            ];
        });

        return response()->json([
            'result' => true,
            'data' => $data,
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ],
        ]);
    }

    /**
     * GET /api/v2/account/orders/{id}
     */
    public function orderDetails($id, Request $request)
    {
        $user = $this->authenticatedUser($request);

        $order = Order::where('id', $id)->where('user_id', $user->id)->first();
        if (!$order) {
            return response()->json(['result' => false, 'message' => translate('Order not found')]);
        }

        $items = $order->orderDetails->map(function ($detail) {
            return [
                'id' => $detail->id,
                'product_id' => $detail->product_id,
                'product_name' => $detail->product?->getTranslation('name'),
                'product_thumbnail' => uploaded_asset($detail->product?->thumbnail_img),
                'variation' => $detail->variation,
                'quantity' => $detail->quantity,
                'price' => single_price($detail->price),
                'tax' => single_price($detail->tax),
                'shipping_cost' => single_price($detail->shipping_cost),
                'delivery_status' => $detail->delivery_status,
                'payment_status' => $detail->payment_status,
            ];
        });

        $shippingAddress = json_decode($order->shipping_address, true);

        return response()->json([
            'result' => true,
            'data' => [
                'id' => $order->id,
                'code' => $order->code,
                'date' => $order->date,
                'grand_total' => single_price($order->grand_total),
                'coupon_discount' => single_price($order->coupon_discount ?? 0),
                'payment_type' => $order->payment_type,
                'payment_status' => $order->payment_status,
                'delivery_status' => $order->delivery_status,
                'shipping_address' => $shippingAddress,
                'items' => $items,
            ],
        ]);
    }

    /**
     * POST /api/v2/account/orders/{id}/cancel
     */
    public function cancelOrder($id, Request $request)
    {
        $user = $this->authenticatedUser($request);

        $order = Order::where('id', $id)->where('user_id', $user->id)->first();
        if (!$order) {
            return response()->json(['result' => false, 'message' => translate('Order not found')]);
        }
        if ($order->delivery_status != 'pending' || $order->payment_status != 'unpaid') {
            return response()->json(['result' => false, 'message' => translate('Order cannot be cancelled')]);
        }

        $order->delivery_status = 'cancelled';
        $order->save();

        foreach ($order->orderDetails as $orderDetail) {
            $orderDetail->delivery_status = 'cancelled';
            $orderDetail->save();
            product_restock($orderDetail);
        }

        return response()->json([
            'result' => true,
            'message' => translate('Order has been canceled successfully'),
        ]);
    }

    /**
     * POST /api/v2/account/orders/{id}/re-order
     */
    public function reOrder($id, Request $request)
    {
        $user = $this->authenticatedUser($request);
        $success_msgs = [];
        $failed_msgs = [];

        $carts = Cart::where('user_id', $user->id)->get();
        $check_auction_in_cart = CartUtility::check_auction_in_cart($carts);
        if ($check_auction_in_cart) {
            $failed_msgs[] = translate('Remove auction product from cart to add products.');
            return response()->json(compact('success_msgs', 'failed_msgs'));
        }

        $order = Order::findOrFail($id);

        foreach ($order->orderDetails as $orderDetail) {
            $product = $orderDetail->product;

            if (!$product || $product->published == 0 || $product->approved == 0 || $product->wholesale_product) {
                $failed_msgs[] = translate('An item from this order is not available now.');
                continue;
            }

            if ($product->auction_product == 1) {
                $failed_msgs[] = translate('You can not re order an auction product.');
                break;
            }

            $order_qty = $orderDetail->quantity;
            if ($product->digital == 0 && $order_qty < $product->min_qty) {
                $order_qty = $product->min_qty;
            }

            $cart = Cart::firstOrNew([
                'variation' => $orderDetail->variation,
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);

            $product_stock = $product->stocks->where('variant', $orderDetail->variation)->first();
            if (!$product_stock) {
                $failed_msgs[] = $product->getTranslation('name') . ' ' . translate('is stock out.');
                continue;
            }

            $quantity = 1;
            if ($product->digital != 1) {
                $quantity = $product_stock->qty;
                if ($quantity > 0) {
                    if ($cart->exists) {
                        $order_qty = $cart->quantity + $order_qty;
                    }
                    $quantity = ($quantity >= $order_qty) ? $order_qty : $quantity;
                } else {
                    $failed_msgs[] = $product->getTranslation('name') . ' ' . translate('is stock out.');
                    continue;
                }
            }

            $price = CartUtility::get_price($product, $product_stock, $quantity);
            $tax = CartUtility::tax_calculation($product, $price);

            CartUtility::save_cart_data($cart, $product, $price, $tax, $quantity);
            $success_msgs[] = $product->getTranslation('name') . ' ' . translate('added to cart.');
        }

        return response()->json(compact('success_msgs', 'failed_msgs'));
    }

    // ──────────────────────────────────────────────
    //  WISHLIST
    // ──────────────────────────────────────────────

    /**
     * GET /api/v2/account/wishlist
     */
    public function wishlist(Request $request)
    {
        $user = $this->authenticatedUser($request);

        $items = Wishlist::where('user_id', $user->id)
            ->with('product')
            ->latest()
            ->paginate($request->per_page ?? 10);

        $data = collect($items->items())->map(function ($w) {
            $product = $w->product;
            return [
                'id' => $w->id,
                'product_id' => $product?->id,
                'product_name' => $product?->getTranslation('name'),
                'product_slug' => $product?->slug,
                'product_thumbnail' => uploaded_asset($product?->thumbnail_img),
                'product_price' => single_price($product?->unit_price ?? 0),
                'product_stock' => $product?->stocks?->sum('qty') ?? 0,
                'product_digital' => $product?->digital == 1,
            ];
        });

        return response()->json([
            'result' => true,
            'data' => $data,
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    /**
     * POST /api/v2/account/wishlist/add
     */
    public function addToWishlist(Request $request)
    {
        $user = $this->authenticatedUser($request);

        $validator = Validator::make($request->all(), [
            'product_slug' => 'required|string|exists:products,slug',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => false, 'message' => $validator->errors()->all()]);
        }

        $product = Product::where('slug', $request->product_slug)->first();

        $existing = Wishlist::where('product_id', $product->id)->where('user_id', $user->id)->first();
        if ($existing) {
            return response()->json([
                'result' => true,
                'message' => translate('Product present in wishlist'),
                'is_in_wishlist' => true,
                'product_id' => $product->id,
                'product_slug' => $product->slug,
                'wishlist_id' => $existing->id,
            ]);
        }

        $wishlist = Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        return response()->json([
            'result' => true,
            'message' => translate('Product added to wishlist'),
            'is_in_wishlist' => true,
            'product_id' => $product->id,
            'product_slug' => $product->slug,
            'wishlist_id' => $wishlist->id,
        ]);
    }

    /**
     * DELETE /api/v2/account/wishlist/{id}
     */
    public function removeFromWishlist($id, Request $request)
    {
        $user = $this->authenticatedUser($request);

        $wishlist = Wishlist::where('id', $id)->where('user_id', $user->id)->first();
        if (!$wishlist) {
            return response()->json(['result' => false, 'message' => translate('Wishlist item not found')]);
        }

        $product = $wishlist->product;
        $wishlist->delete();

        return response()->json([
            'result' => true,
            'message' => translate('Product is removed from wishlist'),
            'is_in_wishlist' => false,
            'product_id' => $product?->id,
            'product_slug' => $product?->slug,
        ]);
    }

    /**
     * GET /api/v2/account/wishlist/check/{slug}
     */
    public function checkWishlist($slug, Request $request)
    {
        $user = $this->authenticatedUser($request);

        $product = Product::where('slug', $slug)->first();
        if (!$product) {
            return response()->json(['result' => false, 'message' => translate('Product not found')]);
        }

        $wishlist = Wishlist::where('product_id', $product->id)->where('user_id', $user->id)->first();

        return response()->json([
            'result' => true,
            'is_in_wishlist' => $wishlist !== null,
            'product_id' => $product->id,
            'wishlist_id' => $wishlist?->id,
            'message' => $wishlist
                ? translate('Product present in wishlist')
                : translate('Product is not present in wishlist'),
        ]);
    }

    // ──────────────────────────────────────────────
    //  WALLET
    // ──────────────────────────────────────────────

    /**
     * GET /api/v2/account/wallet
     */
    public function walletBalance(Request $request)
    {
        $user = $this->authenticatedUser($request);

        $latest = Wallet::where('user_id', $user->id)->latest()->first();

        return response()->json([
            'result' => true,
            'data' => [
                'balance' => single_price($user->balance),
                'last_recharged' => $latest ? $latest->created_at->diffForHumans() : 'Not Available',
            ],
        ]);
    }

    /**
     * GET /api/v2/account/wallet/history
     */
    public function walletHistory(Request $request)
    {
        $user = $this->authenticatedUser($request);

        $history = Wallet::where('user_id', $user->id)
            ->latest()
            ->paginate($request->per_page ?? 10);

        $data = collect($history->items())->map(function ($w) {
            return [
                'id' => $w->id,
                'amount' => single_price($w->amount),
                'payment_method' => $w->payment_method,
                'approval' => $w->approval == 1,
                'created_at' => $w->created_at->toDateTimeString(),
            ];
        });

        return response()->json([
            'result' => true,
            'data' => $data,
            'meta' => [
                'current_page' => $history->currentPage(),
                'last_page' => $history->lastPage(),
                'per_page' => $history->perPage(),
                'total' => $history->total(),
            ],
        ]);
    }

    // ──────────────────────────────────────────────
    //  NOTIFICATIONS
    // ──────────────────────────────────────────────

    /**
     * GET /api/v2/account/notifications
     */
    public function notifications(Request $request)
    {
        $user = $this->authenticatedUser($request);

        $user->unreadNotifications->markAsRead();
        $notifications = $user->notifications()->paginate($request->per_page ?? 20);

        $data = collect($notifications->items())->map(function ($n) {
            return [
                'id' => $n->id,
                'type' => $n->type,
                'data' => $n->data,
                'read_at' => $n->read_at,
                'created_at' => $n->created_at->toDateTimeString(),
            ];
        });

        return response()->json([
            'result' => true,
            'data' => $data,
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
            ],
        ]);
    }

    /**
     * GET /api/v2/account/notifications/unread
     */
    public function unreadNotifications(Request $request)
    {
        $user = $this->authenticatedUser($request);

        $unread = $user->unreadNotifications()->paginate($request->per_page ?? 20);

        $data = collect($unread->items())->map(function ($n) {
            return [
                'id' => $n->id,
                'type' => $n->type,
                'data' => $n->data,
                'created_at' => $n->created_at->toDateTimeString(),
            ];
        });

        return response()->json([
            'result' => true,
            'count' => $user->unreadNotifications()->count(),
            'data' => $data,
            'meta' => [
                'current_page' => $unread->currentPage(),
                'last_page' => $unread->lastPage(),
                'per_page' => $unread->perPage(),
                'total' => $unread->total(),
            ],
        ]);
    }

    /**
     * POST /api/v2/account/notifications/{id}/read
     */
    public function markNotificationRead($id, Request $request)
    {
        $user = $this->authenticatedUser($request);

        $notification = $user->unreadNotifications->where('id', $id)->first();
        if (!$notification) {
            return response()->json(['result' => false, 'message' => translate('Notification not found')]);
        }

        $notification->markAsRead();

        return response()->json([
            'result' => true,
            'type' => $notification->type,
            'data' => $notification->data,
        ]);
    }

    /**
     * POST /api/v2/account/notifications/bulk-delete
     */
    public function bulkDeleteNotifications(Request $request)
    {
        $user = $this->authenticatedUser($request);

        $validator = Validator::make($request->all(), [
            'notification_ids' => 'required|array',
            'notification_ids.*' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => false, 'message' => $validator->errors()->all()]);
        }

        DB::table('notifications')
            ->whereIn('id', $request->notification_ids)
            ->where('notifiable_id', $user->id)
            ->delete();

        return response()->json([
            'result' => true,
            'message' => translate('Notification deleted successfully'),
        ]);
    }

    // ──────────────────────────────────────────────
    //  FOLLOWED SELLERS
    // ──────────────────────────────────────────────

    /**
     * GET /api/v2/account/followed-sellers
     */
    public function followedSellers(Request $request)
    {
        $user = $this->authenticatedUser($request);

        $followed = FollowSeller::with('shop')
            ->where('user_id', $user->id)
            ->orderBy('shop_id', 'asc')
            ->paginate($request->per_page ?? 10);

        $data = collect($followed->items())->map(function ($f) {
            return [
                'id' => $f->id,
                'shop_id' => $f->shop_id,
                'shop_name' => $f->shop?->name,
                'shop_logo' => uploaded_asset($f->shop?->logo),
                'shop_rating' => $f->shop?->rating,
            ];
        });

        return response()->json([
            'result' => true,
            'data' => $data,
            'meta' => [
                'current_page' => $followed->currentPage(),
                'last_page' => $followed->lastPage(),
                'per_page' => $followed->perPage(),
                'total' => $followed->total(),
            ],
        ]);
    }

    /**
     * POST /api/v2/account/followed-sellers/{shopId}
     */
    public function followSeller($shopId, Request $request)
    {
        $user = $this->authenticatedUser($request);

        if ($user->user_type != 'customer') {
            return response()->json(['result' => false, 'message' => translate('You need to login as a customer to follow this seller')]);
        }

        $existing = FollowSeller::where('user_id', $user->id)->where('shop_id', $shopId)->first();
        if (!$existing) {
            FollowSeller::insert([
                'user_id' => $user->id,
                'shop_id' => $shopId,
            ]);
        }

        return response()->json(['result' => true, 'message' => translate('Seller follow is successfull')]);
    }

    /**
     * DELETE /api/v2/account/followed-sellers/{shopId}
     */
    public function unfollowSeller($shopId, Request $request)
    {
        $user = $this->authenticatedUser($request);

        FollowSeller::where('user_id', $user->id)->where('shop_id', $shopId)->delete();

        return response()->json(['result' => true, 'message' => translate('Seller unfollow is successfull')]);
    }

    /**
     * GET /api/v2/account/followed-sellers/check/{shopId}
     */
    public function checkFollowSeller($shopId, Request $request)
    {
        $user = $this->authenticatedUser($request);

        $followed = FollowSeller::where('user_id', $user->id)->where('shop_id', $shopId)->exists();

        return response()->json([
            'result' => true,
            'is_following' => $followed,
        ]);
    }

    // ──────────────────────────────────────────────
    //  ADDRESSES
    // ──────────────────────────────────────────────

    /**
     * GET /api/v2/account/addresses
     */
    public function addresses(Request $request)
    {
        $user = $this->authenticatedUser($request);

        $addresses = Address::where('user_id', $user->id)->get();

        return response()->json([
            'result' => true,
            'data' => $addresses->map(function ($a) {
                return [
                    'id' => $a->id,
                    'address' => $a->address,
                    'country_id' => $a->country_id,
                    'country_name' => $a->country?->name,
                    'state_id' => $a->state_id,
                    'state_name' => $a->state?->name,
                    'city_id' => $a->city_id,
                    'city_name' => $a->city?->name,
                    'area_id' => $a->area_id,
                    'area_name' => $a->area?->name,
                    'postal_code' => $a->postal_code,
                    'phone' => $a->phone,
                    'set_default' => $a->set_default == 1,
                    'latitude' => $a->latitude,
                    'longitude' => $a->longitude,
                ];
            }),
        ]);
    }

    /**
     * POST /api/v2/account/addresses
     */
    public function createAddress(Request $request)
    {
        $user = $this->authenticatedUser($request);

        $validator = Validator::make($request->all(), [
            'address' => 'required|string',
            'country_id' => 'required|integer|exists:countries,id',
            'state_id' => 'required|integer|exists:states,id',
            'city_id' => 'required|integer|exists:cities,id',
            'postal_code' => 'nullable|string',
            'phone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => false, 'message' => $validator->errors()->all()]);
        }

        $address = new Address();
        $address->user_id = $user->id;
        $address->address = $request->address;
        $address->country_id = $request->country_id;
        $address->state_id = $request->state_id;
        $address->city_id = $request->city_id;
        $address->area_id = $request->area_id;
        $address->postal_code = $request->postal_code;
        $address->phone = $request->phone;
        $address->save();

        return response()->json([
            'result' => true,
            'message' => translate('Shipping information has been added successfully'),
            'data' => ['id' => $address->id],
        ]);
    }

    /**
     * PUT /api/v2/account/addresses/{id}
     */
    public function updateAddress($id, Request $request)
    {
        $user = $this->authenticatedUser($request);

        $validator = Validator::make($request->all(), [
            'address' => 'required|string',
            'country_id' => 'required|integer|exists:countries,id',
            'state_id' => 'required|integer|exists:states,id',
            'city_id' => 'required|integer|exists:cities,id',
            'postal_code' => 'nullable|string',
            'phone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => false, 'message' => $validator->errors()->all()]);
        }

        $address = Address::where('id', $id)->where('user_id', $user->id)->first();
        if (!$address) {
            return response()->json(['result' => false, 'message' => translate('Address not found')]);
        }

        $address->address = $request->address;
        $address->country_id = $request->country_id;
        $address->state_id = $request->state_id;
        $address->city_id = $request->city_id;
        $address->area_id = $request->area_id;
        $address->postal_code = $request->postal_code;
        $address->phone = $request->phone;
        $address->save();

        return response()->json([
            'result' => true,
            'message' => translate('Shipping information has been updated successfully'),
        ]);
    }

    /**
     * DELETE /api/v2/account/addresses/{id}
     */
    public function deleteAddress($id, Request $request)
    {
        $user = $this->authenticatedUser($request);

        $address = Address::where('id', $id)->where('user_id', $user->id)->first();
        if (!$address) {
            return response()->json(['result' => false, 'message' => translate('Address not found')]);
        }

        $address->delete();

        return response()->json(['result' => true, 'message' => translate('Shipping information has been deleted')]);
    }

    /**
     * POST /api/v2/account/addresses/{id}/default
     */
    public function setDefaultAddress($id, Request $request)
    {
        $user = $this->authenticatedUser($request);

        $address = Address::where('id', $id)->where('user_id', $user->id)->first();
        if (!$address) {
            return response()->json(['result' => false, 'message' => translate('Address not found')]);
        }

        Address::where('user_id', $user->id)->update(['set_default' => 0]);
        $address->set_default = 1;
        $address->save();

        return response()->json([
            'result' => true,
            'message' => translate('Default shipping information has been updated'),
        ]);
    }

    // ──────────────────────────────────────────────
    //  CHECK PHONE & EMAIL AVAILABILITY
    // ──────────────────────────────────────────────

    /**
     * GET /api/v2/account/check-contact
     */
    public function checkPhoneAndEmail(Request $request)
    {
        $user = $this->authenticatedUser($request);

        return response()->json([
            'phone_available' => !empty($user->phone),
            'email_available' => !empty($user->email),
            'phone_available_message' => $user->phone
                ? translate('User phone number found')
                : translate('User phone number not found'),
            'email_available_message' => $user->email
                ? translate('User email found')
                : translate('User email not found'),
        ]);
    }
}
