<?php

namespace App\Utility;

use App\Mail\MailManager;
use App\Models\EmailTemplate;
use App\Models\User;
use Mail;

class VerifyUtility
{
    /**
     * Send a 6-digit email verification code for the verify page OTP flow.
     */
    public static function sendVerificationCode(User $user, string $userType = 'customer'): string
    {
        $verification_code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->verification_code = $verification_code;
        $user->save();

        $emailIdentifier = 'email_verification_for_registration_' . $userType;
        $emailTemplate = EmailTemplate::whereIdentifier($emailIdentifier)->first();

        if ($emailTemplate == null) {
            $emailIdentifier = 'email_verification_for_registration_customer';
            $emailTemplate = EmailTemplate::whereIdentifier($emailIdentifier)->first();
        }

        $emailSubject = $emailTemplate->subject;
        $emailSubject = str_replace('[[store_name]]', get_setting('site_name'), $emailSubject);

        $emailBody = $emailTemplate->default_text;
        $emailBody = str_replace('[[store_name]]', get_setting('site_name'), $emailBody);
        $emailBody = str_replace('[[code]]', $verification_code, $emailBody);
        $emailBody = str_replace('[[admin_email]]', get_admin()->email, $emailBody);

        $array['subject'] = $emailSubject;
        $array['content'] = $emailBody;

        Mail::to($user->email)->send(new MailManager($array));

        return $verification_code;
    }
}
