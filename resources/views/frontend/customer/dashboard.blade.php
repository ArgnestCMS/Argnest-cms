@extends('frontend.layout')

@section('title', 'Musteri Paneli | Argnest')
@section('description', 'Argnest musteri paneli.')

@php
    $maskedIdentityNumber = $customer->identity_number
        ? str_repeat('*', max(strlen($customer->identity_number) - 4, 0)) . substr($customer->identity_number, -4)
        : 'Belirtilmedi';
    $summaryCards = [
        ['label' => 'Toplam Hizmet', 'value' => $totalServices],
        ['label' => 'Aktif Hizmet', 'value' => $activeServices],
        ['label' => 'Yaklaşan Yenileme', 'value' => $upcomingRenewals],
        ['label' => 'Açık Destek Talebi', 'value' => 0],
    ];
@endphp

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.36),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.30),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-sm font-black uppercase tracking-widest text-blue-200">Musteri Paneli</p>
                    <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">Hos geldiniz, {{ $customer->name }}</h1>
                    <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">Profil bilgileriniz ve proje surecleriniz icin temel panel altyapisi hazir.</p>
                </div>
                <form action="{{ route('frontend.customer.logout') }}" method="POST">
                    @csrf
                    <button class="rounded-2xl border border-white/15 px-6 py-3.5 text-sm font-black text-white transition hover:-translate-y-0.5 hover:border-blue-200 hover:text-blue-100">Cikis Yap</button>
                </form>
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <nav class="mb-8 flex flex-wrap gap-3 rounded-3xl border border-slate-200 bg-white p-3 shadow-sm">
                <a href="{{ route('frontend.customer.dashboard') }}" class="rounded-2xl bg-slate-950 px-5 py-3 text-sm font-black text-white">Dashboard</a>
                <a href="{{ route('frontend.customer.services') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Hizmetlerim</a>
                <a href="#profil" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Profilim</a>
            </nav>

            <div class="mb-6 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($summaryCards as $card)
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-3xl font-black text-slate-950">{{ $card['value'] }}</p>
                        <p class="mt-2 text-sm font-bold text-slate-500">{{ $card['label'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="grid gap-6 lg:grid-cols-[0.85fr_1.15fr]">
            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70">
                <div id="profil"></div>
                <p class="text-sm font-black uppercase tracking-widest text-blue-600">Profil Bilgileriniz</p>
                <div class="mt-6 grid gap-4">
                    @foreach ([
                        'Ad soyad' => $customer->name,
                        'Firma' => $customer->company_name ?: 'Belirtilmedi',
                        'E-posta' => $customer->email,
                        'Telefon' => $customer->phone ?: 'Belirtilmedi',
                        'TC Kimlik No' => $maskedIdentityNumber,
                        'Son giriş tarihi' => $customer->last_login_at?->format('d.m.Y H:i') ?: 'Henuz yok',
                        'Kayıt tarihi' => $customer->created_at?->format('d.m.Y H:i') ?: 'Belirtilmedi',
                    ] as $label => $value)
                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <p class="text-xs font-black uppercase tracking-widest text-blue-600">{{ $label }}</p>
                            <p class="mt-2 text-sm font-bold text-slate-800">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70">
                <p class="text-sm font-black uppercase tracking-widest text-blue-600">Hizmetleriniz</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950">Size tanımlanan hizmetleri takip edin</h2>
                <p class="mt-4 text-sm leading-7 text-slate-600">Domain, hosting, sunucu ve yenileme bilgilerinizi Hizmetlerim sayfasından görüntüleyebilirsiniz.</p>
                <div class="mt-8 grid gap-4 md:grid-cols-3">
                    @foreach (['Teklifler', 'Projeler', 'Destek'] as $item)
                        <div class="rounded-3xl bg-[linear-gradient(135deg,#020617_0%,#1d4ed8_56%,#6d28d9_100%)] p-5 text-white shadow-lg shadow-blue-100">
                            <p class="text-sm font-black">{{ $item }}</p>
                            <p class="mt-3 text-xs leading-5 text-blue-100">Hazirlaniyor</p>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('frontend.customer.services') }}" class="mt-8 inline-flex rounded-2xl bg-blue-600 px-6 py-3.5 text-sm font-black text-white shadow-lg shadow-blue-100 transition hover:-translate-y-0.5 hover:bg-blue-700">Hizmetlerimi Gör</a>
            </article>
            </div>
        </div>
    </section>
@endsection
