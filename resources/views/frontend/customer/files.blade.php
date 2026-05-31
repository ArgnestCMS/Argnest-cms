@extends('frontend.layout')

@section('title', 'Dosyalarim | Argnest')
@section('description', 'Argnest musteri paneli dosya merkezi.')

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.36),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.30),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
            <p class="text-sm font-black uppercase tracking-widest text-blue-200">Dosya Merkezi</p>
            <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">Dosyalarim</h1>
            <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">Size ozel teklif, sozlesme, fatura ve proje dosyalarini guvenli sekilde indirin.</p>
        </div>
    </section>

    <section class="bg-slate-50 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <nav class="mb-8 flex flex-wrap gap-3 rounded-3xl border border-slate-200 bg-white p-3 shadow-sm">
                <a href="{{ route('frontend.customer.dashboard') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Dashboard</a>
                <a href="{{ route('frontend.customer.services') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Hizmetlerim</a>
                <a href="{{ route('frontend.customer.files.index') }}" class="rounded-2xl bg-slate-950 px-5 py-3 text-sm font-black text-white">Dosyalarim</a>
                <a href="{{ route('frontend.customer.reviews.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Yorumlarim</a>
                <a href="{{ route('frontend.customer.activities.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Aktivitelerim</a>
                <a href="{{ route('frontend.customer.support.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Destek</a>
                <a href="{{ route('frontend.customer.dashboard') }}#profil" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Profilim</a>
            </nav>

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($files as $file)
                    <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70 transition hover:-translate-y-1 hover:shadow-2xl">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <span class="rounded-full bg-blue-50 px-3 py-1.5 text-xs font-black uppercase tracking-widest text-blue-700">{{ $categoryOptions[$file->category] ?? $file->category }}</span>
                                <h2 class="mt-4 text-2xl font-black tracking-tight text-slate-950">{{ $file->title }}</h2>
                                <p class="mt-2 break-words text-sm font-bold text-slate-500">{{ $file->file_name }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 px-3 py-2 text-xs font-black text-slate-600">{{ $file->formattedSize() }}</div>
                        </div>

                        @if ($file->description)
                            <p class="mt-5 whitespace-pre-line text-sm leading-7 text-slate-600">{{ $file->description }}</p>
                        @endif

                        <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-5">
                            <p class="text-xs font-bold text-slate-500">Yuklenme: {{ $file->created_at?->format('d.m.Y H:i') ?: 'Tarih yok' }}</p>
                            <a href="{{ route('frontend.customer.files.download', $file) }}" class="inline-flex rounded-2xl bg-blue-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-blue-100 transition hover:-translate-y-0.5 hover:bg-blue-700">Indir</a>
                        </div>
                    </article>
                @empty
                    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/70 md:col-span-2 xl:col-span-3">
                        <p class="text-xl font-black text-slate-950">Henuz size atanmis dosya yok.</p>
                        <p class="mt-3 text-sm leading-6 text-slate-600">Admin ekibi size teklif, sozlesme veya proje dosyasi yuklediginde burada gorunecek.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $files->links() }}
            </div>
        </div>
    </section>
@endsection
