<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\Address;
use App\Models\Cart;
use App\Models\CombinedOrder;
use App\Models\Country;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PickupPoint;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class FrontendCheckoutController extends Controller
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

    /**
     * GET /api/v2/checkout/addresses
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
     * POST /api/v2/checkout/addresses
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
            return response()->json([
                'result' => false,
                'message' => $validator->errors()->all(),
            ]);
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
     * POST /api/v2/checkout/addresses/update
     */
    public function updateAddress(Request $request)
    {
        $user = $this->authenticatedUser($request);

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:addresses,id',
            'address' => 'required|string',
            'country_id' => 'required|integer|exists:countries,id',
            'state_id' => 'required|integer|exists:states,id',
            'city_id' => 'required|integer|exists:cities,id',
            'postal_code' => 'nullable|string',
            'phone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()->all(),
            ]);
        }

        $address = Address::where('id', $request->id)->where('user_id', $user->id)->first();
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
     * DELETE /api/v2/checkout/addresses/{id}
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
     * POST /api/v2/checkout/addresses/{id}/default
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

    /**
     * POST /api/v2/checkout/set-address
     * Set address_id on cart items.
     */
    public function setAddressInCart(Request $request)
    {
        $user = $this->authenticatedUser($request);

        $validator = Validator::make($request->all(), [
            'address_id' => 'required|integer|exists:addresses,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()->all(),
            ]);
        }

        $address = Address::where('id', $request->address_id)->where('user_id', $user->id)->first();
        if (!$address) {
            return response()->json(['result' => false, 'message' => translate('Address not found')]);
        }

        Cart::where('user_id', $user->id)->active()->update(['address_id' => $request->address_id]);

        return response()->json([
            'result' => true,
            'message' => translate('Address is saved'),
            'data' => ['address_id' => (int) $request->address_id],
        ]);
    }

    /**
     * GET /api/v2/checkout/delivery-info
     * Returns shops with carriers & pickup points.
     */
    public function deliveryInfo(Request $request)
    {
        $user = $this->authenticatedUser($request);

        $cartItems = Cart::where('user_id', $user->id)->active()->get();
        if ($cartItems->isEmpty()) {
            return response()->json(['result' => false, 'message' => translate('Cart is empty')]);
        }

        $ownerIds = $cartItems->pluck('owner_id')->unique()->values()->toArray();
        $shippingInfo = [];

        $firstCart = $cartItems->first();
        if ($firstCart && $firstCart->address_id) {
            $address = Address::find($firstCart->address_id);
            if ($address) {
                $shippingInfo['country_id'] = $address->country_id;
                $shippingInfo['city_id'] = $address->city_id;
                $shippingInfo['area_id'] = $address->area_id;
            }
        }

        $shops = [];
        foreach ($ownerIds as $ownerId) {
            $shopItems = $cartItems->where('owner_id', $ownerId)->values();
            $shopData = Shop::where('user_id', $ownerId)->first();

            $carriers = [];
            $zone = isset($shippingInfo['country_id'])
                ? Country::where('id', $shippingInfo['country_id'])->first()?->zone_id
                : null;

            $carrierQuery = \App\Models\Carrier::query();
            $carrierQuery->whereIn('id', function ($query) use ($zone) {
                $query->select('carrier_id')->from('carrier_range_prices')
                    ->where('zone_id', $zone);
            })->orWhere('free_shipping', 1);
            $carrierList = $carrierQuery->active()->get();

            foreach ($carrierList as $carrier) {
                $carriers[] = [
                    'id' => $carrier->id,
                    'name' => $carrier->name,
                    'logo' => uploaded_asset($carrier->logo),
                    'transit_time' => (int) $carrier->transit_time,
                    'free_shipping' => $carrier->free_shipping == 1,
                    'transit_price' => carrier_base_price($cartItems, $carrier->id, $ownerId, $shippingInfo),
                ];
            }

            $pickupPoints = [];
            if (get_setting('pickup_point') == 1) {
                $pickupPointList = PickupPoint::where('pick_up_status', 1)->get();
                foreach ($pickupPointList as $pp) {
                    $pickupPoints[] = [
                        'id' => $pp->id,
                        'name' => $pp->name,
                        'address' => $pp->address,
                        'phone' => $pp->phone,
                    ];
                }
            }

            $shops[] = [
                'name' => $shopData ? $shopData->name : 'Inhouse',
                'owner_id' => (int) $ownerId,
                'carriers' => $carriers,
                'pickup_points' => $pickupPoints,
            ];
        }

        return response()->json([
            'result' => true,
            'data' => $shops,
        ]);
    }

    /**
     * POST /api/v2/checkout/set-shipping
     * Set shipping type per seller.
     */
    public function setShipping(Request $request)
    {
        $user = $this->authenticatedUser($request);

        $validator = Validator::make($request->all(), [
            'owner_id' => 'required|integer',
            'shipping_type' => 'required|in:home_delivery,pickup_point,carrier',
            'shipping_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()->all(),
            ]);
        }

        $carts = Cart::where('user_id', $user->id)->where('owner_id', $request->owner_id)->active()->get();
        if ($carts->isEmpty()) {
            return response()->json(['result' => false, 'message' => translate('No cart items for this seller')]);
        }

        foreach ($carts as $cart) {
            $cart->shipping_cost = 0;
            if ($request->shipping_type == 'pickup_point') {
                $cart->shipping_type = 'pickup_point';
                $cart->pickup_point = $request->shipping_id;
                $cart->carrier_id = 0;
            } elseif ($request->shipping_type == 'home_delivery') {
                $cart->shipping_type = 'home_delivery';
                $cart->pickup_point = 0;
                $cart->carrier_id = 0;
                // shipping cost calculated globally via shipping_cost endpoint
            } elseif ($request->shipping_type == 'carrier') {
                $cart->shipping_type = 'carrier';
                $cart->carrier_id = $request->shipping_id;
                $cart->pickup_point = 0;
            }
            $cart->save();
        }

        return response()->json([
            'result' => true,
            'message' => translate('Delivery type is saved'),
        ]);
    }

    /**
     * POST /api/v2/checkout/shipping-cost
     * Calculate & set shipping cost for all sellers.
     */
    public function shippingCost(Request $request)
    {
        $user = $this->authenticatedUser($request);

        $carts = Cart::where('user_id', $user->id)->active()->get();
        if ($carts->isEmpty()) {
            return response()->json(['result' => false, 'message' => translate('Cart is empty')]);
        }

        $firstCart = $carts->first();
        $shippingInfo = [];
        if ($firstCart && $firstCart->address_id) {
            $address = Address::find($firstCart->address_id);
            if ($address) {
                $shippingInfo['country_id'] = $address->country_id;
                $shippingInfo['city_id'] = $address->city_id;
                $shippingInfo['area_id'] = $address->area_id;
            }
        }

        $totalShipping = 0;
        foreach ($carts as $key => $cart) {
            $cart->shipping_cost = 0;
            if ($cart->shipping_type == 'home_delivery') {
                $cart->shipping_cost = getShippingCost($carts, $key, $shippingInfo);
            } elseif ($cart->shipping_type == 'carrier' && $cart->carrier_id) {
                $cart->shipping_cost = getShippingCost($carts, $key, $shippingInfo, $cart->carrier_id);
            }
            $cart->save();
            $totalShipping += $cart->shipping_cost;
        }

        return response()->json([
            'result' => true,
            'shipping_cost' => convert_price($totalShipping),
            'shipping_cost_string' => format_price(convert_price($totalShipping)),
        ]);
    }

    /**
     * GET /api/v2/checkout/payment-types
     */
    public function paymentTypes(Request $request)
    {
        try {
            return app(PaymentTypesController::class)->getList($request);
        } catch (\Exception $e) {
            return response()->json([
                'result' => true,
                'payment_types' => [
                    [
                        'payment_type' => 'cash_payment',
                        'payment_type_key' => 'cash_on_delivery',
                        'name' => 'Cash Payment',
                        'title' => translate('Cash on delivery'),
                    ],
                    [
                        'payment_type' => 'online_payment',
                        'payment_type_key' => 'online',
                        'name' => 'Online Payment',
                        'title' => translate('Online payment'),
                    ],
                    [
                        'payment_type' => 'wallet_payment',
                        'payment_type_key' => 'wallet',
                        'name' => 'Wallet Payment',
                        'title' => translate('Wallet payment'),
                    ],
                ],
            ]);
        }
    }

    /**
     * POST /api/v2/checkout/place-order
     * Place order (COD / wallet / manual / online).
     */
    public function placeOrder(Request $request)
    {
        $user = $this->authenticatedUser($request);

        $validator = Validator::make($request->all(), [
            'payment_type' => 'required|string|in:cash_on_delivery,wallet,manual_payment,online',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()->all(),
            ]);
        }

        $cartItems = Cart::where('user_id', $user->id)->active()->get();
        if ($cartItems->isEmpty()) {
            return response()->json([
                'combined_order_id' => 0,
                'result' => false,
                'message' => translate('Cart is Empty'),
            ]);
        }

        if (get_setting('minimum_order_amount_check') == 1) {
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $subtotal += cart_product_price($item, $product, false, false) * $item->quantity;
                }
            }
            if ($subtotal < get_setting('minimum_order_amount')) {
                return response()->json([
                    'result' => false,
                    'message' => translate('You order amount is less then the minimum order amount'),
                ]);
            }
        }

        $address = Address::where('id', $cartItems->first()->address_id)->first();
        $shippingAddress = [];
        if ($address) {
            $shippingAddress['name'] = $user->name;
            $shippingAddress['email'] = $user->email;
            $shippingAddress['address'] = $address->address . (isset($address->area) ? ', ' . $address->area->name : '');
            $shippingAddress['country'] = $address->country?->name;
            if (get_setting('has_state') == 1) {
                $shippingAddress['state'] = $address->state?->name;
            }
            $shippingAddress['city'] = $address->city?->name;
            $shippingAddress['postal_code'] = $address->postal_code;
            $shippingAddress['phone'] = $address->phone;
            if ($address->latitude || $address->longitude) {
                $shippingAddress['lat_lang'] = $address->latitude . ',' . $address->longitude;
            }
        }

        $paymentType = $request->payment_type;
        if ($paymentType == 'wallet') {
            return app(WalletController::class)->processPayment($request);
        }

        $setPaid = false;
        if ($paymentType == 'cash_on_delivery') {
            $paymentType = 'cash_on_delivery';
        } elseif ($paymentType == 'manual_payment') {
            $paymentType = 'manual_payment_' . ($request->manual_payment_id ?? 1);
        } elseif ($paymentType == 'online') {
            // online payment: forward to OnlinePaymentController::init
            return response()->json([
                'result' => true,
                'redirect' => url('api/v2/online-pay/init') . '?payment_type=' . ($request->payment_method ?? 'paypal'),
                'message' => translate('Redirecting to payment gateway'),
            ]);
        }

        // Create combined order
        $combinedOrder = new CombinedOrder();
        $combinedOrder->user_id = $user->id;
        $combinedOrder->shipping_address = json_encode($shippingAddress);
        $combinedOrder->save();

        $sellerProducts = [];
        foreach ($cartItems as $cartItem) {
            $product = Product::find($cartItem->product_id);
            if (!$product) {
                continue;
            }
            $sellerProducts[$product->user_id][] = $cartItem;
        }

        foreach ($sellerProducts as $sellerId => $sellerCartItems) {
            $order = new Order();
            $order->combined_order_id = $combinedOrder->id;
            $order->user_id = $user->id;
            $order->shipping_address = $combinedOrder->shipping_address;
            $order->order_from = 'app';
            $order->payment_type = $paymentType;
            $order->delivery_viewed = '0';
            $order->payment_status_viewed = '0';
            $order->code = date('Ymd-His') . rand(10, 99);
            $order->date = strtotime('now');
            $order->payment_status = $setPaid ? 'paid' : 'unpaid';
            $order->save();

            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            $couponDiscount = 0;

            foreach ($sellerCartItems as $cartItem) {
                $product = Product::find($cartItem->product_id);
                if (!$product) {
                    continue;
                }

                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem->quantity;
                $tax += cart_product_tax($cartItem, $product, false) * $cartItem->quantity;
                $couponDiscount += $cartItem->discount;

                $productStock = $product->stocks->where('variant', $cartItem->variation)->first();
                if ($product->digital != 1 && $cartItem->quantity > ($productStock?->qty ?? 0)) {
                    $order->delete();
                    $combinedOrder->delete();
                    return response()->json([
                        'combined_order_id' => 0,
                        'result' => false,
                        'message' => translate('The requested quantity is not available for ') . $product->name,
                    ]);
                }
                if ($product->digital != 1 && $productStock) {
                    $productStock->qty -= $cartItem->quantity;
                    $productStock->save();
                }

                $orderDetail = new OrderDetail();
                $orderDetail->order_id = $order->id;
                $orderDetail->seller_id = $product->user_id;
                $orderDetail->product_id = $product->id;
                $orderDetail->variation = $cartItem->variation;
                $orderDetail->price = cart_product_price($cartItem, $product, false, false) * $cartItem->quantity;
                $orderDetail->tax = cart_product_tax($cartItem, $product, false) * $cartItem->quantity;
                $orderDetail->shipping_type = $cartItem->shipping_type;
                $orderDetail->shipping_cost = $cartItem->shipping_cost;
                $shipping += $orderDetail->shipping_cost;
                $orderDetail->earn_point = $product->earn_point ?? 0;
                $orderDetail->quantity = $cartItem->quantity;
                $orderDetail->save();

                $product->num_of_sale = $product->num_of_sale + $cartItem->quantity;
                $product->save();

                $order->seller_id = $product->user_id;
                $order->shipping_type = $cartItem->shipping_type;
                if ($cartItem->shipping_type == 'pickup_point') {
                    $order->pickup_point_id = $cartItem->pickup_point;
                }
                if ($cartItem->shipping_type == 'carrier') {
                    $order->carrier_id = $cartItem->carrier_id;
                }

                if ($product->added_by == 'seller' && $product->user?->seller) {
                    $seller = $product->user->seller;
                    $seller->num_of_sale += $cartItem->quantity;
                    $seller->save();
                }
            }

            $order->grand_total = $subtotal + $tax + $shipping;
            if ($sellerCartItems[0]->coupon_code) {
                $order->coupon_discount = $couponDiscount;
                $order->grand_total -= $couponDiscount;
            }

            $combinedOrder->grand_total += $order->grand_total;
            $order->save();
        }

        $combinedOrder->save();
        Cart::where('user_id', $user->id)->active()->delete();

        if ($paymentType == 'cash_on_delivery') {
            try {
                \App\Utility\NotificationUtility::sendOrderPlacedNotification($order);
            } catch (\Exception $e) {
            }
        }

        return response()->json([
            'combined_order_id' => $combinedOrder->id,
            'result' => true,
            'message' => translate('Your order has been placed successfully'),
        ]);
    }

    /**
     * GET /api/v2/checkout/orders
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
     * GET /api/v2/checkout/orders/{id}
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
                'shipping_address' => json_decode($order->shipping_address, true),
                'items' => $items,
            ],
        ]);
    }

    /**
     * POST /api/v2/checkout/orders/{id}/cancel
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
}
