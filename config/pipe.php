<?php 

return [
    /*
    |--------------------------------------------------------------------------
    | Base Info
    |--------------------------------------------------------------------------
    |
    */
    'info' => [
        'org_cn'  =>env('CUSTOM_ORG_CN',false),
        'org_en'  =>env('CUSTOM_ORG_EN',false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Configrations for RestRose\Pipe\Auth
    |--------------------------------------------------------------------------
    |
    */
    'org_extra' => 
    [
        'shop' => 
        [
            'enabled' => true,
            'staff_hold' => false,
            'staff_use' => true,
        ],

        'supplier' => 
        [
            'enabled' => true,
            'staff_hold' => false,
            'staff_use' => true,
        ],

        'customer' => 
        [
            'enabled' => true,
            'staff_hold' => false,
            'staff_use' => true,
        ],
    ],

];