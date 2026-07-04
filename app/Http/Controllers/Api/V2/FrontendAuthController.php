<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\OTPVerificationController;
use App\Models\BusinessSetting;
use App\Models\Cart;
use App\Models\User;
use App\Notifications\AppEmailVerificationNotification;
use App\Rules\Recaptcha;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\PersonalAccessToken;

class FrontendAuthController extends Controller
{
    /**
     * 6. Customer login
     * POST /api/v2/auth/login
     */
    public function login(Request $request)
    {
        $loginBy = $request->input('login_by');
        if (!$loginBy) {
            $identifier = $request->input('email', $request->input('email_or_phone'));
            $loginBy = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
            $request->merge([
                'login_by' => $loginBy,
                'email' => $identifier,
            ]);
        }

        $messages = [
            'email.required' => $loginBy === 'email' ? translate('Email is required') : translate('Phone is required'),
            'email.email' => translate('Email must be a valid email address'),
            'email.numeric' => translate('Phone must be a number.'),
            'password.required' => translate('Password is required'),
        ];

        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'login_by' => 'required|in:email,phone',
            'email' => [
                'required',
                Rule::when($loginBy === 'email', ['email']),
                Rule::when($loginBy === 'phone', ['numeric']),
            ],
            'g-recaptcha-response' => [
                Rule::when(
                    get_setting('google_recaptcha') == 1 && get_setting($request->input('recaptcha_action')) == 1,
                    ['required', new Recaptcha()],
                    ['sometimes']
                ),
            ],
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()->all(),
            ]);
        }

        $identifier = $request->email;
        $user = User::where('user_type', 'customer')
            ->where(function ($query) use ($identifier) {
                $query->where('email', $identifier)
                    ->orWhere('phone', $identifier);
            })
            ->first();

        if ($user === null) {
            return response()->json([
                'result' => false,
                'message' => translate('User not found'),
                'user' => null,
            ], 401);
        }

        if ($user->banned) {
            return response()->json([
                'result' => false,
                'message' => translate('User is banned'),
                'user' => null,
            ], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'result' => false,
                'message' => translate('Unauthorized'),
                'user' => null,
            ], 401);
        }

        $tempUserId = $request->input('temp_user_id');
        return app(AuthController::class)->loginSuccess($user, '', $tempUserId);
    }

    /**
     * 7. Customer signup
     * POST /api/v2/auth/signup
     */
    public function signup(Request $request)
    {
        $registerBy = $request->input('register_by');
        if (!$registerBy) {
            $registerBy = $request->filled('email') ? 'email' : 'phone';
            $request->merge(['register_by' => $registerBy]);
        }

        if (!$request->filled('name')) {
            $firstName = trim((string) $request->input('first_name', ''));
            $lastName = trim((string) $request->input('last_name', ''));
            $name = trim($firstName . ' ' . $lastName);
            if ($name !== '') {
                $request->merge(['name' => $name]);
            }
        }

        if (!$request->filled('email_or_phone')) {
            $request->merge([
                'email_or_phone' => $registerBy === 'email'
                    ? $request->input('email')
                    : $request->input('phone'),
            ]);
        }

        $messages = [
            'name.required' => translate('Name is required'),
            'email_or_phone.required' => $registerBy === 'email' ? translate('Email is required') : translate('Phone is required'),
            'email_or_phone.email' => translate('Email must be a valid email address'),
            'email_or_phone.numeric' => translate('Phone must be a number.'),
            'email_or_phone.unique' => $registerBy === 'email' ? translate('The email has already been taken') : translate('The phone has already been taken'),
            'password.required' => translate('Password is required'),
            'password.confirmed' => translate('Password confirmation does not match'),
            'password.min' => translate('Minimum 6 digits required for password'),
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required|min:6|confirmed',
            'email_or_phone' => [
                'required',
                Rule::when($registerBy === 'email', ['email', 'unique:users,email']),
                Rule::when($registerBy === 'phone', ['numeric', 'unique:users,phone']),
            ],
            'g-recaptcha-response' => [
                Rule::when(
                    get_setting('google_recaptcha') == 1 && get_setting('recaptcha_customer_register') == 1,
                    ['required', new Recaptcha()],
                    ['sometimes']
                ),
            ],
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()->all(),
            ]);
        }

        $user = new User();
        $user->name = $request->name;
        $user->user_type = 'customer';

        if ($registerBy === 'email') {
            $user->email = $request->email_or_phone;
            if ($request->filled('phone')) {
                $user->phone = $request->phone;
            }
        } else {
            $user->phone = $request->email_or_phone;
            if ($request->filled('email')) {
                $user->email = $request->email;
            }
        }

        $user->password = bcrypt($request->password);
        $user->verification_code = rand(100000, 999999);
        $user->email_verified_at = null;

        if ($user->email !== null) {
            if (BusinessSetting::where('type', 'email_verification')->first()->value != 1) {
                $user->email_verified_at = date('Y-m-d H:i:s');
            }
        }

        $user->save();

        if ($user->email_verified_at === null) {
            if ($registerBy === 'email') {
                try {
                    $user->notify(new AppEmailVerificationNotification());
                } catch (\Exception $e) {
                }
            } else {
                $otpController = new OTPVerificationController();
                $otpController->send_code($user);
            }
        }

        $user->createToken('tokens')->plainTextToken;

        $tempUserId = $request->input('temp_user_id');
        return app(AuthController::class)->loginSuccess($user, '', $tempUserId);
    }

    /**
     * 8. Forgot password – send code
     * POST /api/v2/auth/password/forget_request
     */
    public function forgetRequest(Request $request)
    {
        $request->validate([
            'g-recaptcha-response' => [
                Rule::when(
                    get_setting('google_recaptcha') == 1 && get_setting('recaptcha_forgot_password') == 1,
                    ['required', new Recaptcha()],
                    ['sometimes']
                ),
            ],
        ]);

        $sendCodeBy = $request->input('send_code_by');
        $identifier = $request->input('email_or_phone', $request->input('email', $request->input('phone')));

        if (!$sendCodeBy && $identifier) {
            $sendCodeBy = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        }

        if (!$identifier || !$sendCodeBy) {
            return response()->json([
                'result' => false,
                'message' => translate('Email or phone is required'),
            ], 422);
        }

        $user = $sendCodeBy === 'email'
            ? User::where('email', $identifier)->first()
            : User::where('phone', $identifier)->first();

        if (!$user) {
            return response()->json([
                'result' => false,
                'message' => translate('User is not found'),
            ], 404);
        }

        $user->verification_code = rand(100000, 999999);
        $user->save();

        if ($sendCodeBy === 'phone') {
            $otpController = new OTPVerificationController();
            $otpController->send_code($user);
        } else {
            try {
                $user->notify(new AppEmailVerificationNotification());
            } catch (\Exception $e) {
            }
        }

        return response()->json([
            'result' => true,
            'message' => translate('A code is sent'),
        ], 200);
    }

    /**
     * 9. Reset password with verification code
     * POST /api/v2/auth/password/confirm_reset
     */
    public function confirmReset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'verification_code' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()->all(),
            ]);
        }

        $user = User::where('verification_code', $request->verification_code)->first();

        if ($user === null) {
            return response()->json([
                'result' => false,
                'message' => translate('No user is found'),
            ], 200);
        }

        $user->verification_code = null;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'result' => true,
            'message' => translate('Your password is reset.Please login'),
        ], 200);
    }

    /**
     * 10. Resend password-reset code
     * POST /api/v2/auth/password/resend_code
     */
    public function resendCode(Request $request)
    {
        $verifyBy = $request->input('verify_by', $request->input('send_code_by'));
        $identifier = $request->input('email_or_phone', $request->input('email', $request->input('phone')));

        if (!$verifyBy && $identifier) {
            $verifyBy = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        }

        if (!$identifier || !$verifyBy) {
            return response()->json([
                'result' => false,
                'message' => translate('Email or phone is required'),
            ], 422);
        }

        $user = $verifyBy === 'email'
            ? User::where('email', $identifier)->first()
            : User::where('phone', $identifier)->first();

        if (!$user) {
            return response()->json([
                'result' => false,
                'message' => translate('User is not found'),
            ], 404);
        }

        $user->verification_code = rand(100000, 999999);
        $user->save();

        if ($verifyBy === 'email') {
            $user->notify(new AppEmailVerificationNotification());
        } else {
            $otpController = new OTPVerificationController();
            $otpController->send_code($user);
        }

        return response()->json([
            'result' => true,
            'message' => translate('A code is sent again'),
        ], 200);
    }

    /**
     * GET /api/v2/auth/user
     */
    public function user(Request $request)
    {
        $user = $this->userFromBearer($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return response()->json([
            'result' => true,
            'user' => $this->formatUser($user),
        ]);
    }

    /**
     * GET /api/v2/auth/logout
     */
    public function logout(Request $request)
    {
        $bearer = $request->bearerToken();
        if ($bearer) {
            PersonalAccessToken::findToken($bearer)?->delete();
        }

        return response()->json([
            'result' => true,
            'message' => translate('Successfully logged out'),
        ]);
    }

    /**
     * POST /api/v2/auth/info
     */
    public function authInfo(Request $request)
    {
        $token = $request->bearerToken() ?? $request->input('access_token');
        if (!$token) {
            return app(AuthController::class)->loginFailed();
        }

        $accessToken = PersonalAccessToken::findToken($token);
        if (!$accessToken || !$accessToken->tokenable) {
            return app(AuthController::class)->loginFailed();
        }

        return app(AuthController::class)->loginSuccess($accessToken->tokenable, $token);
    }

    /**
     * POST /api/v2/auth/confirm_code
     */
    public function confirmVerificationCode(Request $request)
    {
        $user = $this->userFromBearer($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if ($user->verification_code == $request->verification_code) {
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->verification_code = null;
            $user->save();

            return response()->json([
                'result' => true,
                'message' => translate('Your account is now verified'),
            ], 200);
        }

        return response()->json([
            'result' => false,
            'message' => translate('Code does not match, you can request for resending the code'),
        ], 200);
    }

    /**
     * GET /api/v2/auth/resend_code
     */
    public function resendVerificationCode(Request $request)
    {
        $user = $this->userFromBearer($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user->verification_code = rand(100000, 999999);

        if ($user->email) {
            try {
                $user->notify(new AppEmailVerificationNotification());
            } catch (\Exception $e) {
            }
        } else {
            $otpController = new OTPVerificationController();
            $otpController->send_code($user);
        }

        $user->save();

        return response()->json([
            'result' => true,
            'message' => translate('Verification code is sent again'),
        ], 200);
    }

    protected function userFromBearer(Request $request): ?User
    {
        $bearer = $request->bearerToken();
        if (!$bearer) {
            return null;
        }

        $tokenable = PersonalAccessToken::findToken($bearer)?->tokenable;

        return $tokenable instanceof User ? $tokenable : null;
    }

    protected function formatUser(User $user): array
    {
        return [
            'id' => $user->id,
            'type' => $user->user_type,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'avatar_original' => uploaded_asset($user->avatar_original),
            'phone' => $user->phone,
            'email_verified' => $user->email_verified_at != null,
        ];
    }
}
