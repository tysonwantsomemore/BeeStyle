<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidAgeRange implements ValidationRule
{
    protected int $minAge;
    protected int $maxAge;

    public function __construct(int $minAge = 10, int $maxAge = 100)
    {
        $this->minAge = $minAge;
        $this->maxAge = $maxAge;
    }

    /**
     * Xác thực độ tuổi người dùng:
     * - Phải là ngày hợp lệ trong quá khứ (before:today)
     * - Tuổi phải từ $minAge đến $maxAge (mặc định: 10 - 100 tuổi)
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        try {
            $dob = new \DateTimeImmutable((string) $value);
            $dob = $dob->setTime(0, 0, 0);
        } catch (\Throwable $e) {
            $fail(':attribute không đúng định dạng ngày tháng hợp lệ.');
            return;
        }

        $today = new \DateTimeImmutable('today');

        if ($dob >= $today) {
            $fail(':attribute phải là một ngày trong quá khứ (trước ngày hôm nay).');
            return;
        }

        $diff = $today->diff($dob);
        $age = $diff->y;

        if ($age < $this->minAge) {
            $fail("Độ tuổi của bạn chưa đủ điều kiện (yêu cầu tối thiểu từ {$this->minAge} tuổi trở lên).");
            return;
        }

        if ($age > $this->maxAge) {
            $fail("Độ tuổi không hợp lệ (vượt quá {$this->maxAge} tuổi).");
        }
    }
}
