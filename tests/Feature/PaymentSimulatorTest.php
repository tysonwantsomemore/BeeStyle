<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentSimulatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_online_payment_simulator_flows(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $order = Order::create([
            'order_code'       => 'BEE-20260918-ONL1',
            'user_id'          => $user->id,
            'customer_name'    => 'Nguyễn Văn An',
            'customer_phone'   => '0987654321',
            'shipping_address' => 'Số 88 Lê Lợi',
            'city'             => 'Hà Nội',
            'payment_method'   => 'online',
            'payment_status'   => 'unpaid',
            'subtotal'         => 500000,
            'total_amount'     => 500000,
        ]);

        // 1. Xem cổng giả lập thanh toán
        $response = $this->actingAs($user)->get(route('client.checkout.online', $order->order_code));
        $response->assertStatus(200);
        $response->assertSee('Online Sandbox Simulator');

        // 2. Giả lập thanh toán thất bại
        $failResponse = $this->actingAs($user)->post(route('client.checkout.online.failed', $order->order_code));
        $failResponse->assertRedirect(route('client.checkout.online', $order->order_code));
        $this->assertEquals('PAYMENT_FAILED', $order->fresh()->payment_status);

        // 3. Giả lập thanh toán thành công
        $successResponse = $this->actingAs($user)->post(route('client.checkout.online.success', $order->order_code));
        $successResponse->assertRedirect(route('client.home'));
        $this->assertEquals('paid', $order->fresh()->payment_status);
        $this->assertEquals('processing', $order->fresh()->shipping_status);
    }

    public function test_momo_developer_simulator_flows(): void
    {
        \Illuminate\Support\Facades\Http::fake([
            'https://test-payment.momo.vn/v2/gateway/api/create' => \Illuminate\Support\Facades\Http::response([
                'partnerCode' => 'MOMOBKUN20180529',
                'orderId'     => 'BEE-20260918-MOM1_1710000000',
                'requestId'   => '1710000000',
                'amount'      => 750000,
                'resultCode'  => 0,
                'message'     => 'Thành công.',
                'payUrl'      => 'https://test-payment.momo.vn/v2/gateway/pay?token=test_token_123',
            ], 200),
        ]);

        /** @var User $user */
        $user = User::factory()->create();

        $order = Order::create([
            'order_code'       => 'BEE-20260918-MOM1',
            'user_id'          => $user->id,
            'customer_name'    => 'Trần Văn Bình',
            'customer_phone'   => '0912345678',
            'shipping_address' => 'Số 99 Nguyễn Huệ',
            'city'             => 'Hồ Chí Minh',
            'payment_method'   => 'momo',
            'payment_status'   => 'unpaid',
            'subtotal'         => 750000,
            'total_amount'     => 750000,
        ]);

        // 1. Chuyển hướng sang Cổng MoMo Sandbox Gateway chính thức
        $response = $this->actingAs($user)->get(route('client.checkout.momo', $order->order_code));
        $response->assertRedirect('https://test-payment.momo.vn/v2/gateway/pay?token=test_token_123');

        // 2. Xác nhận MoMo thành công
        $successResponse = $this->actingAs($user)->post(route('client.checkout.momo.success', $order->order_code));
        $successResponse->assertRedirect(route('client.home'));
        $this->assertEquals('paid', $order->fresh()->payment_status);
    }
}
