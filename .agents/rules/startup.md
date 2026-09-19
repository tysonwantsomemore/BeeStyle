# Development & Startup Rules

1. **Application Framework:** Laravel 11/12 (PHP 8.2+).
2. **Standard Dev Command:** Always run `php artisan serve` on port 8000 (`http://127.0.0.1:8000`).
3. **Prohibited Actions:** Never run PHP built-in static server `php -S` on the root workspace folder, as it bypasses the Laravel router and Blade rendering engine.
4. **Trigger Commands:** Whenever the user says "chạy đi", "start", "run", "dev", or "khởi động", strictly run `php artisan serve`.
