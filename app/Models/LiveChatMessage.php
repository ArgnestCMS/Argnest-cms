<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiveChatMessage extends Model
{
    public const UPDATED_AT = null;

    public const SENDER_VISITOR = 'visitor';

    public const SENDER_ADMIN = 'admin';

    public const SENDER_SYSTEM = 'system';

    protected $fillable = [
        'live_chat_session_id',
        'sender_type',
        'sender_id',
        'message',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(LiveChatSession::class, 'live_chat_session_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
