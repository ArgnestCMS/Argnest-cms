<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LiveChatMessage;
use App\Models\LiveChatSession;
use App\Models\SiteSetting;
use App\Services\ClientIpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LiveChatController extends Controller
{
    public function start(Request $request): JsonResponse
    {
        if (! $this->isLiveChatEnabled()) {
            return response()->json([
                'message' => 'Canlı destek şu anda aktif değil.',
            ], 423);
        }

        $validated = $request->validate([
            'visitor_name' => ['nullable', 'string', 'max:255'],
            'visitor_email' => ['nullable', 'email', 'max:255'],
            'visitor_phone' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $session = LiveChatSession::query()->create([
            'visitor_name' => $validated['visitor_name'] ?? null,
            'visitor_email' => $validated['visitor_email'] ?? null,
            'visitor_phone' => $validated['visitor_phone'] ?? null,
            'ip_address' => app(ClientIpService::class)->ip($request),
            'user_agent' => app(ClientIpService::class)->userAgent($request),
            'status' => LiveChatSession::STATUS_OPEN,
        ]);

        $session->messages()->create([
            'sender_type' => LiveChatMessage::SENDER_VISITOR,
            'message' => $validated['message'],
            'created_at' => now(),
        ]);
        $this->touchCustomerContact($session);
        $session->touch();

        $previousSessionId = $request->session()->get('live_chat_current_session_id');

        if ($previousSessionId) {
            $request->session()->forget('live_chat_sessions.' . $previousSessionId);
        }

        $request->session()->put($this->sessionKey($session), true);
        $request->session()->put('live_chat_current_session_id', $session->id);

        return response()->json([
            'session_id' => $session->id,
            'status' => $session->status,
            'messages' => $this->messagesPayload($session),
        ]);
    }

    public function messages(Request $request, LiveChatSession $session): JsonResponse
    {
        abort_unless($this->canAccess($request, $session), 403);

        return response()->json([
            'session_id' => $session->id,
            'status' => $session->status,
            'messages' => $this->messagesPayload($session),
        ]);
    }

    public function sendMessage(Request $request, LiveChatSession $session): JsonResponse
    {
        if (! $this->isLiveChatEnabled()) {
            return response()->json([
                'message' => 'Canlı destek şu anda aktif değil.',
            ], 423);
        }

        abort_unless($this->canAccess($request, $session), 403);
        abort_if($session->status === LiveChatSession::STATUS_CLOSED, 422, 'Bu sohbet kapatilmis.');

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $session->messages()->create([
            'sender_type' => LiveChatMessage::SENDER_VISITOR,
            'message' => $validated['message'],
            'created_at' => now(),
        ]);
        $this->touchCustomerContact($session);

        if ($session->status === LiveChatSession::STATUS_ANSWERED) {
            $session->forceFill(['status' => LiveChatSession::STATUS_OPEN])->save();
        } else {
            $session->touch();
        }

        return response()->json([
            'session_id' => $session->id,
            'status' => $session->status,
            'messages' => $this->messagesPayload($session),
        ]);
    }

    public function close(Request $request, LiveChatSession $session): JsonResponse
    {
        abort_unless($this->canAccess($request, $session), 403);

        if ($session->status !== LiveChatSession::STATUS_CLOSED) {
            $session->messages()->create([
                'sender_type' => LiveChatMessage::SENDER_SYSTEM,
                'message' => 'Müşteri görüşmeyi sonlandırdı.',
                'created_at' => now(),
            ]);

            $session->forceFill([
                'status' => LiveChatSession::STATUS_CLOSED,
                'closed_at' => now(),
            ])->save();
        }

        return response()->json([
            'session_id' => $session->id,
            'status' => $session->status,
            'messages' => $this->messagesPayload($session->refresh()),
        ]);
    }

    private function canAccess(Request $request, LiveChatSession $session): bool
    {
        return $request->session()->has($this->sessionKey($session));
    }

    private function isLiveChatEnabled(): bool
    {
        return (bool) SiteSetting::query()->value('live_chat_enabled');
    }

    private function touchCustomerContact(LiveChatSession $session): void
    {
        if (blank($session->visitor_email)) {
            return;
        }

        \App\Models\User::query()
            ->where('role', \App\Models\User::ROLE_CUSTOMER)
            ->where('email', $session->visitor_email)
            ->first()
            ?->forceFill(['last_contact_at' => now()])
            ->save();
    }

    private function sessionKey(LiveChatSession $session): string
    {
        return 'live_chat_sessions.' . $session->id;
    }

    private function messagesPayload(LiveChatSession $session): array
    {
        return $session->messages()
            ->with('sender')
            ->oldest('created_at')
            ->get()
            ->map(fn (LiveChatMessage $message): array => [
                'id' => $message->id,
                'sender_type' => $message->sender_type,
                'sender_name' => match ($message->sender_type) {
                    LiveChatMessage::SENDER_ADMIN => $message->sender?->name ?: 'Destek Ekibi',
                    LiveChatMessage::SENDER_SYSTEM => 'Sistem',
                    default => $session->visitor_name ?: 'Ziyaretci',
                },
                'message' => $message->message,
                'created_at' => $message->created_at?->timezone('Europe/Istanbul')->format('d.m.Y H:i'),
            ])
            ->all();
    }
}
