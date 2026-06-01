<x-filament-panels::page>
    <style>
        .arg-dashboard { --navy:#0f172a; --blue:#2563eb; --violet:#7c3aed; --muted:#64748b; }
        .arg-hero { position:relative; overflow:hidden; border-radius:22px; padding:28px; color:white; background:linear-gradient(135deg,#020617 0%,#0f172a 48%,#1d4ed8 78%,#7c3aed 100%); box-shadow:0 24px 70px rgba(15,23,42,.22); }
        .arg-hero:before { content:""; position:absolute; inset:-120px -80px auto auto; width:360px; height:360px; border-radius:999px; background:rgba(124,58,237,.42); filter:blur(70px); }
        .arg-hero > * { position:relative; }
        .arg-grid { display:grid; gap:16px; }
        .arg-kpis { grid-template-columns:repeat(6,minmax(0,1fr)); }
        .arg-two { grid-template-columns:1.1fr .9fr; }
        .arg-three { grid-template-columns:repeat(3,minmax(0,1fr)); }
        .arg-card { border:1px solid rgba(148,163,184,.22); border-radius:18px; background:rgba(255,255,255,.82); box-shadow:0 18px 50px rgba(15,23,42,.08); padding:20px; backdrop-filter:blur(16px); }
        .dark .arg-card { background:rgba(15,23,42,.78); border-color:rgba(148,163,184,.2); }
        .arg-kpi { min-height:126px; display:flex; flex-direction:column; justify-content:space-between; }
        .arg-kpi span { color:var(--muted); font-size:12px; font-weight:800; text-transform:uppercase; letter-spacing:.05em; }
        .arg-kpi strong { font-size:32px; line-height:1; color:#0f172a; }
        .dark .arg-kpi strong { color:#f8fafc; }
        .arg-chip { display:inline-flex; align-items:center; gap:6px; border-radius:999px; padding:6px 10px; font-size:12px; font-weight:800; }
        .arg-ok { background:#dcfce7; color:#166534; }
        .arg-bad { background:#fee2e2; color:#991b1b; }
        .arg-btn { display:inline-flex; align-items:center; justify-content:center; border-radius:12px; padding:10px 13px; font-size:13px; font-weight:800; text-decoration:none; transition:.15s ease; }
        .arg-btn-primary { background:#2563eb; color:#fff; }
        .arg-btn-soft { background:rgba(255,255,255,.12); color:#dbeafe; border:1px solid rgba(255,255,255,.18); }
        .arg-btn-light { background:#eff6ff; color:#1d4ed8; }
        .arg-actions { display:flex; flex-wrap:wrap; gap:10px; }
        .arg-table-row { display:grid; grid-template-columns:1fr 1.2fr auto; gap:12px; align-items:center; padding:12px 0; border-bottom:1px solid rgba(148,163,184,.22); }
        .arg-table-row:last-child { border-bottom:0; }
        .arg-quick { display:flex; align-items:center; justify-content:space-between; gap:12px; border:1px solid rgba(148,163,184,.22); border-radius:16px; padding:16px; text-decoration:none; background:linear-gradient(135deg,rgba(239,246,255,.9),rgba(245,243,255,.9)); }
        .dark .arg-quick { background:linear-gradient(135deg,rgba(30,41,59,.9),rgba(49,46,129,.45)); }
        .arg-dot { width:10px; height:10px; border-radius:999px; display:inline-block; }
        @media (max-width: 1200px) { .arg-kpis { grid-template-columns:repeat(3,minmax(0,1fr)); } }
        @media (max-width: 900px) { .arg-two,.arg-three { grid-template-columns:1fr; } }
        @media (max-width: 640px) { .arg-kpis { grid-template-columns:1fr; } .arg-table-row { grid-template-columns:1fr; } .arg-hero { padding:22px; } }
    </style>

    <div class="arg-dashboard arg-grid">
        <section class="arg-hero">
            <div class="arg-grid arg-two" style="align-items:center;">
                <div>
                    <p style="margin:0 0 10px;color:#bfdbfe;font-weight:900;text-transform:uppercase;letter-spacing:.08em;font-size:12px;">Argnest CRM Yönetim Merkezi</p>
                    <h1 style="margin:0;font-size:clamp(28px,4vw,48px);font-weight:950;letter-spacing:-.03em;">Hoş geldin, {{ $user?->name }}</h1>
                    <p style="margin:14px 0 0;color:#dbeafe;line-height:1.7;max-width:680px;">Bugünün müşteri, destek, canlı sohbet ve yedekleme durumunu tek ekranda takip et.</p>
                </div>
                <div class="arg-card" style="background:rgba(255,255,255,.11);border-color:rgba(255,255,255,.18);color:#fff;">
                    <div class="arg-grid" style="gap:10px;">
                        <div><span style="color:#bfdbfe;font-size:12px;font-weight:800;">Rol</span><div style="font-weight:900;">{{ $roleName }}</div></div>
                        <div><span style="color:#bfdbfe;font-size:12px;font-weight:800;">Son giriş</span><div>{{ $user?->last_login_at?->timezone('Europe/Istanbul')->format('d.m.Y H:i') ?: 'Henüz yok' }}</div></div>
                        <div><span style="color:#bfdbfe;font-size:12px;font-weight:800;">Son IP</span><div>{{ $user?->last_login_ip ?: 'IP yok' }}</div></div>
                        <div><span style="color:#bfdbfe;font-size:12px;font-weight:800;">Kayıt</span><div>{{ $user?->created_at?->timezone('Europe/Istanbul')->format('d.m.Y H:i') ?: '-' }}</div></div>
                    </div>
                    <div class="arg-actions" style="margin-top:18px;">
                        <a class="arg-btn arg-btn-soft" href="{{ $urls['profile'] }}">Profilim</a>
                        <a class="arg-btn arg-btn-soft" href="{{ $urls['security'] }}">Güvenlik Merkezi</a>
                        <form method="POST" action="{{ route('filament.admin.auth.logout') }}">
                            @csrf
                            <button class="arg-btn arg-btn-primary" type="submit" style="border:0;cursor:pointer;">Oturumu Kapat</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <section class="arg-grid arg-kpis">
            @foreach ($kpis as $kpi)
                <article class="arg-card arg-kpi">
                    <span>{{ $kpi['label'] }}</span>
                    <strong>{{ number_format($kpi['value']) }}</strong>
                    <span class="arg-dot" style="background:{{ match ($kpi['tone']) { 'emerald' => '#10b981', 'amber' => '#f59e0b', 'violet' => '#7c3aed', 'rose' => '#e11d48', 'slate' => '#64748b', default => '#2563eb' } }}"></span>
                </article>
            @endforeach
        </section>

        <section class="arg-grid arg-two">
            <article class="arg-card">
                <h2 style="margin:0 0 16px;font-size:20px;font-weight:950;">Sistem Durumu</h2>
                <div class="arg-grid arg-three">
                    <div><strong>Argnest CRM</strong><p style="margin:6px 0;color:#64748b;">{{ $system['app_version'] }}</p></div>
                    <div><strong>Laravel</strong><p style="margin:6px 0;color:#64748b;">{{ $system['laravel_version'] }}</p></div>
                    <div><strong>Filament</strong><p style="margin:6px 0;color:#64748b;">{{ $system['filament_version'] }}</p></div>
                    <div><strong>PHP</strong><p style="margin:6px 0;color:#64748b;">{{ $system['php_version'] }}</p></div>
                </div>
                <div class="arg-actions" style="margin-top:18px;">
                    <span class="arg-chip {{ $system['mail_active'] ? 'arg-ok' : 'arg-bad' }}">Mail {{ $system['mail_active'] ? 'aktif' : 'pasif' }}</span>
                    <span class="arg-chip {{ $system['live_chat_active'] ? 'arg-ok' : 'arg-bad' }}">Canlı destek {{ $system['live_chat_active'] ? 'aktif' : 'pasif' }}</span>
                    <span class="arg-chip {{ $system['backup_active'] ? 'arg-ok' : 'arg-bad' }}">Yedekleme {{ $system['backup_active'] ? 'aktif' : 'pasif' }}</span>
                    <span class="arg-chip {{ $system['installer_locked'] ? 'arg-ok' : 'arg-bad' }}">Kurulum {{ $system['installer_locked'] ? 'kilitli' : 'açık' }}</span>
                </div>
            </article>

            <article class="arg-card">
                <h2 style="margin:0 0 16px;font-size:20px;font-weight:950;">Son Yedek</h2>
                @if ($latestBackup)
                    <div style="font-weight:900;">{{ $latestBackup->file_name }}</div>
                    <p style="margin:10px 0;color:#64748b;">Boyut: {{ $latestBackup->formattedSize() }}</p>
                    <span class="arg-chip {{ $latestBackup->status === \App\Models\SystemBackup::STATUS_COMPLETED ? 'arg-ok' : 'arg-bad' }}">
                        {{ \App\Models\SystemBackup::statusOptions()[$latestBackup->status] ?? $latestBackup->status }}
                    </span>
                @else
                    <p style="margin:0;color:#64748b;">Henüz alınmış yedek yok.</p>
                @endif
                <div style="margin-top:18px;"><a class="arg-btn arg-btn-light" href="{{ $urls['backups'] }}">Yedeklere Git</a></div>
            </article>
        </section>

        <section class="arg-grid arg-two">
            <article class="arg-card">
                <h2 style="margin:0 0 16px;font-size:20px;font-weight:950;">Son Aktiviteler</h2>
                @forelse ($activities as $activity)
                    <div class="arg-table-row">
                        <strong>{{ $activity->admin?->name ?: 'Sistem' }}</strong>
                        <span>{{ \App\Models\AdminActivityLog::actionOptions()[$activity->action] ?? $activity->action }}</span>
                        <small style="color:#64748b;">{{ $activity->created_at?->timezone('Europe/Istanbul')->format('d.m.Y H:i') }}</small>
                    </div>
                @empty
                    <p style="margin:0;color:#64748b;">Henüz admin aktivitesi yok.</p>
                @endforelse
            </article>

            <article class="arg-card">
                <h2 style="margin:0 0 16px;font-size:20px;font-weight:950;">Hızlı İşlemler</h2>
                <div class="arg-grid" style="grid-template-columns:repeat(2,minmax(0,1fr));">
                    <a class="arg-quick" href="{{ $urls['customers_create'] }}"><strong>Yeni Müşteri</strong><span>→</span></a>
                    <a class="arg-quick" href="{{ $urls['services_create'] }}"><strong>Yeni Hizmet</strong><span>→</span></a>
                    <a class="arg-quick" href="{{ $urls['support_create'] }}"><strong>Yeni Destek Talebi</strong><span>→</span></a>
                    <a class="arg-quick" href="{{ $urls['admin_create'] }}"><strong>Yeni Admin</strong><span>→</span></a>
                    <a class="arg-quick" href="{{ $urls['backups'] }}"><strong>Yeni Yedek Al</strong><span>→</span></a>
                    <a class="arg-quick" href="{{ $urls['live_chat'] }}"><strong>Canlı Destek</strong><span>→</span></a>
                </div>
            </article>
        </section>
    </div>
</x-filament-panels::page>
