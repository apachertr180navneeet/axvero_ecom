<?php

namespace App\Http\Controllers\Api\V2\Admin;

use App\Http\Controllers\Api\V2\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\User;

class CouponController extends Controller
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
        $coupons = Coupon::orderBy('created_at', 'desc')->paginate($request->per_page ?? 20);

        return response()->json(['result' => true, 'data' => $coupons]);
    }

    public function show(Request $request, $id)
    {
        $this->adminUser($request);
        $coupon = Coupon::find($id);
        if (!$coupon) {
            return response()->json(['result' => false, 'message' => translate('Coupon not found')], 404);
        }

        return response()->json(['result' => true, 'data' => $coupon]);
    }

    public function store(Request $request)
    {
        $this->adminUser($request);

        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'code' => 'required|unique:coupons,code',
            'discount' => 'required|numeric',
            'discount_type' => 'required|in:percent,amount',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($validator->fails()) {
            return $this->failed(implode(' ', $validator->errors()->all()));
        }

        $coupon = Coupon::create($request->all());

        return response()->json(['result' => true, 'message' => translate('Coupon created successfully'), 'data' => $coupon]);
    }

    public function update(Request $request, $id)
    {
        $this->adminUser($request);
        $coupon = Coupon::find($id);
        if (!$coupon) {
            return response()->json(['result' => false, 'message' => translate('Coupon not found')], 404);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'nullable|unique:coupons,code,' . $id,
            'discount' => 'nullable|numeric',
            'discount_type' => 'nullable|in:percent,amount',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        if ($validator->fails()) {
            return $this->failed(implode(' ', $validator->errors()->all()));
        }

        $coupon->update($request->all());

        return response()->json(['result' => true, 'message' => translate('Coupon updated successfully'), 'data' => $coupon]);
    }

    public function toggleStatus(Request $request, $id)
    {
        $this->adminUser($request);
        $coupon = Coupon::find($id);
        if (!$coupon) {
            return response()->json(['result' => false, 'message' => translate('Coupon not found')], 404);
        }
        $coupon->status = $request->status ?? !$coupon->status;
        $coupon->save();

        return response()->json(['result' => true, 'message' => translate('Coupon status updated successfully')]);
    }

    public function destroy(Request $request, $id)
    {
        $this->adminUser($request);
        $coupon = Coupon::find($id);
        if (!$coupon) {
            return response()->json(['result' => false, 'message' => translate('Coupon not found')], 404);
        }
        $coupon->delete();

        return response()->json(['result' => true, 'message' => translate('Coupon deleted successfully')]);
    }
}
