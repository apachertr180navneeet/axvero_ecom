<?php

namespace App\Http\Controllers\Api\V2\Admin;

use App\Http\Controllers\Api\V2\Controller;
use App\Models\Category;
use App\Utility\CategoryUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\User;

class CategoryController extends Controller
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
        $categories = Category::with('parentCategory')->orderBy('order_level', 'desc')->paginate($request->per_page ?? 20);

        return response()->json(['result' => true, 'data' => $categories]);
    }

    public function show(Request $request, $id)
    {
        $this->adminUser($request);
        $category = Category::with('parentCategory')->find($id);
        if (!$category) {
            return response()->json(['result' => false, 'message' => translate('Category not found')], 404);
        }

        return response()->json(['result' => true, 'data' => $category]);
    }

    public function store(Request $request)
    {
        $this->adminUser($request);

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'order_level' => 'nullable|integer',
            'digital' => 'nullable|integer',
            'featured' => 'nullable|boolean',
            'cover_image' => 'nullable|integer',
            'banner' => 'nullable|integer',
            'icon' => 'nullable|integer',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable|max:500',
            'slug' => 'nullable|unique:categories,slug',
        ]);

        if ($validator->fails()) {
            return $this->failed(implode(' ', $validator->errors()->all()));
        }

        $category = new Category;
        $category->name = $request->name;
        $category->parent_id = $request->parent_id ?? 0;
        $category->order_level = $request->order_level ?? 0;
        $category->digital = $request->digital ?? 0;
        $category->featured = $request->featured ?? 0;
        $category->cover_image = $request->cover_image ?? 0;
        $category->banner = $request->banner ?? 0;
        $category->icon = $request->icon ?? 0;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->slug = $request->slug ? preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug)) : preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)) . '-' . strtolower(substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 4));
        $category->save();

        return response()->json(['result' => true, 'message' => translate('Category created successfully'), 'data' => $category]);
    }

    public function update(Request $request, $id)
    {
        $this->adminUser($request);
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['result' => false, 'message' => translate('Category not found')], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'order_level' => 'nullable|integer',
            'digital' => 'nullable|integer',
            'featured' => 'nullable|boolean',
            'cover_image' => 'nullable|integer',
            'banner' => 'nullable|integer',
            'icon' => 'nullable|integer',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable|max:500',
            'slug' => 'nullable|unique:categories,slug,' . $id,
        ]);

        if ($validator->fails()) {
            return $this->failed(implode(' ', $validator->errors()->all()));
        }

        if ($request->has('name')) $category->name = $request->name;
        if ($request->has('parent_id')) $category->parent_id = $request->parent_id;
        if ($request->has('order_level')) $category->order_level = $request->order_level;
        if ($request->has('digital')) $category->digital = $request->digital;
        if ($request->has('featured')) $category->featured = $request->featured;
        if ($request->has('cover_image')) $category->cover_image = $request->cover_image;
        if ($request->has('banner')) $category->banner = $request->banner;
        if ($request->has('icon')) $category->icon = $request->icon;
        if ($request->has('meta_title')) $category->meta_title = $request->meta_title;
        if ($request->has('meta_description')) $category->meta_description = $request->meta_description;
        if ($request->has('slug')) $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        $category->save();

        return response()->json(['result' => true, 'message' => translate('Category updated successfully'), 'data' => $category]);
    }

    public function destroy(Request $request, $id)
    {
        $this->adminUser($request);
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['result' => false, 'message' => translate('Category not found')], 404);
        }

        $child_ids = CategoryUtility::children_ids($category->id);
        array_push($child_ids, $category->id);

        Category::destroy($child_ids);

        return response()->json(['result' => true, 'message' => translate('Category deleted successfully')]);
    }

    public function toggleFeatured(Request $request, $id)
    {
        $this->adminUser($request);
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['result' => false, 'message' => translate('Category not found')], 404);
        }
        $category->featured = $request->featured ?? !$category->featured;
        $category->save();

        return response()->json(['result' => true, 'message' => translate('Category updated successfully')]);
    }
}
