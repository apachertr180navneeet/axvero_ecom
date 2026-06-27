<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Carrier;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Country;
use App\Models\ProductLimit;
use Auth;
use App\Utility\CartUtility;
use Session;
use Cookie;

class CartController extends Controller
{
  public function index(Request $request)
{
    $user = auth()->user();
    $productLimit = ProductLimit::first()->product_limit;
    if ($user != null) {
        $user_id = $user->id;

        if ($request->session()->get('temp_user_id')) {
            Cart::where('temp_user_id', $request->session()->get('temp_user_id'))
                ->update([
                    'user_id' => $user_id,
                    'temp_user_id' => null
                ]);

            Session::forget('temp_user_id');
        }

        $carts = Cart::where('user_id', $user_id)->get();
    } else {
        $temp_user_id = $request->session()->get('temp_user_id');
        $carts = ($temp_user_id != null)
            ? Cart::where('temp_user_id', $temp_user_id)->get()
            : collect();
    }

    if ($carts->count() > 0) {
        $carts->toQuery()->update(['shipping_cost' => 0]);
        $carts = $carts->fresh();
    }

    /* ==========================
       TOTAL CALCULATION
       ========================== */

    $subtotal = 0;
    $tax = 0;
    $gst = 0;
    $shipping = 0;
    $coupon_discount = $carts->sum('discount');

    foreach ($carts as $cartItem) {
        $product = get_single_product($cartItem->product_id);

        $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem->quantity;
        $tax      += cart_product_tax($cartItem, $product, false) * $cartItem->quantity;

        if (addon_is_activated('gst_system')) {
            $gst += cart_product_gst($cartItem, $product, false);
        }

        $shipping += $cartItem->shipping_cost;
    }

    $total = $subtotal + $tax + $gst + $shipping;

    if (Session::has('club_point')) {
        $total -= Session::get('club_point');
    }

    if ($coupon_discount > 0) {
        $total -= $coupon_discount;
    }

    /* ==========================
       BULK BUYER PAYMENT SPLIT 
       ========================== */

    $is_bulk_buyer = false;
   $bulk_online_pay_amount = 0;
$bulk_cod_pay_amount = $total;

    if ($user && $user->is_bulk_buyer == "1") {
        $is_bulk_buyer = true;

        // 40% Online, remaining COD
        $bulk_online_pay_amount = round($total * 0.30, 2);
        $bulk_cod_pay_amount    = round($total - $bulk_online_pay_amount, 2);
    }

    return view(
        'frontend.view_cart',
        compact(
            'productLimit',
            'carts',
            'subtotal',
            'tax',
            'gst',
            'shipping',
            'coupon_discount',
            'total',
            'is_bulk_buyer',
            'bulk_online_pay_amount',
            'bulk_cod_pay_amount'
        )
    );
}

    public function showCartModal(Request $request)
    {
        $product = Product::find($request->id);
        return view('frontend.partials.cart.addToCart', compact('product'));
    }

    public function showCartModalAuction(Request $request)
    {
        $product = Product::find($request->id);
        return view('auction.frontend.addToCartAuction', compact('product'));
    }

