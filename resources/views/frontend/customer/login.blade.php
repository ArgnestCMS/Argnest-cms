@extends('frontend.layout')

@section('title', 'Musteri Giris | Argnest')
@section('description', 'Argnest musteri paneline giris yapin.')

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.36),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.30),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto grid max-w-7xl gap-10 px-4 py-20 sm:px-6 lg:grid-cols-[0.95fr_1.05fr] lg:px-8 lg:py-24">
            <div class="flex flex-col justify-center">
                <p class="text-sm font-black uppercase tracking-widest text-blue-200">Musteri Girisi</p>
                <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl lg:text-6xl">Panelinize guvenli giris yapin</h1>
                <p class="mt-6 max-w-2xl text-base leading-8 text-slate-300 sm:text-lg">Proje, teklif ve destek surecleriniz icin Argnest musteri paneline erisin.</p>
                <a href="{{ route('frontend.customer.register') }}" class="mt-8 inline-flex w-fit rounded-2xl border border-white/15 px-6 py-3.5 text-sm font-black text-white transition hover:-translate-y-0.5 hover:border-blue-200 hover:text-blue-100">Yeni hesap olustur</a>
            </div>

            <form action="{{ route('frontend.customer.login.store') }}" method="POST" class="rounded-3xl border border-white/10 bg-white/[0.04] p-6 shadow-2xl shadow-blue-950/40 backdrop-blur">
                @csrf

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-300/30 bg-red-500/10 px-4 py-3 text-sm font-bold text-red-100">
                        E-posta veya sifre bilgisini kontrol edin.
                    </div>
                @endif

                <div class="grid gap-5">
                    <label class="block">
                        <span class="mb-2 block text-sm font-bold text-slate-200">E-posta</span>
                        <input name="email" type="email" value="{{ old('email') }}" class="w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm text-white outline-none ring-blue-300/20 transition focus:border-blue-200 focus:ring-4">
                        @error('email')<span class="mt-2 block text-xs font-bold text-red-200">{{ $message }}</span>@enderror
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-bold text-slate-200">Sifre</span>
                        <input name="password" type="password" class="w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm text-white outline-none ring-blue-300/20 transition focus:border-blue-200 focus:ring-4">
                        @error('password')<span class="mt-2 block text-xs font-bold text-red-200">{{ $message }}</span>@enderror
                    </label>
                    <label class="flex items-center gap-3 text-sm font-bold text-slate-200">
                        <input name="remember" value="1" type="checkbox" class="h-4 w-4 rounded border-white/20">
                        Beni hatirla
                    </label>
                </div>

                <button class="mt-6 w-full rounded-2xl bg-cyan-300 px-6 py-4 text-sm font-black text-slate-950 shadow-xl shadow-cyan-500/20 transition hover:-translate-y-0.5 hover:bg-cyan-200">Giris Yap</button>
            </form>
        </div>
    </section>
@endsection
