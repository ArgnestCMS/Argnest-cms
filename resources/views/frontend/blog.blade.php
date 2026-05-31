@extends('frontend.layout')

@php
    $contactUrl = route('home') . '#teklif';
    $whatsappNumber = preg_replace('/\D+/', '', $settings?->whatsapp ?: $settings?->phone ?: '');
@endphp

@section('title', 'Blog | Argnest')
@section('description', 'Argnest blog: teknoloji, yazilim, web, SEO ve dijital buyume notlari.')

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.36),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.30),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
            <div class="max-w-4xl">
                <div class="mb-7 flex flex-wrap items-center gap-3">
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-black uppercase tracking-widest text-blue-100">Argnest Blog</span>
                    <span class="rounded-full bg-cyan-300 px-3 py-1.5 text-xs font-black uppercase tracking-widest text-slate-950">Teknoloji Notlari</span>
                </div>
                <h1 class="text-4xl font-black tracking-tight sm:text-5xl lg:text-6xl">Dijital buyume ve yazilim uzerine net fikirler</h1>
                <p class="mt-6 max-w-2xl text-base leading-8 text-slate-300 sm:text-lg">
                    Kurumsal web, ozel yazilim, SEO, guvenlik ve operasyonel dijitallesme konularinda uygulanabilir Argnest notlari.
                </p>
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12 flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
                <div class="max-w-3xl">
                    <p class="text-sm font-black uppercase tracking-widest text-blue-600">Blog Grid</p>
                    <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">Son yayinlanan icerikler</h2>
                    <p class="mt-4 text-base leading-7 text-slate-600">Strateji, tasarim, yazilim ve yayin sureclerini daha okunabilir hale getiren secili yazilar.</p>
                </div>
                @if ($featuredPosts->isNotEmpty())
                    <div class="rounded-full border border-blue-100 bg-white px-4 py-2 text-xs font-black uppercase tracking-widest text-blue-700 shadow-sm">One Cikan {{ $featuredPosts->count() }} Yazi</div>
                @endif
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($blogPosts as $post)
                    @php
                        $imageUrl = $post->featured_image ? \Illuminate\Support\Facades\Storage::url($post->featured_image) : null;
                    @endphp
                    <article data-blog-slug="{{ $post->slug }}" class="group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-2xl hover:shadow-blue-100">
                        <div class="relative overflow-hidden">
                            @if ($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $post->title }}" class="h-56 w-full object-cover transition duration-500 group-hover:scale-105">
                            @else
                                <div class="h-56 bg-[radial-gradient(circle_at_18%_18%,rgba(59,130,246,0.58),transparent_32%),radial-gradient(circle_at_82%_20%,rgba(124,58,237,0.45),transparent_34%),linear-gradient(135deg,#020617_0%,#1e3a8a_52%,#312e81_100%)] p-5">
                                    <div class="h-full rounded-3xl border border-white/15 bg-white/10 p-5 backdrop-blur">
                                        <div class="flex items-center gap-2">
                                            <span class="h-2.5 w-2.5 rounded-full bg-cyan-200"></span>
                                            <span class="h-2.5 w-2.5 rounded-full bg-blue-200"></span>
                                            <span class="h-2.5 w-2.5 rounded-full bg-violet-200"></span>
                                        </div>
                                        <div class="mt-8 h-3 w-36 rounded-full bg-white/70"></div>
                                        <div class="mt-4 h-2 w-52 max-w-full rounded-full bg-white/35"></div>
                                        <div class="mt-8 grid grid-cols-3 gap-3">
                                            <span class="h-16 rounded-2xl bg-white/20"></span>
                                            <span class="h-16 rounded-2xl bg-white/30"></span>
                                            <span class="h-16 rounded-2xl bg-white/20"></span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="absolute left-5 top-5 flex flex-wrap gap-2">
                                <span class="rounded-full bg-white/90 px-3 py-1.5 text-xs font-black text-slate-950 shadow-lg">{{ $post->category?->name ?: 'Blog' }}</span>
                                @if ($post->is_featured)
                                    <span class="rounded-full bg-blue-500 px-3 py-1.5 text-xs font-black text-white shadow-lg shadow-blue-950/30">One Cikan</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="mb-4 flex flex-wrap items-center gap-2 text-xs font-bold text-slate-400">
                                @if ($post->published_at)
                                    <span>{{ $post->published_at->format('d.m.Y') }}</span>
                                @endif
                                @if ($post->author)
                                    <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                                    <span>{{ $post->author }}</span>
                                @endif
                            </div>
                            <h3 class="text-xl font-black tracking-tight text-slate-950">{{ $post->title }}</h3>
                            <p class="mt-3 text-sm leading-6 text-slate-600">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 130) }}</p>
                            <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-5">
                                <span class="text-sm font-black text-blue-700">Detay sayfasi icin hazir</span>
                                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-50 text-lg font-black text-blue-700 transition group-hover:bg-blue-600 group-hover:text-white">-&gt;</span>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm md:col-span-2 lg:col-span-3">
                        <p class="text-lg font-black text-slate-950">Aktif blog yazisi bulunamadi.</p>
                        <p class="mt-3 text-sm leading-6 text-slate-600">Yeni icerikler yayina alindiginda bu sayfada listelenecek.</p>
                    </div>
                @endforelse
            </div>

            @if ($blogPosts->hasPages())
                <div class="mt-12">
                    {{ $blogPosts->links() }}
                </div>
            @endif
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-[2rem] bg-[linear-gradient(135deg,#020617_0%,#1d4ed8_56%,#6d28d9_100%)] p-8 text-white shadow-2xl shadow-slate-300 md:p-10">
                <div class="grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
                    <div>
                        <p class="text-sm font-black uppercase tracking-widest text-blue-100">Bir Sonraki Adim</p>
                        <h2 class="mt-3 max-w-3xl text-3xl font-black tracking-tight sm:text-4xl">Okuduklarinizi projenize uyarlayalim</h2>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-blue-100">Web sitesi, panel, CRM, SEO veya ozel yazilim ihtiyaciniz icin net bir yol haritasi cikaralim.</p>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row lg:flex-col xl:flex-row">
                        <a href="{{ $contactUrl }}" class="rounded-xl bg-white px-6 py-3.5 text-center text-sm font-black text-slate-950 shadow-lg shadow-slate-950/20 transition hover:-translate-y-0.5">Teklif Al</a>
                        @if ($whatsappNumber)
                            <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" rel="noreferrer" class="rounded-xl border border-white/20 px-6 py-3.5 text-center text-sm font-black text-white transition hover:border-white hover:bg-white/10">Iletisime Gec</a>
                        @else
                            <a href="{{ $contactUrl }}" class="rounded-xl border border-white/20 px-6 py-3.5 text-center text-sm font-black text-white transition hover:border-white hover:bg-white/10">Iletisime Gec</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
