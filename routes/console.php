<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Hiển thị câu danh ngôn truyền cảm hứng');

Schedule::command('orders:auto-complete')->daily()->runInBackground();
