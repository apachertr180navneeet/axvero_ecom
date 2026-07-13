<?php

namespace App\Http\Controllers\Api\V2\Admin;

use App\Http\Controllers\Api\V2\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\User;

class BrandController extends Controller
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
        $brands = Brand::orderBy('name', 'asc')->paginate($request->per_page ?? 20);

        return response()->json(['result' => true, 'data' => $brands]);
    }

    public function show(Request $request, $id)
    {
        $this->adminUser($request);
        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json(['result' => false, 'message' => translate('Brand not found')], 404);
        }

        return response()->json(['result' => true, 'data' => $brand]);
    }

    public function store(Request $request)
    {
        $this->adminUser($request);

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'logo' => 'nullable|integer',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable|max:500',
            'slug' => 'nullable|unique:brands,slug',
        ]);

        if ($validator->fails()) {
            return $this->failed(implode(' ', $validator->errors()->all()));
        }

        $brand = new Brand;
        $brand->name = $request->name;
        $brand->logo = $request->logo ?? 0;
        $brand->meta_title = $request->meta_title;
        $brand->meta_description = $request->meta_description;
        $brand->slug = $request->slug ? preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug)) : preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)) . '-' . strtolower(substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 4));
        $brand->save();

        return response()->json(['result' => true, 'message' => translate('Brand created successfully'), 'data' => $brand]);
    }

    public function update(Request $request, $id)
    {
        $this->adminUser($request);
        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json(['result' => false, 'message' => translate('Brand not found')], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|max:255',
            'logo' => 'nullable|integer',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable|max:500',
            'slug' => 'nullable|unique:brands,slug,' . $id,
        ]);

        if ($validator->fails()) {
            return $this->failed(implode(' ', $validator->errors()->all()));
        }

        if ($request->has('name')) $brand->name = $request->name;
        if ($request->has('logo')) $brand->logo = $request->logo;
        if ($request->has('meta_title')) $brand->meta_title = $request->meta_title;
        if ($request->has('meta_description')) $brand->meta_description = $request->meta_description;
        if ($request->has('slug')) $brand->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        $brand->save();

        return response()->json(['result' => true, 'message' => translate('Brand updated successfully'), 'data' => $brand]);
    }

    public function destroy(Request $request, $id)
    {
        $this->adminUser($request);
        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json(['result' => false, 'message' => translate('Brand not found')], 404);
        }
        $brand->delete();

        return response()->json(['result' => true, 'message' => translate('Brand deleted successfully')]);
    }
}
