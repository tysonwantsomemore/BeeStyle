<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Services\MomoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MomoAtmPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected MomoService $momoService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->momoService = app(MomoService::class);
    }

    public function test_momo_atm_create_payment_request(): void
    {
        Http::fake([
            'https://test-payment.momo.vn/v2/gateway/api/create' => Http::response([
                'partnerCode' => 'MOMOBKUN20180529',
                'orderId'     => 'BEE-TEST-ATM_1234567890',
                'requestId'   => '1234567890',
                'amount'      => 500000,
                'resultCode'  => 0,
                'message'     => 'Thành công.',
                'payUrl'      => 'https://test-payment.momo.vn/v2/gateway/pay?token=test_token',
                'deeplink'    => 'momo://?action=payWithApp&token=test_token',
            ], 200),
        ]);

        /** @var User $user */
        $user = User::factory()->create();

        $order = Order::create([
            'order_code'       => 'BEE-20260919-TEST1',
            'user_id'          => $user->id,
            'customer_name'    => 'Nguyễn Văn Test',
            'customer_phone'   => '0968238770',
            'shipping_address' => '123 Đường Láng',
            'city'             => 'Hà Nội',
            'payment_method'   => 'momo',
            'payment_status'   => 'unpaid',
            'subtotal'         => 500000,
            'total_amount'     => 500000,
        ]);

        $result = $this->momoService->createPayment($order, 'payWithATM');

        $this->assertTrue($result['success']);
        $this->assertEquals('https://test-payment.momo.vn/v2/gateway/pay?token=test_token', $result['payUrl']);
    }

    public function test_momo_atm_result_callback_success_flow(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $order = Order::create([
            'order_code'       => 'BEE-20260919-MOM2',
            'user_id'          => $user->id,
            'customer_name'    => 'Lê Thị Thu',
            'customer_phone'   => '0987654321',
            'shipping_address' => '456 Phố Huế',
            'city'             => 'Hà Nội',
            'payment_method'   => 'momo',
            'payment_status'   => 'PENDING_PAYMENT',
            'subtotal'         => 350000,
            'total_amount'     => 350000,
        ]);

        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $orderId = $order->order_code . '_1710000000';
        $requestId = '1710000000';
        $amount = '350000';
        $orderInfo = 'Thanh toan don hang #' . $order->order_code . ' qua MoMo';
        $orderType = 'momo_wallet';
        $transId = '2893847291';
        $message = 'Giao dịch thành công.';
        $localMessage = 'Thành công';
        $responseTime = '1710000010';
        $errorCode = '0';
        $payType = 'napas';
        $extraData = '';

        $rawHash = "partnerCode=" . $partnerCode .
            "&accessKey=" . $accessKey .
            "&requestId=" . $requestId .
            "&amount=" . $amount .
            "&orderId=" . $orderId .
            "&orderInfo=" . $orderInfo .
            "&orderType=" . $orderType .
            "&transId=" . $transId .
            "&message=" . $message .
            "&localMessage=" . $localMessage .
            "&responseTime=" . $responseTime .
            "&errorCode=" . $errorCode .
            "&payType=" . $payType .
            "&extraData=" . $extraData;

        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $queryParams = [
            'partnerCode'  => $partnerCode,
            'accessKey'    => $accessKey,
            'orderId'      => $orderId,
            'requestId'    => $requestId,
            'amount'       => $amount,
            'orderInfo'    => $orderInfo,
            'orderType'    => $orderType,
            'transId'      => $transId,
            'message'      => $message,
            'localMessage' => $localMessage,
            'responseTime' => $responseTime,
            'errorCode'    => $errorCode,
            'payType'      => $payType,
            'extraData'    => $extraData,
            'signature'    => $signature,
        ];

        $response = $this->get(route('payment.momo.result', $queryParams));

        $response->assertStatus(200);
        $response->assertSee('GIAO DỊCH THÀNH CÔNG');
        $response->assertSee('Pass Checksum');
        $response->assertSee($transId);
        $response->assertSee('350.000đ');

        $this->assertEquals('paid', $order->fresh()->payment_status);
        $this->assertEquals($transId, $order->fresh()->momo_trans_id);
    }

    public function test_momo_atm_ipn_webhook_success_flow(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $order = Order::create([
            'order_code'       => 'BEE-20260919-IPN1',
            'user_id'          => $user->id,
            'customer_name'    => 'Hoàng Văn Cường',
            'customer_phone'   => '0911223344',
            'shipping_address' => '789 Trần Hưng Đạo',
            'city'             => 'Đà Nẵng',
            'payment_method'   => 'momo',
            'payment_status'   => 'PENDING_PAYMENT',
            'subtotal'         => 800000,
            'total_amount'     => 800000,
        ]);

        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $orderId = $order->order_code . '_1710000000';
        $requestId = '1710000000';
        $amount = '800000';
        $orderInfo = 'Thanh toan don hang qua MoMo';
        $orderType = 'momo_wallet';
        $transId = '999888777';
        $message = 'Success';
        $localMessage = 'Thành công';
        $responseTime = '1710000015';
        $errorCode = '0';
        $payType = 'napas';
        $extraData = '';

        $rawHash = "partnerCode=" . $partnerCode .
            "&accessKey=" . $accessKey .
            "&requestId=" . $requestId .
            "&amount=" . $amount .
            "&orderId=" . $orderId .
            "&orderInfo=" . $orderInfo .
            "&orderType=" . $orderType .
            "&transId=" . $transId .
            "&message=" . $message .
            "&localMessage=" . $localMessage .
            "&responseTime=" . $responseTime .
            "&errorCode=" . $errorCode .
            "&payType=" . $payType .
            "&extraData=" . $extraData;

        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $payload = [
            'partnerCode'  => $partnerCode,
            'accessKey'    => $accessKey,
            'orderId'      => $orderId,
            'requestId'    => $requestId,
            'amount'       => $amount,
            'orderInfo'    => $orderInfo,
            'orderType'    => $orderType,
            'transId'      => $transId,
            'message'      => $message,
            'localMessage' => $localMessage,
            'responseTime' => $responseTime,
            'errorCode'    => $errorCode,
            'payType'      => $payType,
            'extraData'    => $extraData,
            'signature'    => $signature,
        ];

        $response = $this->postJson(route('payments.momo.ipn'), $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'resultCode' => 0,
            'message'    => 'Received payment result success',
        ]);

        $this->assertEquals('paid', $order->fresh()->payment_status);
        $this->assertEquals($transId, $order->fresh()->momo_trans_id);
    }

    public function test_momo_atm_result_callback_user_cancelled_flow(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $order = Order::create([
            'order_code'       => 'BEE-20260919-CANCEL',
            'user_id'          => $user->id,
            'customer_name'    => 'Vũ Thị Hạnh',
            'customer_phone'   => '0933445566',
            'shipping_address' => '101 Cầu Giấy',
            'city'             => 'Hà Nội',
            'payment_method'   => 'momo',
            'payment_status'   => 'PENDING_PAYMENT',
            'shipping_status'  => 'pending',
            'subtotal'         => 420000,
            'total_amount'     => 420000,
        ]);

        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $orderId = $order->order_code . '_1710000000';
        $requestId = '1710000000';
        $amount = '420000';
        $orderInfo = 'Thanh toan don hang qua MoMo';
        $orderType = 'momo_wallet';
        $transId = '';
        $message = 'Giao dịch bị từ chối bởi người dùng.';
        $localMessage = 'Người dùng hủy giao dịch';
        $responseTime = '1710000020';
        $errorCode = '1006';
        $payType = 'napas';
        $extraData = '';

        $rawHash = "partnerCode=" . $partnerCode .
            "&accessKey=" . $accessKey .
            "&requestId=" . $requestId .
            "&amount=" . $amount .
            "&orderId=" . $orderId .
            "&orderInfo=" . $orderInfo .
            "&orderType=" . $orderType .
            "&transId=" . $transId .
            "&message=" . $message .
            "&localMessage=" . $localMessage .
            "&responseTime=" . $responseTime .
            "&errorCode=" . $errorCode .
            "&payType=" . $payType .
            "&extraData=" . $extraData;

        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $queryParams = [
            'partnerCode'  => $partnerCode,
            'accessKey'    => $accessKey,
            'orderId'      => $orderId,
            'requestId'    => $requestId,
            'amount'       => $amount,
            'orderInfo'    => $orderInfo,
            'orderType'    => $orderType,
            'transId'      => $transId,
            'message'      => $message,
            'localMessage' => $localMessage,
            'responseTime' => $responseTime,
            'errorCode'    => $errorCode,
            'resultCode'   => 1006,
            'payType'      => $payType,
            'extraData'    => $extraData,
            'signature'    => $signature,
        ];

        $response = $this->get(route('payment.momo.result', $queryParams));

        $response->assertStatus(200);
        $response->assertSee('GIAO DỊCH BỊ HỦY BỞI NGƯỜI DÙNG');
        $response->assertSee('Pass Checksum');

        $this->assertEquals('CANCELLED', $order->fresh()->payment_status);
        $this->assertEquals('cancelled', $order->fresh()->shipping_status);
    }

    public function test_momo_atm_result_callback_invalid_signature_flow(): void
    {
        $queryParams = [
            'partnerCode'  => 'MOMOBKUN20180529',
            'accessKey'    => 'klm05TvNBzhg7h7j',
            'orderId'      => 'BEE-FAKE-123_1710000000',
            'requestId'    => '1710000000',
            'amount'       => '500000',
            'orderInfo'    => 'Fake Order',
            'orderType'    => 'momo_wallet',
            'transId'      => '111222333',
            'message'      => 'Success',
            'localMessage' => 'Thành công',
            'responseTime' => '1710000030',
            'errorCode'    => '0',
            'payType'      => 'napas',
            'extraData'    => '',
            'signature'    => 'fake_invalid_tampered_signature_string',
        ];

        $response = $this->get(route('payment.momo.result', $queryParams));

        $response->assertStatus(200);
        $response->assertSee('LỖI XÁC THỰC CHỮ KÝ');
        $response->assertSee('Fail Checksum');
    }

    public function test_momo_atm_query_transaction_flow(): void
    {
        Http::fake([
            'https://test-payment.momo.vn/v2/gateway/api/query' => Http::response([
                'partnerCode'  => 'MOMOBKUN20180529',
                'orderId'      => 'BEE-20260919-TESTQ_1710000000',
                'requestId'    => '1710000000',
                'amount'       => 600000,
                'resultCode'   => 0,
                'errorCode'    => 0,
                'message'      => 'Giao dịch thành công.',
                'localMessage' => 'Thành công',
                'transId'      => '555666777',
                'payType'      => 'napas',
                'extraData'    => '',
            ], 200),
        ]);

        $queryResult = $this->momoService->queryTransaction('BEE-20260919-TESTQ_1710000000');
        $this->assertTrue($queryResult['success']);
        $this->assertEquals(0, $queryResult['data']['resultCode']);
        $this->assertEquals('555666777', $queryResult['data']['transId']);

        // Test view GET & POST query
        $viewResponse = $this->get(route('payment.momo.query', ['orderId' => 'BEE-20260919-TESTQ_1710000000']));
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('Kiểm Tra Trạng Thái Giao Dịch MoMo');

        $postResponse = $this->post(route('payment.momo.query.submit'), [
            'orderId' => 'BEE-20260919-TESTQ_1710000000'
        ]);
        $postResponse->assertStatus(200);
        $postResponse->assertSee('555666777');
    }
}
