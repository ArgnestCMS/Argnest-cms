<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use App\Models\LiveChatMessage;
use App\Models\LiveChatSession;
use App\Models\User;
use App\Services\AdminActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LiveChatPopupController extends Controller
{
    public function sessions(Request $request): JsonResponse
    {
        $this->authorizePermission($request->user(), 'live_chat_view');

        $sessions = LiveChatSession::query()
            ->with(['assignedUser'])
            ->withCount('messages')
            ->whereIn('status', [LiveChatSession::STATUS_OPEN, LiveChatSession::STATUS_ANSWERED])
            ->latest('updated_at')
            ->limit(30)
            ->get();

        $attentionCount = $sessions
            ->filter(fn (LiveChatSession $session): bool => $this->needsAttention($session))
            ->count();

        return response()->json([
            'active_count' => $sessions->count(),
            'attention_count' => $attentionCount,
            'sessions' => $sessions
                ->map(fn (LiveChatSession $session): array => $this->sessionPayload($session))
                ->all(),
        ]);
    }

    public function show(Request $request, LiveChatSession $session): JsonResponse
    {
        $this->authorizePermission($request->user(), 'live_chat_view');

        return response()->json([
            'session' => $this->sessionPayload($session),
            'messages' => $this->messagesPayload($session),
        ]);
    }

    public function reply(Request $request, LiveChatSession $session): JsonResponse
    {
        $this->authorizePermission($request->user(), 'live_chat_reply');
        abort_if($session->status === LiveChatSession::STATUS_CLOSED, 422, 'Bu sohbet kapatilmis.');

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $session->messages()->create([
            'sender_type' => LiveChatMessage::SENDER_ADMIN,
            'sender_id' => $request->user()->id,
            'message' => $validated['message'],
            'created_at' => now(),
        ]);

        $session->forceFill([
            'status' => LiveChatSession::STATUS_ANSWERED,
            'assigned_user_id' => $session->assigned_user_id ?: $request->user()->id,
        ])->save();
        $this->touchCustomerContact($session);

        app(AdminActivityLogger::class)->log(
            AdminActivityLog::ACTION_LIVE_CHAT_REPLIED,
            'Canli destek cevaplandi: #' . $session->id,
            $request->user(),
            $request,
        );

        return $this->show($request, $session->refresh());
    }

    public function close(Request $request, LiveChatSession $session): JsonResponse
    {
        $this->authorizePermission($request->user(), 'live_chat_close');

        if ($session->status !== LiveChatSession::STATUS_CLOSED) {
            $session->forceFill([
                'status' => LiveChatSession::STATUS_CLOSED,
                'closed_at' => now(),
                'assigned_user_id' => $session->assigned_user_id ?: $request->user()->id,
            ])->save();

            $session->messages()->create([
                'sender_type' => LiveChatMessage::SENDER_SYSTEM,
                'sender_id' => $request->user()->id,
                'message' => 'Yetkili görüşmeyi sonlandırdı.',
                'created_at' => now(),
            ]);

            Log::info('Live chat session closed by admin.', [
                'live_chat_session_id' => $session->id,
                'admin_user_id' => $request->user()->id,
            ]);

            app(AdminActivityLogger::class)->log(
                AdminActivityLog::ACTION_LIVE_CHAT_CLOSED,
                'Canli destek kapatildi: #' . $session->id,
                $request->user(),
                $request,
            );
        }

        return response()->json([
            'session' => $this->sessionPayload($session->refresh()),
        ]);
    }

    private function authorizePermission(?User $user, string $permission): void
    {
        abort_unless($user?->role === User::ROLE_ADMIN && $user->hasPermission($permission), 403);
    }

    private function sessionPayload(LiveChatSession $session): array
    {
        $lastMessage = $session->messages()
            ->latest('created_at')
            ->first();

        return [
            'id' => $session->id,
            'visitor_name' => $session->visitor_name ?: 'Isimsiz ziyaretci',
            'visitor_email' => $session->visitor_email,
            'visitor_phone' => $session->visitor_phone,
            'ip_address' => $session->ip_address,
            'user_agent' => $session->user_agent,
            'status' => $session->status,
            'status_label' => LiveChatSession::statusOptions()[$session->status] ?? $session->status,
            'assigned_user' => $session->assignedUser?->name,
            'messages_count' => $session->messages_count ?? $session->messages()->count(),
            'last_message' => $lastMessage?->message,
            'last_sender_type' => $lastMessage?->sender_type,
            'last_message_at' => $lastMessage?->created_at?->timezone('Europe/Istanbul')->format('d.m.Y H:i'),
            'needs_attention' => $lastMessage?->sender_type === LiveChatMessage::SENDER_VISITOR && $session->status !== LiveChatSession::STATUS_CLOSED,
            'updated_at' => $session->updated_at?->timezone('Europe/Istanbul')->format('d.m.Y H:i'),
        ];
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

    private function needsAttention(LiveChatSession $session): bool
    {
        return $session->messages()
            ->latest('created_at')
            ->value('sender_type') === LiveChatMessage::SENDER_VISITOR;
    }

    private function touchCustomerContact(LiveChatSession $session): void
    {
        if (blank($session->visitor_email)) {
            return;
        }

        User::query()
            ->where('role', User::ROLE_CUSTOMER)
            ->where('email', $session->visitor_email)
            ->first()
            ?->forceFill(['last_contact_at' => now()])
            ->save();
    }
}
