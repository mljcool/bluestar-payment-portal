<?php

namespace App\Http\Controllers;

class KeySettings
{
    private function getENVByKeys()
    {
        return [
            'TEST_MODE'=> [
                'profile_id'=> env('TEST_MODE_ID', ''),
                'api_key'=>env('TEST_API_PAYTABS', ''),
            ],
            'LIVE_MODE'=> [
                'profile_id'=> env('LIVE_MODE_ID', ''),
                'api_key'=>env('LIVE_API_PAYTABS', ''),
            ]
        ];
    }
 

    public function getPayTabsKeys($keys = 'TEST_MODE')
    {
        if (env('FORCE_LIVE', false)) {
            return json_decode(json_encode($this->getENVByKeys()['LIVE_MODE']));
        } else {
            return json_decode(json_encode($this->getENVByKeys()[$keys]));
        }
    }
}
