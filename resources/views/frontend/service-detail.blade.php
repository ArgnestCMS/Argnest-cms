@extends('frontend.layout')

@php
    $serviceImageUrl = $service->cover_image ? \Illuminate\Support\Facades\Storage::url($service->cover_image) : null;
    $serviceIconUrl = $service->icon ? \Illuminate\Support\Facades\Storage::url($service->icon) : null;
    $contactUrl = route('home') . '#teklif';
    $homeUrl = route('home');
    $whatsappNumber = preg_replace('/\D+/', '', $settings?->whatsapp ?: $settings?->phone ?: '');
    $detailText = $service->description ?: $service->short_description ?: 'Bu hizmet, markanizin dijital varligini daha guvenli, yonetilebilir ve olceklenebilir hale getirmek icin ihtiyaca ozel planlanir.';
    $advantages = [
        ['title' => 'Stratejik Planlama', 'text' => 'Ihtiyaclar, hedef kitle ve operasyon akisi netlestirilerek dogru yol haritasi cikarilir.', 'icon' => 'heroicon-o-map'],
        ['title' => 'Kurumsal Tasarim', 'text' => 'Marka guvenini artiran, hizli ve mobil uyumlu arayuz deneyimi olusturulur.', 'icon' => 'heroicon-o-sparkles'],
        ['title' => 'Yonetilebilir Altyapi', 'text' => 'Panel, icerik ve teknik yapi uzun vadeli kullanim icin sade tutulur.', 'icon' => 'heroicon-o-cog-6-tooth'],
        ['title' => 'Performans ve SEO', 'text' => 'Yayin sonrasi hiz, teknik SEO ve olceklenebilirlik odağı korunur.', 'icon' => 'heroicon-o-chart-bar-square'],
    ];
    $processSteps = [
        ['title' => 'Analiz', 'text' => 'Hedefler, mevcut durum ve teknik ihtiyaclar netlestirilir.'],
        ['title' => 'Tasarim', 'text' => 'Kurumsal kimlige uygun ekran ve deneyim akisi hazirlanir.'],
        ['title' => 'Gelistirme', 'text' => 'Laravel tabanli, guvenli ve yonetilebilir altyapi kodlanir.'],
        ['title' => 'Teslim', 'text' => 'Test, yayin ve temel kullanim aktarimi birlikte tamamlanir.'],
    ];
@endphp

