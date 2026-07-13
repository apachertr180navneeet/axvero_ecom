<?php

namespace App\Http\Controllers\Api\V2\Admin;

use App\Http\Controllers\Api\V2\Controller;
use App\Models\Product;
use App\Models\Category;
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
            "category",
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
