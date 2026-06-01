@extends('frontend.layout')

@section('title', 'Şifre Değiştir | Argnest')
@section('description', 'Argnest musteri paneli sifre degistirme sayfasi.')

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.36),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.30),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <p class="text-sm font-black uppercase tracking-widest text-blue-200">Musteri Paneli</p>
            <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">Şifre Değiştir</h1>
            <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">Hesabinizin guvenligi icin mevcut sifrenizi dogrulayarak yeni sifre belirleyin.</p>
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
                <a href="{{ route('frontend.customer.password') }}" class="rounded-2xl bg-slate-950 px-5 py-3 text-sm font-black text-white">Şifre Değiştir</a>
            </nav>

            @if (session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr]">
                <aside class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70">
                    <p class="text-sm font-black uppercase tracking-widest text-blue-600">Guvenlik</p>
                    <h2 class="mt-3 text-2xl font-black tracking-tight text-slate-950">Şifre kuralları</h2>
                    <div class="mt-6 space-y-3 text-sm leading-7 text-slate-600">
                        <p>Mevcut sifreniz dogrulanmadan yeni sifre kaydedilmez.</p>
                        <p>Yeni sifreniz en az 8 karakter olmalidir.</p>
                        <p>Yeni sifre ve tekrar alani ayni olmalidir.</p>
                    </div>
                </aside>

                <form action="{{ route('frontend.customer.password.update') }}" method="POST" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70">
                    @csrf

                    <p class="text-sm font-black uppercase tracking-widest text-blue-600">Hesap Guvenligi</p>
                    <h2 class="mt-3 text-2xl font-black tracking-tight text-slate-950">Yeni şifre belirle</h2>

                    <div class="mt-6 grid gap-5">
                        <label class="block">
                            <span class="mb-2 block text-sm font-bold text-slate-700">Mevcut Şifre</span>
                            <input name="current_password" type="password" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none ring-blue-500/20 transition focus:border-blue-500 focus:ring-4">
                            @error('current_password')
                                <span class="mt-2 block text-xs font-bold text-red-600">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-bold text-slate-700">Yeni Şifre</span>
                            <input name="password" type="password" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none ring-blue-500/20 transition focus:border-blue-500 focus:ring-4">
                            @error('password')
                                <span class="mt-2 block text-xs font-bold text-red-600">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-bold text-slate-700">Yeni Şifre Tekrar</span>
                            <input name="password_confirmation" type="password" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none ring-blue-500/20 transition focus:border-blue-500 focus:ring-4">
                        </label>
                    </div>

                    <button class="mt-6 rounded-2xl bg-blue-600 px-6 py-3.5 text-sm font-black text-white shadow-lg shadow-blue-100 transition hover:-translate-y-0.5 hover:bg-blue-700">
                        Şifreyi Değiştir
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
