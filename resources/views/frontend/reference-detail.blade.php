@extends('frontend.layout')

@php
    $coverImageUrl = $portfolio->cover_image ? \Illuminate\Support\Facades\Storage::url($portfolio->cover_image) : null;
    $galleryImages = collect($portfolio->gallery ?: [])->filter()->values();

    if ($galleryImages->isEmpty() && $portfolio->cover_image) {
        $galleryImages = collect([$portfolio->cover_image]);
    }

    $contactUrl = route('home') . '#teklif';
    $referencesUrl = route('home') . '#referanslar';
    $whatsappNumber = preg_replace('/\D+/', '', $settings?->whatsapp ?: $settings?->phone ?: '');
    $detailText = $portfolio->description ?: $portfolio->short_description ?: 'Bu proje; ihtiyac analizi, tasarim, gelistirme ve yayin surecleri Argnest tarafindan uctan uca planlanan premium bir dijital calismadir.';
    $techBadges = ['Laravel', 'Filament', 'Tailwind', 'MySQL'];
    $projectInfo = [
        ['label' => 'Musteri', 'value' => $portfolio->client_name ?: 'Kurumsal marka'],
        ['label' => 'Sektor', 'value' => 'Dijital Cozumler'],
        ['label' => 'Tarih', 'value' => $portfolio->completion_date?->format('d.m.Y') ?: 'Planli teslim'],
        ['label' => 'Durum', 'value' => $portfolio->is_featured ? 'One cikan proje' : 'Tamamlanan proje'],
    ];
@endphp

