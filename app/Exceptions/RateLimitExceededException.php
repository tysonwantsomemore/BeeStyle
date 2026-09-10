<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RateLimitExceededException extends Exception
{
    protected int $retryAfterSeconds;

    public function __construct(string $message = 'Bạn đã thử quá số lần quy định. Vui lòng thử lại sau!', int $retryAfterSeconds = 900)
    {
        parent::__construct($message);
        $this->retryAfterSeconds = $retryAfterSeconds;
    }

    public function getRetryAfterSeconds(): int
    {
        return $this->retryAfterSeconds;
    }

    public function render(Request $request): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        if ($request->expectsJson() || $request->is('api/*') || $request->ajax()) {
            return response()->json([
                'success'             => false,
                'message'             => $this->getMessage(),
                'retry_after_seconds' => $this->retryAfterSeconds,
                'code'                => 'RATE_LIMIT_EXCEEDED',
            ], 429);
        }

        return back()->with('error', $this->getMessage());
    }
}
