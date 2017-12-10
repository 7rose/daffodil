<?php 

return [
    /*
    |--------------------------------------------------------------------------
    | Wechat Enterprise
    |--------------------------------------------------------------------------
    |
    */
    'enterprise' => [
        'corp_id'      =>env('WECHAT_ENTERPRISE_CORP_ID',false),
        'corp_secret'  =>env('WECHAT_ENTERPRISE_CORP_SECRET',false),
        'agent_id'     =>env('WECHAT_ENTERPRISE_AGENT_ID',0),
        'token'        =>env('WECHAT_ENTERPRISE_TOKEN',false),
        'AESkey'       =>env('WECHAT_ENTERPRISE_AESKEY',false),
        'callback_url' =>env('WECHAT_ENTERPRISE_CALLBACK_URL',false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Wechat  Public
    |--------------------------------------------------------------------------
    |
    */
    'public' => [
        'account'      =>env('WECHAT_ACCOUNT',false),   
        'app_id'       =>env('WECHAT_APP_ID',false),
        'app_secret'   =>env('WECHAT_APP_SECRET',false),
        'token'        =>env('WECHAT_TOKEN',false),
        'AESkey'       =>env('WECHAT_AESKEY',false),
        'callback_url' =>env('WECHAT_CALLBACK_URL',false),
    ],

];