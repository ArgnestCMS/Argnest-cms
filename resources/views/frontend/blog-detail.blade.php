@extends('frontend.layout')

@php
    $imageUrl = $post->featured_image ? \Illuminate\Support\Facades\Storage::url($post->featured_image) : null;
    $blogUrl = route('frontend.blog.index');
    $contactUrl = route('home') . '#teklif';
    $whatsappNumber = preg_replace('/\D+/', '', $settings?->whatsapp ?: $settings?->phone ?: '');
@endphp

@section('title', ($post->seo_title ?: $post->title) . ' | Argnest Blog')
@section('description', $post->seo_description ?: $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 150))

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.36),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.30),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
            <div class="max-w-4xl">
                <div class="mb-7 flex flex-wrap items-center gap-3">
                    <a href="{{ $blogUrl }}" class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-black uppercase tracking-widest text-blue-100 transition hover:border-blue-200">Blog</a>
                    <span class="rounded-full bg-cyan-300 px-3 py-1.5 text-xs font-black uppercase tracking-widest text-slate-950">{{ $post->category?->name ?: 'Teknoloji' }}</span>
                    @if ($post->published_at)
                        <span class="rounded-full bg-white/10 px-3 py-1.5 text-xs font-black uppercase tracking-widest text-blue-100">{{ $post->published_at->format('d.m.Y') }}</span>
                    @endif
                </div>
                <h1 class="text-4xl font-black tracking-tight sm:text-5xl lg:text-6xl">{{ $post->title }}</h1>
                @if ($post->excerpt)
                    <p class="mt-6 max-w-2xl text-base leading-8 text-slate-300 sm:text-lg">{{ $post->excerpt }}</p>
                @endif
                @if ($post->author)
                    <p class="mt-6 text-sm font-black uppercase tracking-widest text-blue-200">{{ $post->author }}</p>
                @endif
            </div>
        </div>
    </section>

    <section class="bg-white py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-slate-950 p-3 shadow-2xl shadow-slate-200">
                @if ($imageUrl)
                    <img src="{{ $imageUrl }}" alt="{{ $post->title }}" class="aspect-[16/8] w-full rounded-[1.5rem] object-cover">
                @else
                    <div class="aspect-[16/8] rounded-[1.5rem] bg-[radial-gradient(circle_at_18%_18%,rgba(59,130,246,0.58),transparent_32%),radial-gradient(circle_at_82%_20%,rgba(124,58,237,0.45),transparent_34%),linear-gradient(135deg,#020617_0%,#1e3a8a_52%,#312e81_100%)] p-6">
                        <div class="grid h-full gap-5 rounded-3xl border border-white/15 bg-white/10 p-6 backdrop-blur md:grid-cols-[0.75fr_1fr]">
                            <div>
                                <div class="h-3 w-32 rounded-full bg-cyan-200"></div>
                                <div class="mt-8 h-4 w-4/5 rounded-full bg-white/75"></div>
                                <div class="mt-4 h-3 w-3/5 rounded-full bg-white/40"></div>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <span class="rounded-3xl bg-white/20"></span>
                                <span class="rounded-3xl bg-white/30"></span>
                                <span class="rounded-3xl bg-white/20"></span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-20">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-[minmax(0,1fr)_22rem] lg:px-8">
            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70 sm:p-8 lg:p-10">
                <div class="prose prose-slate max-w-none prose-headings:font-black prose-headings:text-slate-950 prose-a:font-bold prose-a:text-blue-700 prose-img:rounded-3xl">
                    {!! $post->content !!}
                </div>
            </article>

            <aside class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-black uppercase tracking-widest text-blue-600">Son Yazilar</p>
                    <div class="mt-5 grid gap-4">
                        @forelse ($latestPosts as $latestPost)
                            <a href="{{ route('frontend.blog.show', $latestPost) }}" class="group rounded-2xl border border-slate-100 bg-slate-50 p-4 transition hover:-translate-y-0.5 hover:border-blue-200 hover:bg-white hover:shadow-lg">
                                <p class="text-xs font-black uppercase tracking-widest text-blue-600">{{ $latestPost->category?->name ?: 'Blog' }}</p>
                                <h2 class="mt-2 text-sm font-black leading-6 text-slate-950 group-hover:text-blue-700">{{ $latestPost->title }}</h2>
                                @if ($latestPost->published_at)
                                    <p class="mt-2 text-xs font-bold text-slate-400">{{ $latestPost->published_at->format('d.m.Y') }}</p>
                                @endif
                            </a>
                        @empty
                            <p class="text-sm leading-6 text-slate-600">Henuz baska blog yazisi bulunmuyor.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-950 p-6 text-white shadow-2xl shadow-slate-200">
                    <p class="text-sm font-black uppercase tracking-widest text-blue-300">Diger Yazilar</p>
                    <div class="mt-5 grid gap-4">
                        @forelse ($relatedPosts as $relatedPost)
                            <a href="{{ route('frontend.blog.show', $relatedPost) }}" class="group rounded-2xl border border-white/10 bg-white/5 p-4 transition hover:-translate-y-0.5 hover:border-blue-300/40 hover:bg-white/10">
                                <p class="text-xs font-black uppercase tracking-widest text-blue-200">{{ $relatedPost->category?->name ?: 'Blog' }}</p>
                                <h2 class="mt-2 text-sm font-black leading-6 text-white group-hover:text-cyan-100">{{ $relatedPost->title }}</h2>
                                @if ($relatedPost->published_at)
                                    <p class="mt-2 text-xs font-bold text-slate-400">{{ $relatedPost->published_at->format('d.m.Y') }}</p>
                                @endif
                            </a>
                        @empty
                            <p class="text-sm leading-6 text-slate-300">Bu kategori icin benzer yazi henuz yok.</p>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-[2rem] bg-[linear-gradient(135deg,#020617_0%,#1d4ed8_56%,#6d28d9_100%)] p-8 text-white shadow-2xl shadow-slate-300 md:p-10">
                <div class="grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
                    <div>
                        <p class="text-sm font-black uppercase tracking-widest text-blue-100">Projeniz Icin Bizimle Iletisime Gecin</p>
                        <h2 class="mt-3 max-w-3xl text-3xl font-black tracking-tight sm:text-4xl">Bu fikirleri markaniz icin uygulanabilir plana cevirelim</h2>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-blue-100">Web, yazilim, panel, CRM veya SEO ihtiyacinizi birlikte netlestirip dogru teknik yol haritasini cikaralim.</p>
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