@section('title', ($portfolio->seo_title ?: $portfolio->title) . ' | Argnest')
@section('description', $portfolio->seo_description ?: $portfolio->short_description ?: 'Argnest referans detay sayfasi.')

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.36),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.32),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto grid max-w-7xl gap-10 px-4 py-20 sm:px-6 lg:grid-cols-[0.92fr_1.08fr] lg:px-8 lg:py-24">
            <div class="flex flex-col justify-center">
                <div class="mb-7 flex flex-wrap items-center gap-3">
                    <a href="{{ $referencesUrl }}" class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-black uppercase tracking-widest text-blue-100 transition hover:border-blue-200">Referanslar</a>
                    @if ($portfolio->client_name)
                        <span class="rounded-full bg-cyan-300 px-3 py-1.5 text-xs font-black uppercase tracking-widest text-slate-950">{{ $portfolio->client_name }}</span>
                    @endif
                    @if ($portfolio->is_featured)
                        <span class="rounded-full bg-blue-400/15 px-3 py-1.5 text-xs font-black uppercase tracking-widest text-blue-100">One Cikan</span>
                    @endif
                </div>
                <h1 class="text-4xl font-black tracking-tight sm:text-5xl lg:text-6xl">{{ $portfolio->title }}</h1>
                <p class="mt-6 max-w-2xl text-base leading-8 text-slate-300 sm:text-lg">
                    {{ $portfolio->short_description ?: 'Marka hedeflerine gore tasarlanan, guvenli ve yonetilebilir premium dijital proje.' }}
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ $contactUrl }}" class="rounded-2xl bg-blue-500 px-6 py-3.5 text-center text-sm font-black text-white shadow-2xl shadow-blue-600/30 transition hover:-translate-y-0.5 hover:bg-blue-400">Teklif Al</a>
                    @if ($whatsappNumber)
                        <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" rel="noreferrer" class="rounded-2xl border border-white/15 px-6 py-3.5 text-center text-sm font-black text-white transition hover:-translate-y-0.5 hover:border-blue-200 hover:text-blue-100">Iletisime Gec</a>
                    @else
                        <a href="{{ $contactUrl }}" class="rounded-2xl border border-white/15 px-6 py-3.5 text-center text-sm font-black text-white transition hover:-translate-y-0.5 hover:border-blue-200 hover:text-blue-100">Iletisime Gec</a>
                    @endif
                </div>
            </div>

            <div class="relative">
                <div class="rounded-[2rem] border border-white/10 bg-white/[0.04] p-4 shadow-2xl shadow-blue-950/40 backdrop-blur">
                    @if ($coverImageUrl)
                        <img src="{{ $coverImageUrl }}" alt="{{ $portfolio->title }}" class="aspect-[16/11] w-full rounded-[1.5rem] object-cover">
                    @else
                        <div class="aspect-[16/11] rounded-[1.5rem] bg-[linear-gradient(135deg,#020617_0%,#1d4ed8_50%,#6d28d9_100%)] p-5">
                            <div class="grid h-full grid-cols-[0.72fr_1fr] gap-4 rounded-3xl border border-white/15 bg-slate-950/35 p-4 backdrop-blur">
                                <div class="rounded-3xl bg-white/10 p-4">
                                    <div class="h-3 w-24 rounded-full bg-cyan-200"></div>
                                    <div class="mt-7 space-y-3">
                                        <span class="block h-11 rounded-2xl bg-white/15"></span>
                                        <span class="block h-11 rounded-2xl bg-white/10"></span>
                                        <span class="block h-11 rounded-2xl bg-white/15"></span>
                                    </div>
                                </div>
                                <div class="grid gap-4">
                                    <div class="rounded-3xl bg-white/90 p-5">
                                        <div class="h-3 w-32 rounded-full bg-slate-300"></div>
                                        <div class="mt-5 grid grid-cols-3 gap-3">
                                            <span class="h-20 rounded-2xl bg-blue-100"></span>
                                            <span class="h-20 rounded-2xl bg-violet-100"></span>
                                            <span class="h-20 rounded-2xl bg-cyan-100"></span>
                                        </div>
                                    </div>
                                    <div class="rounded-3xl bg-white/15 p-5">
                                        <div class="h-2 rounded-full bg-white/25"></div>
                                        <div class="mt-3 h-2 w-4/5 rounded-full bg-cyan-200"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 max-w-3xl">
                <p class="text-sm font-black uppercase tracking-widest text-blue-600">Proje Bilgileri</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">Kapsam ve teslim ozeti</h2>
            </div>
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($projectInfo as $info)
                    <article class="rounded-3xl border border-slate-200 bg-slate-50 p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-2xl hover:shadow-blue-100">
                        <p class="text-xs font-black uppercase tracking-widest text-blue-600">{{ $info['label'] }}</p>
                        <p class="mt-3 text-lg font-black text-slate-950">{{ $info['value'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-20">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-8">
            <div>
                <p class="text-sm font-black uppercase tracking-widest text-blue-600">Proje Aciklamasi</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">{{ $portfolio->title }} nasil deger uretti?</h2>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/60">
                <div class="prose prose-slate max-w-none text-sm leading-8 text-slate-700">
                    {!! nl2br(e($detailText)) !!}
                </div>
                @if ($portfolio->project_url)
                    <a href="{{ $portfolio->project_url }}" target="_blank" rel="noreferrer" class="mt-6 inline-flex rounded-2xl bg-slate-950 px-5 py-3 text-sm font-black text-white shadow-lg shadow-slate-300 transition hover:-translate-y-0.5 hover:bg-blue-700">Projeyi Ziyaret Et</a>
                @endif
            </div>
        </div>
    </section>

    <section class="bg-slate-950 py-20 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12 flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
                <div class="max-w-3xl">
                    <p class="text-sm font-black uppercase tracking-widest text-blue-300">Galeri</p>
                    <h2 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">Projeden secili ekranlar</h2>
                </div>
                <div class="rounded-full border border-blue-300/20 bg-blue-400/10 px-4 py-2 text-xs font-black uppercase tracking-widest text-blue-100">Portfolio Showcase</div>
            </div>

            @if ($galleryImages->isNotEmpty())
                <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($galleryImages as $image)
                        <div class="overflow-hidden rounded-3xl border border-white/10 bg-white/[0.04] p-3 shadow-2xl shadow-slate-950/30 transition hover:-translate-y-1 hover:border-blue-300/40">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($image) }}" alt="{{ $portfolio->title }} galeri gorseli" class="aspect-[4/3] w-full rounded-[1.35rem] object-cover">
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-3xl border border-white/10 bg-[linear-gradient(135deg,#020617_0%,#1d4ed8_52%,#6d28d9_100%)] p-8 shadow-2xl shadow-blue-950/40">
                    <div class="aspect-[16/7] rounded-3xl border border-white/15 bg-white/10 p-6 backdrop-blur">
                        <div class="h-3 w-32 rounded-full bg-cyan-200"></div>
                        <div class="mt-8 grid h-3/5 grid-cols-3 gap-5">
                            <span class="rounded-3xl bg-white/20"></span>
                            <span class="rounded-3xl bg-white/30"></span>
                            <span class="rounded-3xl bg-white/20"></span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <section class="bg-white py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 max-w-3xl">
                <p class="text-sm font-black uppercase tracking-widest text-blue-600">Teknoloji</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">Kullanilan modern altyapi</h2>
            </div>
            <div class="flex flex-wrap gap-3">
                @foreach ($techBadges as $tech)
                    <span class="rounded-full border border-blue-100 bg-blue-50 px-5 py-3 text-sm font-black text-blue-700 shadow-sm">{{ $tech }}</span>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-[2rem] bg-[linear-gradient(135deg,#020617_0%,#1d4ed8_56%,#6d28d9_100%)] p-8 text-white shadow-2xl shadow-slate-300 md:p-10">
                <div class="grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
                    <div>
                        <p class="text-sm font-black uppercase tracking-widest text-blue-100">Benzer Bir Proje Mi Istiyorsunuz?</p>
                        <h2 class="mt-3 max-w-3xl text-3xl font-black tracking-tight sm:text-4xl">Markaniz icin premium dijital cozum planlayalim</h2>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-blue-100">Ihtiyacinizi anlatin; kapsam, surec ve teknik yol haritasini birlikte netlestirelim.</p>
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

    @if ($relatedPortfolios->isNotEmpty())
        <section class="bg-white py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-10 max-w-3xl">
                    <p class="text-sm font-black uppercase tracking-widest text-blue-600">Diger Referanslar</p>
                    <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">Argnest proje vitrininden secimler</h2>
                </div>
                <div class="grid gap-5 md:grid-cols-3">
                    @foreach ($relatedPortfolios as $relatedPortfolio)
                        <a href="{{ route('frontend.references.show', $relatedPortfolio) }}" class="rounded-3xl border border-slate-200 bg-slate-50 p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl">
                            <p class="mb-3 text-xs font-black uppercase tracking-widest text-blue-600">{{ $relatedPortfolio->client_name ?: 'Referans' }}</p>
                            <h3 class="text-lg font-black text-slate-950">{{ $relatedPortfolio->title }}</h3>
                            <p class="mt-3 text-sm leading-6 text-slate-600">{{ $relatedPortfolio->short_description ?: 'Argnest tarafindan planlanan premium dijital proje.' }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
