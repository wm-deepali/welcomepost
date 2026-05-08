<?php

return [
    //These are for the Marketplace
    'appID' => '653339e21ba4effb7c05fb6026933356',
    'secretKey' => 'cfsk_ma_prod_19437a7566caa240908f733896b099e3_6f6d2b91',
    'testURL' => 'https://ces-gamma.cashfree.com',
    'prodURL' => 'https://ces-api.cashfree.com',
    'maxReturn' => 100, //this is for request pagination
    'isLive' => true,

    //For the PaymentGateway.
    'PG' => [
        'appID' => '653339e21ba4effb7c05fb6026933356',
        'secretKey' => 'cfsk_ma_prod_19437a7566caa240908f733896b099e3_6f6d2b91',
        'testURL' => 'https://test.cashfree.com',
        'prodURL' => 'https://api.cashfree.com',
        'isLive' => true,
    ]
];
