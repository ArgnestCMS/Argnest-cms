<?php

namespace App\Services;

use Illuminate\Http\Request;

class ClientIpService
{
    public function ip(?Request $request = null): ?string
    {
        $request ??= request();

        return $this->firstValidIp($request->header('CF-Connecting-IP'))
            ?? $this->firstValidIp($request->header('X-Forwarded-For'))
            ?? $this->firstValidIp($request->header('X-Real-IP'))
            ?? $request->ip();
    }

    public function userAgent(?Request $request = null): ?string
    {
        $request ??= request();

        return $request->userAgent();
    }

    public function userAgentInfo(?Request $request = null): array
    {
        $userAgent = $this->userAgent($request);

        return [
            'user_agent' => $userAgent,
            'browser' => $this->browser($userAgent),
            'platform' => $this->platform($userAgent),
        ];
    }

    public function browser(?string $userAgent): ?string
    {
        if (blank($userAgent)) {
            return null;
        }

        return match (true) {
            str_contains($userAgent, 'Edg/') => 'Edge',
            str_contains($userAgent, 'OPR/') || str_contains($userAgent, 'Opera') => 'Opera',
            str_contains($userAgent, 'Chrome/') && ! str_contains($userAgent, 'Chromium') => 'Chrome',
            str_contains($userAgent, 'Firefox/') => 'Firefox',
            str_contains($userAgent, 'Safari/') && str_contains($userAgent, 'Version/') => 'Safari',
            str_contains($userAgent, 'MSIE') || str_contains($userAgent, 'Trident/') => 'Internet Explorer',
            default => 'Bilinmiyor',
        };
    }

    public function platform(?string $userAgent): ?string
    {
        if (blank($userAgent)) {
            return null;
        }

        return match (true) {
            str_contains($userAgent, 'Windows') => 'Windows',
            str_contains($userAgent, 'Android') => 'Android',
            str_contains($userAgent, 'iPhone') || str_contains($userAgent, 'iPad') => 'iOS',
            str_contains($userAgent, 'Macintosh') || str_contains($userAgent, 'Mac OS X') => 'macOS',
            str_contains($userAgent, 'Linux') => 'Linux',
            default => 'Bilinmiyor',
        };
    }

    private function firstValidIp(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $ip = trim(explode(',', $value)[0]);

        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : null;
    }
}