    public function addToCart(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'status' => 0,
                'alert'  => 'Please login first to add product to cart'
            ]);
        }

        $authUser = auth()->user();
        $product = Product::findOrFail($request->id);
        $productLimit = ProductLimit::first()->product_limit;
        
        $totalWeight = 0;
        
        if (auth()->check()) {
            $carts = Cart::where('user_id', auth()->id())->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = $temp_user_id
                ? Cart::where('temp_user_id', $temp_user_id)->get()
                : collect();
        }
        
        foreach ($carts as $cart) {
            $p = Product::find($cart->product_id);
            if ($p && $p->weight) {
                $totalWeight += $p->weight * $cart->quantity;
            }
        }
        
        $totalWeight += $product->weight * $request->quantity;
        
        if ($totalWeight > $productLimit) {
            return response()->json([
                'status' => 0,
                'modal_view' => '<div class="text-center p-4 text-warning">
                    Total cart weight limit exceeded
                </div>'
            ]);
        }

        if($authUser != null) {
            $user_id = $authUser->id;
            $data['user_id'] = $user_id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            if($request->session()->get('temp_user_id')) {
                $temp_user_id = $request->session()->get('temp_user_id');
            } else {
                $temp_user_id = bin2hex(random_bytes(10));
                $request->session()->put('temp_user_id', $temp_user_id);
            }
            $data['temp_user_id'] = $temp_user_id;
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        $check_auction_in_cart = CartUtility::check_auction_in_cart($carts);
        $product = Product::find($request->id);
        $carts = array();

        if($check_auction_in_cart && $product->auction_product == 0) {
            return array(
                'status' => 0,
                'cart_count' => count($carts),
                'modal_view' => view('frontend.partials.cart.removeAuctionProductFromCart')->render(),
                'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
            );
        }

        $quantity = $request['quantity'];

        if ($quantity < $product->min_qty) {
            return array(
                'status' => 0,
                'cart_count' => count($carts),
                'modal_view' => view('frontend.partials.minQtyNotSatisfied', ['min_qty' => $product->min_qty])->render(),
                'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
            );
        }

        //check the color enabled or disabled for the product
        $str = CartUtility::create_cart_variant($product, $request->all());
        $product_stock = $product->stocks->where('variant', $str)->first();

        if($authUser != null) {
            $user_id = $authUser->id;
            $cart = Cart::firstOrNew([
                'variation' => $str,
                'user_id' => $user_id,
                'product_id' => $request['id']
            ]);
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $cart = Cart::firstOrNew([
                'variation' => $str,
                'temp_user_id' => $temp_user_id,
                'product_id' => $request['id']
            ]);
        }

        if ($cart->exists && $product->digital == 0) {
            if ($product->auction_product == 1 && ($cart->product_id == $product->id)) {
                return array(
                    'status' => 0,
                    'cart_count' => count($carts),
                    'modal_view' => view('frontend.partials.cart.auctionProductAlredayAddedCart')->render(),
                    'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
                );
            }
            if ($product_stock->qty < $cart->quantity + $request['quantity']) {
                return array(
                    'status' => 0,
                    'cart_count' => count($carts),
                    'modal_view' => view('frontend.partials.outOfStockCart')->render(),
                    'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
                );
            }
            $quantity = $cart->quantity + $request['quantity'];
        }

        $price = CartUtility::get_price($product, $product_stock, $request->quantity);
        $tax = CartUtility::tax_calculation($product, $price);

        CartUtility::save_cart_data($cart, $product, $price, $tax, $quantity);

        if($authUser != null) {
            $user_id = $authUser->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        return array(
            'status' => 1,
            'cart_count' => count($carts),
            'modal_view' => view('frontend.partials.cart.addedToCart', compact('product', 'cart'))->render(),
            'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
        );
    }

    //removes from Cart
    public function removeFromCart(Request $request)
    {
        Cart::destroy($request->id);
        $authUser = auth()->user();
        if ($authUser != null) {
            $user_id = $authUser->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        return array(
            'cart_count' => count($carts),
            'cart_view' => view('frontend.partials.cart.cart_details', compact('carts'))->render(),
            'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
        );
    }

    //updated the quantity for a cart item
    public function updateQuantity(Request $request)
    {
        $cartItem = Cart::findOrFail($request->id);

        if ($cartItem['id'] == $request->id) {
            $product = Product::find($cartItem['product_id']);
            $product_stock = $product->stocks->where('variant', $cartItem['variation'])->first();
            $quantity = $product_stock->qty;
            $price = $product_stock->price;

            //discount calculation
            $discount_applicable = false;

            if ($product->discount_start_date == null) {
                $discount_applicable = true;
            } elseif (
                strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
            ) {
                $discount_applicable = true;
            }

            if ($discount_applicable) {
                if ($product->discount_type == 'percent') {
                    $price -= ($price * $product->discount) / 100;
                } elseif ($product->discount_type == 'amount') {
                    $price -= $product->discount;
                }
            }

            if ($quantity >= $request->quantity) {
                if ($request->quantity >= $product->min_qty) {
                    $cartItem['quantity'] = $request->quantity;
                }
            }

            if ($product->wholesale_product) {
                $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $request->quantity)->where('max_qty', '>=', $request->quantity)->first();
                if ($wholesalePrice) {
                    $price = $wholesalePrice->price;
                }
            }

            $cartItem['price'] = $price;
            $cartItem->save();
        }

        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        return array(
            'cart_count' => count($carts),
            'cart_view' => view('frontend.partials.cart.cart_details', compact('carts'))->render(),
            'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
        );
    }

    public function updateCartStatus(Request $request)
    {
        $product_ids = $request->product_id;

        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        $coupon_applied = $carts->toQuery()->where('coupon_applied', 1)->first();
        if($coupon_applied != null){
            $owner_id = $coupon_applied->owner_id;
            $coupon_code = $coupon_applied->coupon_code;
            $user_carts = $carts->toQuery()->where('owner_id', $owner_id)->get();
            $coupon_discount = $user_carts->toQuery()->sum('discount');
            $user_carts->toQuery()->update(
                [
                    'discount' => 0.00,
                    'coupon_code' => '',
                    'coupon_applied' => 0
                ]
            );
        }

        $carts->toQuery()->update(['status' => 0]);
        if($product_ids != null){
            if($coupon_applied != null){
                $active_user_carts = $user_carts->toQuery()->whereIn('product_id', $product_ids)->get();
                if (count($active_user_carts) > 0) {
                    $active_user_carts->toQuery()->update(
                        [
                            'discount' => $coupon_discount / count($active_user_carts),
                            'coupon_code' => $coupon_code,
                            'coupon_applied' => 1
                        ]
                    );
                }
            }

            $carts->toQuery()->whereIn('product_id', $product_ids)->update(['status' => 1]);
        }
        $carts = $carts->fresh();

        return view('frontend.partials.cart.cart_details', compact('carts'))->render();
    }
   public function updateBulkBuyer(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    
        $request->validate([
            'is_bulk_buyer' => 'required|in:0,1'
        ]);
    
        $user = auth()->user();
        $user->is_bulk_buyer = (int) $request->is_bulk_buyer;
        $user->save();
    
        return response()->json([
            'message' => $request->is_bulk_buyer
                ? 'Bulk Buyer enabled successfully'
                : 'Bulk Buyer disabled successfully'
        ]);
    }

}
