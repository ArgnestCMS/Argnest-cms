@extends('frontend.layout')

@section('title', 'Musteri Kayit | Argnest')
@section('description', 'Argnest musteri hesabi olusturun ve proje sureclerinizi takip etmeye baslayin.')

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.36),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.30),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto grid max-w-7xl gap-10 px-4 py-20 sm:px-6 lg:grid-cols-[0.95fr_1.05fr] lg:px-8 lg:py-24">
            <div class="flex flex-col justify-center">
                <p class="text-sm font-black uppercase tracking-widest text-blue-200">Musteri Hesabi</p>
                <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl lg:text-6xl">Argnest musteri paneline katilin</h1>
                <p class="mt-6 max-w-2xl text-base leading-8 text-slate-300 sm:text-lg">Teklif, proje ve destek surecleriniz icin guvenli bir musteri hesabi olusturun.</p>
                <a href="{{ route('login') }}" class="mt-8 inline-flex w-fit rounded-2xl border border-white/15 px-6 py-3.5 text-sm font-black text-white transition hover:-translate-y-0.5 hover:border-blue-200 hover:text-blue-100">Zaten hesabim var</a>
            </div>

            <form action="{{ route('frontend.customer.register.store') }}" method="POST" class="rounded-3xl border border-white/10 bg-white/[0.04] p-6 shadow-2xl shadow-blue-950/40 backdrop-blur">
                @csrf

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-300/30 bg-red-500/10 px-4 py-3 text-sm font-bold text-red-100">
                        Lutfen formdaki eksik veya hatali alanlari kontrol edin.
                    </div>
                @endif

                <div class="grid gap-5 md:grid-cols-2">
                    <label class="block">
                        <span class="mb-2 block text-sm font-bold text-slate-200">Ad soyad</span>
                        <input name="name" value="{{ old('name') }}" class="w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm text-white outline-none ring-blue-300/20 transition placeholder:text-slate-400 focus:border-blue-200 focus:ring-4">
                        @error('name')<span class="mt-2 block text-xs font-bold text-red-200">{{ $message }}</span>@enderror
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-bold text-slate-200">Firma adi</span>
                        <input name="company_name" value="{{ old('company_name') }}" class="w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm text-white outline-none ring-blue-300/20 transition focus:border-blue-200 focus:ring-4">
                        @error('company_name')<span class="mt-2 block text-xs font-bold text-red-200">{{ $message }}</span>@enderror
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-bold text-slate-200">E-posta</span>
                        <input name="email" type="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm text-white outline-none ring-blue-300/20 transition focus:border-blue-200 focus:ring-4">
                        @error('email')<span class="mt-2 block text-xs font-bold text-red-200">{{ $message }}</span>@enderror
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-bold text-slate-200">Telefon</span>
                        <input name="phone" value="{{ old('phone') }}" class="w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm text-white outline-none ring-blue-300/20 transition focus:border-blue-200 focus:ring-4">
                        @error('phone')<span class="mt-2 block text-xs font-bold text-red-200">{{ $message }}</span>@enderror
                    </label>
                    <label class="block md:col-span-2">
                        <span class="mb-2 block text-sm font-bold text-slate-200">TC Kimlik No</span>
                        <input name="identity_number" inputmode="numeric" maxlength="11" value="{{ old('identity_number') }}" class="w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm text-white outline-none ring-blue-300/20 transition focus:border-blue-200 focus:ring-4">
                        @error('identity_number')<span class="mt-2 block text-xs font-bold text-red-200">{{ $message }}</span>@enderror
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-bold text-slate-200">Sifre</span>
                        <input name="password" type="password" class="w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm text-white outline-none ring-blue-300/20 transition focus:border-blue-200 focus:ring-4">
                        @error('password')<span class="mt-2 block text-xs font-bold text-red-200">{{ $message }}</span>@enderror
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-bold text-slate-200">Sifre tekrar</span>
                        <input name="password_confirmation" type="password" class="w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm text-white outline-none ring-blue-300/20 transition focus:border-blue-200 focus:ring-4">
                    </label>
                    <label class="flex gap-3 rounded-2xl border border-white/10 bg-white/5 p-4 md:col-span-2">
                        <input name="kvkk_accepted" value="1" type="checkbox" class="mt-1 h-4 w-4 rounded border-white/20">
                        <span class="text-sm leading-6 text-slate-200">
                            <a href="{{ route('frontend.legal.kvkk') }}" target="_blank" class="font-black text-blue-200 hover:text-white">KVKK aydinlatma metnini</a> okudum ve kabul ediyorum.
                            @error('kvkk_accepted')<span class="mt-2 block text-xs font-bold text-red-200">{{ $message }}</span>@enderror
                        </span>
                    </label>
                </div>

                <button class="mt-6 w-full rounded-2xl bg-cyan-300 px-6 py-4 text-sm font-black text-slate-950 shadow-xl shadow-cyan-500/20 transition hover:-translate-y-0.5 hover:bg-cyan-200">Hesap Olustur</button>
            </form>
        </div>
    </section>
@endsection
