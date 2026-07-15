<?php

namespace App\Http\Controllers\Api\V2;

class GeneralSettingController extends Controller
{
    public function index()
    {
        return response()->json([
            'result' => true,
            'data' => [
                'logo' => uploaded_asset(get_setting('header_logo')) ?? '',
                'site_name' => get_setting('site_name', ''),
                'address' => get_setting('address', ''),
                'description' => get_setting('description', ''),
                'phone' => get_setting('phone', ''),
                'email' => get_setting('email', ''),
                'facebook' => get_setting('facebook', ''),
                'twitter' => get_setting('twitter', ''),
                'instagram' => get_setting('instagram', ''),
                'youtube' => get_setting('youtube', ''),
                'google_plus' => get_setting('google_plus', ''),
            ],
        ]);
    }
}