@section('title', ($service->seo_title ?: $service->title) . ' | Argnest')
@section('description', $service->seo_description ?: $service->short_description ?: 'Argnest hizmet detay sayfasi.')
@section('image', $serviceImageUrl ?: ($settings?->logo ? asset('storage/' . $settings->logo) : asset('favicon.ico')))

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.34),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.28),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_56%,#111827_100%)]"></div>
        <div class="relative mx-auto grid max-w-7xl gap-10 px-4 py-20 sm:px-6 lg:grid-cols-[0.95fr_1.05fr] lg:px-8 lg:py-24">
            <div class="flex flex-col justify-center">
                <div class="mb-7 flex flex-wrap items-center gap-3">
                    <a href="{{ $homeUrl }}#hizmetler" class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-black uppercase tracking-widest text-blue-100 transition hover:border-blue-200">Hizmetler</a>
                    <span class="rounded-full bg-blue-400/15 px-3 py-1.5 text-xs font-black uppercase tracking-widest text-blue-100">Premium Landing</span>
                </div>
                <h1 class="text-4xl font-black tracking-tight sm:text-5xl lg:text-6xl">{{ $service->title }}</h1>
                <p class="mt-6 max-w-2xl text-base leading-8 text-slate-300 sm:text-lg">
                    {{ $service->short_description ?: 'Is hedeflerinize gore planlanan, modern ve surdurulebilir dijital hizmet altyapisi.' }}
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
                    @if ($serviceImageUrl)
                        <img src="{{ $serviceImageUrl }}" alt="{{ $service->title }}" class="aspect-[16/11] w-full rounded-[1.5rem] object-cover">
                    @else
                        <div class="aspect-[16/11] rounded-[1.5rem] bg-[linear-gradient(135deg,#020617_0%,#1d4ed8_52%,#6d28d9_100%)] p-6">
                            <div class="grid h-full grid-cols-[0.75fr_1fr] gap-5 rounded-3xl border border-white/15 bg-slate-950/35 p-5 backdrop-blur">
                                <div class="rounded-3xl bg-white/10 p-5">
                                    @if ($serviceIconUrl)
                                        <img src="{{ $serviceIconUrl }}" alt="{{ $service->title }}" class="h-12 w-12 object-contain">
                                    @else
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-blue-700">
                                            <x-heroicon-o-sparkles class="h-7 w-7" />
                                        </div>
                                    @endif
                                    <div class="mt-8 space-y-3">
                                        <span class="block h-3 rounded-full bg-white/70"></span>
                                        <span class="block h-3 w-4/5 rounded-full bg-white/35"></span>
                                        <span class="block h-3 w-2/3 rounded-full bg-white/25"></span>
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
        <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-8">
            <div>
                <p class="text-sm font-black uppercase tracking-widest text-blue-600">Hizmet Icerigi</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">{{ $service->title }} kapsaminda neler var?</h2>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6 shadow-xl shadow-slate-200/60">
                <div class="prose prose-slate max-w-none text-sm leading-8 text-slate-700">
                    {!! nl2br(e($detailText)) !!}
                </div>
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12 max-w-3xl">
                <p class="text-sm font-black uppercase tracking-widest text-blue-600">Avantajlar</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">Kurumsal faydaya odaklanan hizmet yapisi</h2>
            </div>
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($advantages as $advantage)
                    <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-2xl hover:shadow-blue-100">
                        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-linear-to-br from-slate-950 via-blue-700 to-violet-600 text-white shadow-lg shadow-blue-200">
                            <x-dynamic-component :component="$advantage['icon']" class="h-6 w-6" />
                        </div>
                        <h3 class="font-black text-slate-950">{{ $advantage['title'] }}</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $advantage['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-slate-950 py-20 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12 max-w-3xl">
                <p class="text-sm font-black uppercase tracking-widest text-blue-300">Surec</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">Analizden teslimata net proje akisi</h2>
            </div>
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($processSteps as $index => $step)
                    <article class="rounded-3xl border border-white/10 bg-white/5 p-6">
                        <div class="mb-6 flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-500 text-sm font-black shadow-lg shadow-blue-950/30">{{ $index + 1 }}</div>
                        <h3 class="font-black">{{ $step['title'] }}</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-300">{{ $step['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-[2rem] bg-[linear-gradient(135deg,#020617_0%,#1d4ed8_58%,#6d28d9_100%)] p-8 text-white shadow-2xl shadow-slate-300 md:p-10">
                <div class="grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
                    <div>
                        <p class="text-sm font-black uppercase tracking-widest text-blue-100">Birlikte Planlayalim</p>
                        <h2 class="mt-3 max-w-3xl text-3xl font-black tracking-tight sm:text-4xl">{{ $service->title }} icin ucretsiz teklif alin</h2>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-blue-100">Ihtiyacinizi anlatin; kapsam, surec ve teknik yol haritasini netlestirelim.</p>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row lg:flex-col xl:flex-row">
                        <a href="{{ $contactUrl }}" class="rounded-xl bg-white px-6 py-3.5 text-center text-sm font-black text-slate-950 shadow-lg shadow-slate-950/20 transition hover:-translate-y-0.5">Ucretsiz Teklif Al</a>
                        @if ($whatsappNumber)
                            <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" rel="noreferrer" class="rounded-xl border border-white/20 px-6 py-3.5 text-center text-sm font-black text-white transition hover:border-white hover:bg-white/10">Hemen Iletisime Gec</a>
                        @else
                            <a href="{{ $contactUrl }}" class="rounded-xl border border-white/20 px-6 py-3.5 text-center text-sm font-black text-white transition hover:border-white hover:bg-white/10">Hemen Iletisime Gec</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($relatedServices->isNotEmpty())
        <section class="bg-slate-50 py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-10 max-w-3xl">
                    <p class="text-sm font-black uppercase tracking-widest text-blue-600">Diger Hizmetler</p>
                    <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">Argnest ile tamamlayici cozumler</h2>
                </div>
                <div class="grid gap-5 md:grid-cols-3">
                    @foreach ($relatedServices as $relatedService)
                        <a href="{{ route('frontend.services.show', $relatedService) }}" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl">
                            <h3 class="text-lg font-black text-slate-950">{{ $relatedService->title }}</h3>
                            <p class="mt-3 text-sm leading-6 text-slate-600">{{ $relatedService->short_description ?: 'Argnest hizmet ekosisteminin tamamlayici parcasi.' }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
