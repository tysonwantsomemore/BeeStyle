<?php

return [
    'tmn_code' => env('VNP_TMN_CODE', 'NWCCIJDI'),
    'hash_secret' => env('VNP_HASH_SECRET', 'ZXPMMSHUMXCWBQOUSTJRBNFAWYIOJRQO'),
    'url' => env('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'api_url' => env('VNP_API_URL', 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction'),
    'return_url' => env('VNP_RETURN_URL', 'http://127.0.0.1:8000/thanh-toan/vnpay/callback'),
    'currency' => 'VND',
    'locale' => 'vn',
    'version' => '2.1.0',
    'expire_minutes' => 15,
];
