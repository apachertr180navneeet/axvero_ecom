<?php

namespace App\Http\Controllers\Api\V2\Admin;

use App\Http\Controllers\Api\V2\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductStock;
use App\Models\ProductTranslation;
use App\Utility\CategoryUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\User;

class ProductController extends Controller
{
    protected function adminUser(Request $request): User
    {
        $bearer = $request->bearerToken();
        if (!$bearer) {
            abort(
                401,
                json_encode([
                    "result" => false,
                    "message" => translate("Unauthenticated"),
                ]),
            );
        }
        $user = PersonalAccessToken::findToken($bearer)?->tokenable;
        if (
            !$user ||
            !($user instanceof User) ||
            $user->user_type !== "admin"
        ) {
            abort(
                403,
                json_encode([
                    "result" => false,
                    "message" => translate("Forbidden"),
                ]),
            );
        }
        return $user;
    }

    public function store(Request $request)
    {
        $this->adminUser($request);

        $validator = Validator::make($request->all(), [
            "name" => "required|max:255",
            "category_id" => "required|exists:categories,id",
            "unit_price" => "required|numeric|gt:0",
            "current_stock" => "required|integer|min:0",
            "unit" => "required|max:50",
            "brand_id" => "nullable|exists:brands,id",
            "description" => "nullable",
            "photos" => "nullable",
            "thumbnail_img" => "nullable|integer",
            "featured" => "nullable|boolean",
            "tags" => "nullable",
            "video_provider" => "nullable",
            "video_link" => "nullable",
            "shipping_type" => "nullable|in:free,flat_rate",
            "flat_shipping_cost" => "nullable|numeric",
            "min_qty" => "nullable|integer|min:1",
            "low_stock_quantity" => "nullable|integer|min:0",
            "discount" => "nullable|numeric|min:0",
            "discount_type" => "nullable|in:amount,percent",
            "discount_start_date" => "nullable|date",
            "discount_end_date" => "nullable|date|after:discount_start_date",
            "meta_title" => "nullable|max:255",
            "meta_description" => "nullable|max:500",
        ]);

        if ($validator->fails()) {
            return $this->failed(implode(" ", $validator->errors()->all()));
        }

        $adminUser = User::where("user_type", "admin")->first();

        $slug =
            preg_replace(
                "/[^A-Za-z0-9\-]/",
                "",
                str_replace(" ", "-", $request->name),
            ) .
            "-" .
            strtolower(
                substr(
                    str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"),
                    0,
                    4,
                ),
            );

        $product = Product::create([
            "name" => $request->name,
            "slug" => $slug,
            "category_id" => $request->category_id,
            "brand_id" => $request->brand_id ?? 0,
            "user_id" => $adminUser->id,
            "added_by" => "admin",
            "unit_price" => $request->unit_price,
            "unit" => $request->unit,
            "description" => $request->description ?? "",
            "photos" => $request->photos ?? "",
            "thumbnail_img" => $request->thumbnail_img ?? 0,
            "featured" => $request->featured ?? 0,
            "tags" => is_array($request->tags)
                ? implode(",", $request->tags)
                : $request->tags ?? "",
            "video_provider" => $request->video_provider ?? "",
            "video_link" => $request->video_link ?? "",
            "shipping_type" => $request->shipping_type ?? "flat_rate",
            "shipping_cost" =>
                $request->shipping_type === "free"
                    ? 0
                    : $request->flat_shipping_cost ?? 0,
            "min_qty" => $request->min_qty ?? 1,
            "low_stock_quantity" => $request->low_stock_quantity ?? 0,
            "discount" => $request->discount ?? 0.0,
            "discount_type" => $request->discount_type ?? "amount",
            "discount_start_date" => $request->discount_start_date,
            "discount_end_date" => $request->discount_end_date,
            "meta_title" => $request->meta_title ?? $request->name,
            "meta_description" =>
                $request->meta_description ??
                strip_tags($request->description ?? ""),
            "published" => 1,
            "approved" => 1,
            "current_stock" => $request->current_stock,
        ]);

        ProductStock::create([
            "product_id" => $product->id,
            "variant" => "",
            "price" => $request->unit_price,
            "qty" => $request->current_stock,
            "sku" => $request->sku ?? "",
        ]);

        ProductTranslation::create([
            "lang" => env("DEFAULT_LANGUAGE", "en"),
            "name" => $request->name,
            "unit" => $request->unit,
            "description" => $request->description ?? "",
            "product_id" => $product->id,
        ]);

        return response()->json([
            "result" => true,
            "message" => translate("Product created successfully"),
            "data" => $product->fresh()->load("stocks", "categories", "brand"),
        ]);
    }

