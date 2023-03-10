<?php

namespace App\Http\Controllers;

use Illuminate\Filesystem\Filesystem;

ini_set('max_execution_time', 180);

use App\CentralLogics\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class UpdateController extends Controller
{
    public function update_software_index()
    {
        return view('update.update-software');
    }

    public function update_software(Request $request)
    {
        if (env('SOFTWARE_VERSION') == '1.0') {
            $filesystem = new Filesystem;
            $filesystem->cleanDirectory('database/migrations');
        }

        Helpers::setEnvironmentValue('BUYER_USERNAME', $request['username']);
        Helpers::setEnvironmentValue('PURCHASE_CODE', $request['purchase_key']);
        Helpers::setEnvironmentValue('APP_MODE', 'live');
        Helpers::setEnvironmentValue('SOFTWARE_VERSION', '1.4.1');
        Helpers::setEnvironmentValue('APP_NAME', '6amMart' . time());

        Artisan::call('migrate', ['--force' => true]);
        $previousRouteServiceProvier = base_path('app/Providers/RouteServiceProvider.php');
        $newRouteServiceProvier = base_path('app/Providers/RouteServiceProvider.txt');
        copy($newRouteServiceProvier, $previousRouteServiceProvier);
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Helpers::insert_business_settings_key(array("mobile_app_section_heading" => "Download the App for Enjoy Best Restaurant Test"));
        Helpers::insert_business_settings_key(array("mobile_app_section_text" => "Default Text Mobile App Section"));
        Helpers::insert_business_settings_key(array("feature_section_description" => "Feature section description"));
        Helpers::insert_business_settings_key("Feature section description", json_encode([
            "app_url_android_status" => "0",
            "app_url_android" => "https://play.google.com",
            "app_url_ios_status" => "0",
            "app_url_ios" => "https://www.apple.com/app-store",
            "web_app_url_status" => "0",
            "web_app_url" => "https://stackfood.6amtech.com/"
        ]));

        return redirect('/admin/auth/login');
    }
}
