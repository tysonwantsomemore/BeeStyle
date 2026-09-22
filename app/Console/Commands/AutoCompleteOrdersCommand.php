<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class AutoCompleteOrdersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:auto-complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động hoàn tất các đơn hàng đã giao (delivered) quá 7 ngày mà khách hàng không có khiếu nại hoặc yêu cầu đổi trả';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Đang quét các đơn hàng đã giao thành công quá 7 ngày...');
        
        $count = Order::autoCompleteEligibleDeliveredOrders();

        if ($count > 0) {
            $this->info("Đã tự động chuyển đổi thành công {$count} đơn hàng sang trạng thái HOÀN TẤT.");
        } else {
            $this->info('Không có đơn hàng nào thỏa mãn điều kiện hoàn tất tự động sau 7 ngày.');
        }

        return Command::SUCCESS;
    }
}