    public function index(Request $request)
    {
        $this->adminUser($request);
        $query = Product::query();

        if ($request->type === "admin") {
            $query->where("added_by", "admin");
        } elseif ($request->type === "seller") {
            $query->where("added_by", "seller");
        }

        if ($request->search) {
            $query->where("name", "like", "%" . $request->search . "%");
        }

        if ($request->category_id) {
            $category_ids = CategoryUtility::children_ids(
                $request->category_id,
            );
            $category_ids[] = $request->category_id;
            $query->whereIn("category_id", $category_ids);
        }

        if ($request->has("published")) {
            $query->where("published", $request->published);
        }

        if ($request->has("approved")) {
            $query->where("approved", $request->approved);
        }

        $products = $query
            ->orderBy("created_at", "desc")
            ->paginate($request->per_page ?? 20);

        return response()->json(["result" => true, "data" => $products]);
    }

    public function show(Request $request, $id)
    {
        $this->adminUser($request);
        $product = Product::with(
            "stocks",
            "categories",
            "brand",
            "reviews",
        )->find($id);
        if (!$product) {
            return response()->json(
                [
                    "result" => false,
                    "message" => translate("Product not found"),
                ],
                404,
            );
        }

        return response()->json(["result" => true, "data" => $product]);
    }

    public function togglePublished(Request $request, $id)
    {
        $this->adminUser($request);
        $product = Product::find($id);
        if (!$product) {
            return response()->json(
                [
                    "result" => false,
                    "message" => translate("Product not found"),
                ],
                404,
            );
        }
        $product->published = $request->published ?? !$product->published;
        $product->save();

        return response()->json([
            "result" => true,
            "message" => translate("Product updated successfully"),
        ]);
    }

    public function toggleFeatured(Request $request, $id)
    {
        $this->adminUser($request);
        $product = Product::find($id);
        if (!$product) {
            return response()->json(
                [
                    "result" => false,
                    "message" => translate("Product not found"),
                ],
                404,
            );
        }
        $product->featured = $request->featured ?? !$product->featured;
        $product->save();

        return response()->json([
            "result" => true,
            "message" => translate("Product updated successfully"),
        ]);
    }

    public function toggleTodaysDeal(Request $request, $id)
    {
        $this->adminUser($request);
        $product = Product::find($id);
        if (!$product) {
            return response()->json(
                [
                    "result" => false,
                    "message" => translate("Product not found"),
                ],
                404,
            );
        }
        $product->todays_deal = $request->todays_deal ?? !$product->todays_deal;
        $product->save();

        return response()->json([
            "result" => true,
            "message" => translate("Product updated successfully"),
        ]);
    }

    public function approve(Request $request, $id)
    {
        $this->adminUser($request);
        $product = Product::find($id);
        if (!$product) {
            return response()->json(
                [
                    "result" => false,
                    "message" => translate("Product not found"),
                ],
                404,
            );
        }
        $product->approved = $request->approved ?? 1;
        $product->save();

        return response()->json([
            "result" => true,
            "message" => translate("Product approval updated successfully"),
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $this->adminUser($request);
        $product = Product::find($id);
        if (!$product) {
            return response()->json(
                [
                    "result" => false,
                    "message" => translate("Product not found"),
                ],
                404,
            );
        }
        $product->delete();

        return response()->json([
            "result" => true,
            "message" => translate("Product deleted successfully"),
        ]);
    }
}
