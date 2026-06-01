@extends('frontend.layout')

@section('title', 'Güvenlik Merkezi | Argnest')
@section('description', 'Argnest musteri paneli guvenlik merkezi.')

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.36),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.30),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <p class="text-sm font-black uppercase tracking-widest text-blue-200">Musteri Paneli</p>
            <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">Güvenlik Merkezi</h1>
            <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">Hesabinizin giris ve guvenlik aktivitesi bilgilerini buradan izleyebilirsiniz.</p>
        </div>
    </section>

    <section class="bg-slate-50 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <nav class="mb-8 flex flex-wrap gap-3 rounded-3xl border border-slate-200 bg-white p-3 shadow-sm">
                <a href="{{ route('frontend.customer.dashboard') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Dashboard</a>
                <a href="{{ route('frontend.customer.services') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Hizmetlerim</a>
                <a href="{{ route('frontend.customer.files.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Dosyalarim</a>
                <a href="{{ route('frontend.customer.notifications.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Bildirimlerim</a>
                <a href="{{ route('frontend.customer.reviews.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Yorumlarim</a>
                <a href="{{ route('frontend.customer.activities.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Aktivitelerim</a>
                <a href="{{ route('frontend.customer.support.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Destek</a>
                <a href="{{ route('frontend.customer.profile') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Profilim</a>
                <a href="{{ route('frontend.customer.password') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Şifre Değiştir</a>
                <a href="{{ route('frontend.customer.security') }}" class="rounded-2xl bg-slate-950 px-5 py-3 text-sm font-black text-white">Güvenlik Merkezi</a>
            </nav>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    'Son giriş tarihi' => $customer->last_login_at?->format('d.m.Y H:i') ?: 'Henuz yok',
                    'Son giriş IP' => $customer->last_login_ip ?: 'Belirtilmedi',
                    'Kayıt tarihi' => $customer->created_at?->format('d.m.Y H:i') ?: 'Belirtilmedi',
                    'Kayıt IP' => $customer->registration_ip ?: 'Belirtilmedi',
                ] as $label => $value)
                    <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-xs font-black uppercase tracking-widest text-blue-600">{{ $label }}</p>
                        <p class="mt-3 text-sm font-bold text-slate-800">{{ $value }}</p>
                    </article>
                @endforeach
            </div>

            <article class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-sm font-black uppercase tracking-widest text-blue-600">Son 10 Güvenlik Aktivitesi</p>
                        <h2 class="mt-3 text-2xl font-black tracking-tight text-slate-950">Hesap hareketleri</h2>
                    </div>
                </div>

                <div class="mt-6 grid gap-4">
                    @forelse ($securityLogs as $log)
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm font-black text-slate-950">{{ $actionOptions[$log->action] ?? $log->action }}</p>
                                    <p class="mt-1 text-sm leading-6 text-slate-600">{{ $log->description ?: 'Aciklama yok.' }}</p>
                                </div>
                                <div class="text-left sm:text-right">
                                    <p class="text-xs font-bold text-slate-500">{{ $log->created_at?->format('d.m.Y H:i') }}</p>
                                    <p class="mt-1 text-xs font-bold text-slate-400">IP: {{ $log->ip_address ?: 'Belirtilmedi' }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center">
                            <p class="text-sm font-bold text-slate-600">Henüz güvenlik aktivitesi bulunmuyor.</p>
                        </div>
                    @endforelse
                </div>
            </article>
        </div>
    </section>
@endsection
