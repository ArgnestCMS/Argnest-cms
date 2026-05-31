<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerService extends Model
{
    protected $fillable = [
        'user_id',
        'service_name',
        'domain_name',
        'hosting_package',
        'server_ip',
        'server_panel',
        'username',
        'password_hint',
        'expiry_date',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'expiry_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
