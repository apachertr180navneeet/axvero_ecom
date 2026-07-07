<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\OTPVerificationController;
use App\Utility\VerifyUtility;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend', 'verifyCode');
    }

    /**
     * Show the email verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if ($request->user()->email != null) {
            return $request->user()->hasVerifiedEmail()
                            ? redirect($this->redirectPath())
                            : view('auth.'.get_setting('authentication_layout_select').'.verify_email');
        }
        else {
            $otpController = new OTPVerificationController;
            $otpController->send_code($request->user());
            return redirect()->route('verification');
        }
    }

    protected function verificationResultView(string $type)
    {
        return view('auth.'.get_setting('authentication_layout_select').'.verify_email_result', compact('type'));
    }

    /**
     * Verify the email using a 6-digit OTP code.
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|digits:6',
        ]);

        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return $this->verificationResultView('success');
        }

        if ((string) $user->verification_code === (string) $request->verification_code) {
            $user->email_verified_at = Carbon::now();
            $user->verification_code = null;
            $user->save();
            offerUserWelcomeCoupon();

            return $this->verificationResultView('success');
        }

        return $this->verificationResultView('failed');
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        try {
            VerifyUtility::sendVerificationCode(
                $request->user(),
                $request->user()->user_type == 'seller' ? 'seller' : 'customer'
            );
        } catch (\Exception $e) {
            flash(translate('Something went wrong. Please try again later.'))->error();
            return back();
        }

        return back()->with('resent', true);
    }

    public function verification_confirmation($code){
        $user = User::where('verification_code', $code)->first();

        if ($user == null) {
            try {
                $user = User::find(decrypt($code));
            } catch (\Exception $e) {
                $user = null;
            }
        }

        if($user != null){
            $user->email_verified_at = Carbon::now();
            $user->verification_code = null;
            $user->save();
            auth()->login($user, true);
            offerUserWelcomeCoupon();

            return $this->verificationResultView('success');
        }

        if (auth()->check()) {
            return $this->verificationResultView('failed');
        }

        flash(translate('Sorry, we could not verifiy you. Please try again'))->error();
        return redirect()->route('user.login');
    }
}
