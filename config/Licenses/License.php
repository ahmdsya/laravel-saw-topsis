<?php

namespace Config\Licenses;

use Illuminate\Support\Facades\Http;

class Lincense {

    public function isHaveAccess()
    {

        $hasAccess = false;

        $licenseCode = env("LICENCSE_CODE");
        if(!$licenseCode){
            return false;
            exit;
        }
        
        $response = Http::post('https://license-project.syrif.my.id/api/get_license', [
            'licenseCode' => $licenseCode
        ]);

        $jsonData = $response->json();

        $success = $jsonData['data']['success'];
        $message = $jsonData['data']['message'];

        $data = $jsonData['data']['data'];

        if($success && $message == "OK"){
            if($data['IsActive'] == "Y"){
                $hasAccess = true;
            }
        }

        return $hasAccess;
    }

    public function isConnected()
    {
        $is_conn = false;

        $connected = @fsockopen("www.example.com", 80); 
        if ($connected){
            $is_conn = true;
            fclose($connected);
        }

        return $is_conn;
    }

}
