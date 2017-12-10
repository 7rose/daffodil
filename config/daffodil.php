<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Error Reporting
    |--------------------------------------------------------------------------
    |
    | * Error reporting can help us make this APP better & stronger!
    |   错误报告可以帮助我们把这个应用改进得更安全,好用!
    |
    | * Error reporting will NEVER inclue your personal data! 
    |   错误报告 ** 绝对不会 ** 收集您的任何个人信息和数据!
    |
    | * This module is shutdown by default, but if you allow to turn it on,
    |   we are so thankful for you!
    |   错误报告默认是关闭的,如果您愿意启用,我们衷心感谢!
    |
    */
    'error_reporting' =>env('SERVICE_ERROR_REPORTING',false),

    /*
    |--------------------------------------------------------------------------
    | Auto-Update
    |--------------------------------------------------------------------------
    |
    | * Auto-Update will be great helpful to the secure & experience of this APP. 
    |   自动更新将对这个应用的安全性和使用体验带来很大帮助
    |
    | * We will be very careful to improve the code of this APP, but we CAN NOT 
    |   provide any guarante for the updating, even the chance is very rare.
    |   我们将谨慎并专业地改进这个应用, 但我们不为更新应用可能产生的使用问题提供绝对保证,
    |   尽管影响使用的概率非常低
    |
    | * Auto-Update service is Free in your APP nomal serve time.
    |   自动更新在您应用正常服务期是免费的
    |  
    */
    'auto_update' =>env('SERVICE_AUTO_UPDATE',false),

    /*
    |--------------------------------------------------------------------------
    | Key & Service Options
    |--------------------------------------------------------------------------
    |
    | Authorized Key. Any problem, please contact with RestRose AI Systems co.,ltd
    | 
    | hi@restrose.net
    |
    */
    'key' =>env('SERVICE_KEY',null),
    'service' =>env('SERVICE_URL','restrose.org'),
    'service_ssl' =>env('SERVICE_SSL',true),
    'service_path' =>env('SERVICE_PATH',false),

    /*
    |--------------------------------------------------------------------------
    | Server Options
    |--------------------------------------------------------------------------
    |
    | SSL(HTTPS) options of the server: true - enabled, false - disabled
    |
    */
    'ssl' =>env('SERVER_SSL',true),

    /*
    |--------------------------------------------------------------------------
    | Alidayu SMS
    |--------------------------------------------------------------------------
    |
    | Alidayu signature, get from alidayu.com
    |
    */
    'sms_signature' =>env('ALISMS_SIGNATURE',null),

    /*
    |--------------------------------------------------------------------------
    | Custom
    |--------------------------------------------------------------------------
    |
    | Custom settings of company or orgnization
    |
    */
    'max_staff'     =>env('CUSTOM_MAX_STAFF',50),
    'staff_sn_from' =>env('CUSTOM_STAFF_SN_FROM',1),

];