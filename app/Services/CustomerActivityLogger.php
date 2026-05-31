<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class CustomerActivityLogger
{
    public function log(User $user, string $action, ?string $description = null, ?Request $request = null): void
    {
        try {
            $user->customerActivityLogs()->create([
                'action' => $action,
                'description' => $description,
                'ip_address' => $request?->ip(),
                'user_agent' => $request?->userAgent(),
                'created_at' => now(),
            ]);
        } catch (Throwable $exception) {
            Log::warning('Customer activity log could not be written.', [
                'user_id' => $user->id,
                'action' => $action,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    public function logForRequest(string $action, ?string $description = null, ?Request $request = null): void
    {
        $request ??= request();
        $user = $request->user();

        if ($user instanceof User) {
            $this->log($user, $action, $description, $request);
        }
    }
}
