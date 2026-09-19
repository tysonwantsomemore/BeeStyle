<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserAddress;
use App\Services\Address\AddressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressWithoutDistrictTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_address_without_district(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('client.profile.address.store'), [
            'receiver_name'    => 'Nguyễn Văn An',
            'receiver_phone'   => '0987654321',
            'detailed_address' => 'Số 123 Đường Cầu Giấy',
            'province_name'    => 'Hà Nội',
            'ward_name'        => 'Dịch Vọng',
            'is_default'       => 1,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('user_addresses', [
            'user_id'        => $user->id,
            'recipient_name' => 'Nguyễn Văn An',
            'phone'          => '0987654321',
            'address'        => 'Số 123 Đường Cầu Giấy',
            'city'           => 'Hà Nội',
            'ward'           => 'Dịch Vọng',
        ]);

        $address = UserAddress::where('user_id', $user->id)->first();
        $this->assertNotNull($address);
        $this->assertEquals('Số 123 Đường Cầu Giấy, Dịch Vọng, Hà Nội', $address->full_address);
    }

    public function test_address_snapshot_without_district(): void
    {
        /** @var AddressService $addressService */
        $addressService = app(AddressService::class);

        $snapshot = $addressService->createOrderAddressSnapshot([
            'customer_name'    => 'Trần Thị Mai',
            'customer_phone'   => '0912345678',
            'shipping_address' => 'Số 456 Nguyễn Huệ',
            'ward'             => 'Bến Nghé',
            'city'             => 'Hồ Chí Minh',
        ]);

        $this->assertEquals('Số 456 Nguyễn Huệ, Bến Nghé, Hồ Chí Minh', $snapshot['full_address']);
        $this->assertEquals('', $snapshot['district']);
    }
}
