<?php

return [
    'partner_code' => env('MOMO_PARTNER_CODE', 'MOMOBKUN20180529'),
    'access_key' => env('MOMO_ACCESS_KEY', 'klm05TvNBzhg7h7j'),
    'secret_key' => env('MOMO_SECRET_KEY', 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa'),
    'api_endpoint' => env('MOMO_API_ENDPOINT', env('MOMO_ENDPOINT', 'https://test-payment.momo.vn/v2/gateway/api/create')),
    'redirect_url' => env('MOMO_REDIRECT_URL', 'http://127.0.0.1:8000/payment/momo/result'),
    'ipn_url' => env('MOMO_IPN_URL', 'http://127.0.0.1:8000/api/payments/momo/ipn'),
];