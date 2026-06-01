<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class AdminActivityLogger
{
    public function log(string $action, ?string $description = null, ?User $admin = null, ?Request $request = null): void
    {
        $request ??= request();
        $admin ??= $request->user();

        if (! $admin instanceof User || $admin->role !== User::ROLE_ADMIN) {
            return;
        }

        try {
            $ipService = app(ClientIpService::class);

            $admin->adminActivityLogs()->create([
                'action' => $action,
                'description' => $description,
                'ip_address' => $ipService->ip($request),
                'user_agent' => $ipService->userAgent($request),
                'created_at' => now(),
            ]);
        } catch (Throwable $exception) {
            Log::warning('Admin activity log could not be written.', [
                'user_id' => $admin->id,
                'action' => $action,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
