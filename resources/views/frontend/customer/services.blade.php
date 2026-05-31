@extends('frontend.layout')

@section('title', 'Hizmetlerim | Argnest')
@section('description', 'Argnest musteri paneli hizmetlerim sayfasi.')

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.36),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.30),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
            <p class="text-sm font-black uppercase tracking-widest text-blue-200">Musteri Paneli</p>
            <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">Hizmetlerim</h1>
            <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">Argnest tarafindan hesabiniza tanimlanan hizmet, domain, hosting ve yenileme bilgileri.</p>
        </div>
    </section>

    <section class="bg-slate-50 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <nav class="mb-8 flex flex-wrap gap-3 rounded-3xl border border-slate-200 bg-white p-3 shadow-sm">
                <a href="{{ route('frontend.customer.dashboard') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Dashboard</a>
                <a href="{{ route('frontend.customer.services') }}" class="rounded-2xl bg-slate-950 px-5 py-3 text-sm font-black text-white">Hizmetlerim</a>
                <a href="{{ route('frontend.customer.support.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Destek</a>
                <a href="{{ route('frontend.customer.dashboard') }}#profil" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Profilim</a>
            </nav>

            <div class="grid gap-6 lg:grid-cols-2">
                @forelse ($services as $service)
                    @php
                        $remainingDays = $service->expiry_date ? now()->startOfDay()->diffInDays($service->expiry_date->copy()->startOfDay(), false) : null;
                        $renewalClasses = match (true) {
                            $remainingDays === null => 'border-slate-200 bg-slate-50 text-slate-700',
                            $remainingDays >= 90 => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                            $remainingDays >= 30 => 'border-amber-200 bg-amber-50 text-amber-700',
                            default => 'border-red-200 bg-red-50 text-red-700',
                        };
                        $renewalText = match (true) {
                            $remainingDays === null => 'Bitis tarihi yok',
                            $remainingDays < 0 => abs($remainingDays) . ' gun once bitti',
                            $remainingDays === 0 => 'Bugun bitiyor',
                            default => $remainingDays . ' gun kaldi',
                        };
                    @endphp
                    <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70 transition hover:-translate-y-1 hover:shadow-2xl">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="text-sm font-black uppercase tracking-widest text-blue-600">Hizmet</p>
                                <h2 class="mt-2 text-2xl font-black tracking-tight text-slate-950">{{ $service->service_name }}</h2>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span class="rounded-full px-3 py-1.5 text-xs font-black {{ $service->is_active ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : 'bg-slate-100 text-slate-500 ring-1 ring-slate-200' }}">
                                    {{ $service->is_active ? 'Aktif' : 'Pasif' }}
                                </span>
                                <span class="rounded-full border px-3 py-1.5 text-xs font-black {{ $renewalClasses }}">{{ $renewalText }}</span>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            @foreach ([
                                'Domain adi' => $service->domain_name ?: 'Belirtilmedi',
                                'Hosting paketi' => $service->hosting_package ?: 'Belirtilmedi',
                                'Sunucu IP' => $service->server_ip ?: 'Belirtilmedi',
                                'Sunucu paneli' => $service->server_panel ?: 'Belirtilmedi',
                                'Kullanici adi' => $service->username ?: 'Belirtilmedi',
                                'Son kullanim tarihi' => $service->expiry_date?->format('d.m.Y') ?: 'Belirtilmedi',
                            ] as $label => $value)
                                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                    <p class="text-xs font-black uppercase tracking-widest text-blue-600">{{ $label }}</p>
                                    <p class="mt-2 break-words text-sm font-bold text-slate-800">{{ $value }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-5 rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <p class="text-xs font-black uppercase tracking-widest text-blue-600">Notlar</p>
                            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $service->notes ?: 'Bu hizmet icin henuz not eklenmedi.' }}</p>
                        </div>
                    </article>
                @empty
                    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/70 lg:col-span-2">
                        <p class="text-xl font-black text-slate-950">Henuz hizmet kaydiniz yok.</p>
                        <p class="mt-3 text-sm leading-6 text-slate-600">Admin ekibi size hizmet tanimladiginda domain, hosting ve yenileme bilgileri burada gorunecek.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
