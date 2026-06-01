@extends('frontend.layout')

@section('title', 'Profilim | Argnest')
@section('description', 'Argnest musteri paneli profil duzenleme sayfasi.')

@php
    $maskedIdentityNumber = $customer->identity_number
        ? str_repeat('*', max(strlen($customer->identity_number) - 4, 0)) . substr($customer->identity_number, -4)
        : 'Belirtilmedi';
@endphp

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.36),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.30),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <p class="text-sm font-black uppercase tracking-widest text-blue-200">Musteri Paneli</p>
            <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">Profilim</h1>
            <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">Temel profil bilgilerinizi guvenli sekilde guncelleyin.</p>
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
                <a href="{{ route('frontend.customer.profile') }}" class="rounded-2xl bg-slate-950 px-5 py-3 text-sm font-black text-white">Profilim</a>
                <a href="{{ route('frontend.customer.password') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Şifre Değiştir</a>
                <a href="{{ route('frontend.customer.security') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Güvenlik Merkezi</a>
            </nav>

            @if (session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('warning'))
                <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-bold text-amber-800">
                    {{ session('warning') }}
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
                <form action="{{ route('frontend.customer.profile.update') }}" method="POST" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70">
                    @csrf

                    <p class="text-sm font-black uppercase tracking-widest text-blue-600">Duzenlenebilir Bilgiler</p>
                    <h2 class="mt-3 text-2xl font-black tracking-tight text-slate-950">Profil bilgileri</h2>

                    <div class="mt-6 grid gap-5 md:grid-cols-2">
                        <label class="block">
                            <span class="mb-2 block text-sm font-bold text-slate-700">Ad soyad</span>
                            <input name="name" value="{{ old('name', $customer->name) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none ring-blue-500/20 transition focus:border-blue-500 focus:ring-4">
                            @error('name')
                                <span class="mt-2 block text-xs font-bold text-red-600">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-bold text-slate-700">E-posta</span>
                            <input name="email" type="email" value="{{ old('email', $customer->email) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none ring-blue-500/20 transition focus:border-blue-500 focus:ring-4">
                            @error('email')
                                <span class="mt-2 block text-xs font-bold text-red-600">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-bold text-slate-700">Firma adi</span>
                            <input name="company_name" value="{{ old('company_name', $customer->company_name) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none ring-blue-500/20 transition focus:border-blue-500 focus:ring-4">
                            @error('company_name')
                                <span class="mt-2 block text-xs font-bold text-red-600">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-bold text-slate-700">Telefon</span>
                            <input name="phone" value="{{ old('phone', $customer->phone) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none ring-blue-500/20 transition focus:border-blue-500 focus:ring-4">
                            @error('phone')
                                <span class="mt-2 block text-xs font-bold text-red-600">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    <button class="mt-6 rounded-2xl bg-blue-600 px-6 py-3.5 text-sm font-black text-white shadow-lg shadow-blue-100 transition hover:-translate-y-0.5 hover:bg-blue-700">
                        Profili Guncelle
                    </button>
                </form>

                <aside class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70">
                    <p class="text-sm font-black uppercase tracking-widest text-blue-600">Guvenlik Bilgileri</p>
                    <h2 class="mt-3 text-2xl font-black tracking-tight text-slate-950">Sadece goruntulenir</h2>
                    <div class="mt-6 grid gap-4">
                        @foreach ([
                            'TC Kimlik No' => $maskedIdentityNumber,
                            'Kayit tarihi' => $customer->created_at?->timezone('Europe/Istanbul')->format('d.m.Y H:i') ?: 'Belirtilmedi',
                            'Son giris tarihi' => $customer->last_login_at?->timezone('Europe/Istanbul')->format('d.m.Y H:i') ?: 'Henuz yok',
                            'Son giris IP' => $customer->last_login_ip ?: 'Belirtilmedi',
                            'Kayit IP' => $customer->registration_ip ?: 'Belirtilmedi',
                        ] as $label => $value)
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                <p class="text-xs font-black uppercase tracking-widest text-blue-600">{{ $label }}</p>
                                <p class="mt-2 text-sm font-bold text-slate-800">{{ $value }}</p>
                            </div>
                        @endforeach
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
