@extends('frontend.layout')

@section('title', 'Aktivite Gecmisim | Argnest')
@section('description', 'Argnest musteri paneli aktivite gecmisi.')

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.36),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.30),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
            <p class="text-sm font-black uppercase tracking-widest text-blue-200">Musteri Paneli</p>
            <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">Aktivite Gecmisim</h1>
            <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">Panelinizde gerceklestirdiginiz islemleri, IP ve tarih bilgileriyle takip edin.</p>
        </div>
    </section>

    <section class="bg-slate-50 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <nav class="mb-8 flex flex-wrap gap-3 rounded-3xl border border-slate-200 bg-white p-3 shadow-sm">
                <a href="{{ route('frontend.customer.dashboard') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Dashboard</a>
                <a href="{{ route('frontend.customer.services') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Hizmetlerim</a>
                <a href="{{ route('frontend.customer.files.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Dosyalarim</a>
                <a href="{{ route('frontend.customer.notifications.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Bildirimlerim</a>
                <a href="{{ route('frontend.customer.reviews.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Yorumlarim</a>
                <a href="{{ route('frontend.customer.activities.index') }}" class="rounded-2xl bg-slate-950 px-5 py-3 text-sm font-black text-white">Aktivite Gecmisim</a>
                <a href="{{ route('frontend.customer.support.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Destek</a>
                <a href="{{ route('frontend.customer.profile') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Profilim</a>
                <a href="{{ route('frontend.customer.password') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Şifre Değiştir</a>
                <a href="{{ route('frontend.customer.security') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Güvenlik Merkezi</a>
            </nav>

            <div class="grid gap-4">
                @forelse ($logs as $log)
                    <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70">
                        <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <p class="text-xs font-black uppercase tracking-widest text-blue-600">{{ $actionOptions[$log->action] ?? $log->action }}</p>
                                <h2 class="mt-2 text-xl font-black text-slate-950">{{ $log->description ?: 'Aktivite kaydi' }}</h2>
                                <p class="mt-3 text-sm font-bold text-slate-500">IP: {{ $log->ip_address ?: 'Kayit yok' }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-600">
                                {{ $log->created_at?->format('d.m.Y H:i') ?: 'Tarih yok' }}
                            </div>
                        </div>
                        @if ($log->user_agent)
                            <p class="mt-4 break-words rounded-2xl bg-slate-50 p-4 text-xs font-semibold leading-6 text-slate-500">{{ $log->user_agent }}</p>
                        @endif
                    </article>
                @empty
                    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/70">
                        <p class="text-xl font-black text-slate-950">Henuz aktivite kaydiniz yok.</p>
                        <p class="mt-3 text-sm leading-6 text-slate-600">Panelde islem yaptikca aktivite gecmisiniz burada listelenecek.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $logs->links() }}
            </div>
        </div>
    </section>
@endsection
