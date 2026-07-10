<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use App\Utility\CartUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class FrontendCartController extends Controller
{
    protected function authenticatedUser(Request $request): User
    {
        $bearer = $request->bearerToken();
        if (!$bearer) {
            abort(401, json_encode(['result' => false, 'message' => translate('Unauthenticated')]));
        }
        $tokenable = PersonalAccessToken::findToken($bearer)?->tokenable;
        if (!$tokenable instanceof User) {
            abort(401, json_encode(['result' => false, 'message' => translate('Unauthenticated')]));
        }
        return $tokenable;
    }

    /**
     * GET /api/v2/cart
     */
    public function index(Request $request)
    {
        $user = $this->authenticatedUser($request);
        $cartItems = Cart::where('user_id', $user->id)->active()->get();
        $ownerIds = $cartItems->pluck('owner_id')->unique()->values()->toArray();

        $shops = [];
        $grandTotal = 0.00;
        $currencySymbol = currency_symbol();

        foreach ($ownerIds as $ownerId) {
            $items = $cartItems->where('owner_id', $ownerId)->values();
            $shopData = Shop::where('user_id', $ownerId)->first();

            $cartItemData = [];
            $subTotal = 0.00;

            foreach ($items as $item) {
                $product = Product::find($item->product_id);
                if (!$product) {
                    continue;
                }

                $price = cart_product_price($item, $product, false, false) * $item->quantity;
                $tax = cart_product_tax($item, $product, false);
                $subTotal += $price + $tax;

                $stock = $product->stocks->where('variant', $item->variation)->first();

                $cartItemData[] = [
                    'id' => (int) $item->id,
                    'product_id' => (int) $item->product_id,
                    'product_name' => $product->getTranslation('name'),
                    'product_thumbnail_image' => uploaded_asset($product->thumbnail_img),
                    'variation' => $item->variation,
                    'price' => single_price($price),
                    'price_value' => (float) cart_product_price($item, $product, false, false),
                    'currency_symbol' => $currencySymbol,
                    'tax' => single_price($tax),
                    'shipping_cost' => (float) $item->shipping_cost,
                    'quantity' => (int) $item->quantity,
                    'lower_limit' => (int) $product->min_qty,
                    'upper_limit' => $stock ? (int) $stock->qty : 0,
                    'stock' => $stock ? (int) $stock->qty : 0,
                    'digital' => $product->digital,
                    'auction_product' => $product->auction_product,
                ];
            }

            $grandTotal += $subTotal;

            $shops[] = [
                'name' => $shopData ? translate($shopData->name) : translate('Inhouse'),
                'owner_id' => (int) $ownerId,
                'sub_total' => single_price($subTotal),
                'cart_items' => $cartItemData,
            ];
        }

        $firstItem = $cartItems->first();

        return response()->json([
            'result' => true,
            'grand_total' => single_price($grandTotal),
            'grand_total_value' => convert_price($grandTotal),
            'coupon_code' => $firstItem ? $firstItem->coupon_code : '',
            'coupon_applied' => $firstItem ? (bool) $firstItem->coupon_applied : false,
            'data' => $shops,
        ]);
    }

    /**
     * POST /api/v2/cart/add
     */
    public function add(Request $request)
    {
        $user = $this->authenticatedUser($request);

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variant' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()->all(),
            ]);
        }

        $product = Product::findOrFail($request->id);
        $variant = $request->input('variant', '');
        $quantity = (int) $request->quantity;

        if ($product->min_qty > $quantity) {
            return response()->json([
                'result' => false,
                'message' => translate('Minimum') . " {$product->min_qty} " . translate('item(s) should be ordered'),
            ]);
        }

        $existingCarts = Cart::where('user_id', $user->id)->active()->get();
        $checkAuctionInCart = CartUtility::check_auction_in_cart($existingCarts);

        if ($checkAuctionInCart && $product->auction_product == 0) {
            return response()->json([
                'result' => false,
                'message' => translate('Remove auction product from cart to add this product.'),
            ]);
        }

        if (!$checkAuctionInCart && $existingCarts->isNotEmpty() && $product->auction_product == 1) {
            return response()->json([
                'result' => false,
                'message' => translate('Remove other products from cart to add this auction product.'),
            ]);
        }

        $productStock = $product->stocks->where('variant', $variant)->first();
        if (!$productStock) {
            $productStock = $product->stocks->first();
        }

        $cart = Cart::firstOrNew([
            'variation' => $variant,
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        if ($cart->exists && $product->digital == 0) {
            if ($product->auction_product == 1 && $cart->product_id == $product->id) {
                return response()->json([
                    'result' => false,
                    'message' => translate('This auction product is already added to your cart.'),
                ]);
            }

            $availableQty = $productStock ? $productStock->qty : 0;
            if ($availableQty < $cart->quantity + $quantity) {
                $variantStr = $variant ? translate('for') . " ($variant)" : '';
                if ($availableQty == 0) {
                    return response()->json([
                        'result' => false,
                        'message' => translate('Stock out'),
                    ]);
                }
                return response()->json([
                    'result' => false,
                    'message' => translate('Only') . " {$availableQty} " . translate('item(s) are available') . " {$variantStr}",
                ]);
            }

            if ($product->digital == 1 && $cart->product_id == $product->id) {
                return response()->json([
                    'result' => false,
                    'message' => translate('Already added this product'),
                ]);
            }

            $quantity = $cart->quantity + $quantity;
        }

        $price = CartUtility::get_price($product, $productStock, $quantity);
        $tax = CartUtility::tax_calculation($product, $price);
        CartUtility::save_cart_data($cart, $product, $price, $tax, $quantity);

        return response()->json([
            'result' => true,
            'message' => translate('Product added to cart successfully'),
        ]);
    }

    /**
     * POST /api/v2/cart/update-quantity
     */
    public function updateQuantity(Request $request)
    {
        $user = $this->authenticatedUser($request);

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:carts,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()->all(),
            ]);
        }

        $cart = Cart::where('id', $request->id)->where('user_id', $user->id)->first();
        if (!$cart) {
            return response()->json(['result' => false, 'message' => translate('Cart item not found')], 404);
        }

        $product = Product::find($cart->product_id);
        if ($product && $product->auction_product == 1) {
            return response()->json(['result' => false, 'message' => translate('Maximum available quantity reached')]);
        }

        $stock = $cart->product->stocks->where('variant', $cart->variation)->first();
        $availableQty = $stock ? $stock->qty : 0;

        if ($availableQty >= $request->quantity) {
            $cart->update(['quantity' => $request->quantity]);
            return response()->json(['result' => true, 'message' => translate('Cart updated')]);
        }

        return response()->json(['result' => false, 'message' => translate('Maximum available quantity reached')]);
    }

    /**
     * DELETE /api/v2/cart/{id}
     */
    public function destroy($id, Request $request)
    {
        $user = $this->authenticatedUser($request);

        $cart = Cart::where('id', $id)->where('user_id', $user->id)->first();
        if (!$cart) {
            return response()->json(['result' => false, 'message' => translate('Cart item not found')], 404);
        }

        $cart->delete();
        return response()->json(['result' => true, 'message' => translate('Product is successfully removed from your cart')]);
    }

    /**
     * GET /api/v2/cart/summary
     */
    public function summary(Request $request)
    {
        $user = $this->authenticatedUser($request);
        $items = Cart::where('user_id', $user->id)->active()->get();

        if ($items->isEmpty()) {
            return response()->json([
                'sub_total' => format_price(0.00),
                'tax' => format_price(0.00),
                'shipping_cost' => format_price(0.00),
                'discount' => format_price(0.00),
                'grand_total' => format_price(0.00),
                'grand_total_value' => 0.00,
                'coupon_code' => '',
                'coupon_applied' => false,
            ]);
        }

        $subTotal = 0.00;
        $tax = 0.00;

        foreach ($items as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $subTotal += cart_product_price($item, $product, false, false) * $item->quantity;
                $tax += cart_product_tax($item, $product, false) * $item->quantity;
            }
        }

        $shippingCost = $items->sum('shipping_cost');
        $discount = $items->sum('discount');
        $grandTotal = ($subTotal + $tax + $shippingCost) - $discount;
        $firstItem = $items->first();

        return response()->json([
            'sub_total' => single_price($subTotal),
            'tax' => single_price($tax),
            'shipping_cost' => single_price($shippingCost),
            'discount' => single_price($discount),
            'grand_total' => single_price($grandTotal),
            'grand_total_value' => convert_price($grandTotal),
            'coupon_code' => $firstItem ? $firstItem->coupon_code : '',
            'coupon_applied' => $firstItem ? (bool) $firstItem->coupon_applied : false,
        ]);
    }

    /**
     * GET /api/v2/cart/count
     */
    public function count(Request $request)
    {
        $user = $this->authenticatedUser($request);
        $count = Cart::where('user_id', $user->id)->active()->count();

        return response()->json([
            'count' => $count,
            'status' => true,
        ]);
    }

    /**
     * POST /api/v2/cart/apply-coupon
     */
    public function applyCoupon(Request $request)
    {
        $user = $this->authenticatedUser($request);

        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()->all(),
            ]);
        }

        $coupon = Coupon::where('code', $request->coupon_code)->first();
        if (!$coupon) {
            return response()->json(['result' => false, 'message' => translate('Invalid coupon code!')]);
        }

        $cartItems = Cart::where('user_id', $user->id)
            ->where('owner_id', $coupon->user_id)
            ->active()
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'result' => false,
                'message' => translate('This coupon is not applicable to your cart products!'),
            ]);
        }

        $inRange = strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date;
        if (!$inRange) {
            return response()->json(['result' => false, 'message' => translate('Coupon expired!')]);
        }

        $isUsed = CouponUsage::where('user_id', $user->id)
            ->where('coupon_id', $coupon->id)
            ->exists();

        if ($isUsed) {
            return response()->json(['result' => false, 'message' => translate('You already used this coupon!')]);
        }

        $couponDetails = json_decode($coupon->details);
        $couponDiscount = 0;

        if ($coupon->type == 'cart_base') {
            $subTotal = 0;
            $tax = 0;
            $shipping = 0;

            foreach ($cartItems as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $subTotal += cart_product_price($item, $product, false, false) * $item->quantity;
                    $tax += cart_product_tax($item, $product, false) * $item->quantity;
                    $shipping += ($item->shipping ?? 0) * $item->quantity;
                }
            }

            $sum = $subTotal + $tax + $shipping;

            if ($sum >= $couponDetails->min_buy) {
                if ($coupon->discount_type == 'percent') {
                    $couponDiscount = ($sum * $coupon->discount) / 100;
                    if ($couponDiscount > $couponDetails->max_discount) {
                        $couponDiscount = $couponDetails->max_discount;
                    }
                } elseif ($coupon->discount_type == 'amount') {
                    $couponDiscount = $coupon->discount;
                }
            } else {
                return response()->json([
                    'result' => false,
                    'message' => translate('You need to order at least') . " {$couponDetails->min_buy}",
                ]);
            }
        } elseif ($coupon->type == 'product_base') {
            $couponDiscount = 0;
            foreach ($cartItems as $item) {
                $product = Product::find($item->product_id);
                if (!$product) {
                    continue;
                }
                foreach ($couponDetails as $detail) {
                    if ($detail->product_id == $item->product_id) {
                        $price = cart_product_price($item, $product, false, false) * $item->quantity;
                        if ($coupon->discount_type == 'percent') {
                            $discount = ($price * $coupon->discount) / 100;
                            if (isset($detail->max_discount) && $discount > $detail->max_discount) {
                                $discount = $detail->max_discount;
                            }
                        } elseif ($coupon->discount_type == 'amount') {
                            $discount = $coupon->discount;
                        } else {
                            $discount = $price;
                        }
                        $couponDiscount += $discount;
                    }
                }
            }
        }

        Cart::where('user_id', $user->id)
            ->where('owner_id', $coupon->user_id)
            ->active()
            ->update([
                'discount' => $couponDiscount / $cartItems->count(),
                'coupon_code' => $request->coupon_code,
                'coupon_applied' => 1,
            ]);

        CouponUsage::create([
            'user_id' => $user->id,
            'coupon_id' => $coupon->id,
        ]);

        return response()->json([
            'result' => true,
            'message' => translate('Coupon applied successfully'),
        ]);
    }

    /**
     * POST /api/v2/cart/remove-coupon
     */
    public function removeCoupon(Request $request)
    {
        $user = $this->authenticatedUser($request);

        Cart::where('user_id', $user->id)
            ->active()
            ->update([
                'discount' => 0.00,
                'coupon_code' => '',
                'coupon_applied' => 0,
            ]);

        return response()->json([
            'result' => true,
            'message' => translate('Coupon removed successfully'),
        ]);
    }
}
