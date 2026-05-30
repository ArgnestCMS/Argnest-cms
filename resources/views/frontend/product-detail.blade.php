@extends('frontend.layout')

@php
    $productImageUrl = $product->cover_image ? \Illuminate\Support\Facades\Storage::url($product->cover_image) : null;
    $contactUrl = route('home') . '#teklif';
    $whatsappNumber = preg_replace('/\D+/', '', $settings?->whatsapp ?: $settings?->phone ?: '');
    $statusLabel = $productStatusOptions[$product->product_status] ?? 'Aktif';
    $detailText = $product->description ?: $product->short_description ?: 'Argnest urun ekosisteminin parcasi olan bu SaaS cozum, kurumsal operasyonlari daha guvenli, hizli ve yonetilebilir hale getirmek icin tasarlanir.';
    $featureMap = [
        'argnest-cms' => [
            ['title' => 'Icerik Yonetimi', 'text' => 'Sayfa, hizmet, blog ve teklif alanlari tek panelden kolayca yonetilir.', 'icon' => 'heroicon-o-document-text'],
            ['title' => 'SEO Hazir Altyapi', 'text' => 'Teknik SEO, meta alanlari ve yayin akisi kurumsal gorunurluk icin planlanir.', 'icon' => 'heroicon-o-magnifying-glass'],
            ['title' => 'Rol Bazli Panel', 'text' => 'Ekiplerin yetki seviyelerine gore guvenli panel deneyimi saglanir.', 'icon' => 'heroicon-o-user-group'],
            ['title' => 'Moduler Mimari', 'text' => 'Yeni sayfalar, moduller ve is akislari ihtiyaca gore genisletilebilir.', 'icon' => 'heroicon-o-squares-2x2'],
        ],
        'argnest-crm' => [
            ['title' => 'Musteri Takibi', 'text' => 'Musteri kartlari, gorusmeler ve firsatlar tek merkezde toplanir.', 'icon' => 'heroicon-o-users'],
            ['title' => 'Teklif Sureci', 'text' => 'Talep, teklif ve satis asamalari gorunur ve takip edilebilir hale gelir.', 'icon' => 'heroicon-o-clipboard-document-check'],
            ['title' => 'Raporlama', 'text' => 'Operasyon ve satis performansi icin anlasilir raporlar uretilir.', 'icon' => 'heroicon-o-chart-bar-square'],
        ],
        'argnest-fit' => [
            ['title' => 'Uye Yonetimi', 'text' => 'Uyelik, paket ve durum takibi salon operasyonu icin sadelestirilir.', 'icon' => 'heroicon-o-identification'],
            ['title' => 'Randevu Akisi', 'text' => 'Ders, seans ve egitmen planlamasi tek ekranda izlenir.', 'icon' => 'heroicon-o-calendar-days'],
            ['title' => 'Operasyon Paneli', 'text' => 'Gunluk salon isleri, takipler ve bilgilendirmeler panelden yonetilir.', 'icon' => 'heroicon-o-rectangle-group'],
        ],
    ];
    $features = $featureMap[$product->slug] ?? [
        ['title' => 'Modern Panel', 'text' => 'Kurumsal ekiplerin rahat kullanacagi sade ve guvenli panel deneyimi.', 'icon' => 'heroicon-o-computer-desktop'],
        ['title' => 'Verimli Is Akisi', 'text' => 'Tekrarlayan operasyonlari azaltan, takip edilebilir surecler.', 'icon' => 'heroicon-o-bolt'],
        ['title' => 'Raporlanabilir Yapi', 'text' => 'Verileri daha anlamli okumanizi saglayan yonetim gorunurlugu.', 'icon' => 'heroicon-o-chart-pie'],
    ];
    $techBadges = ['Laravel', 'Filament', 'Tailwind', 'MySQL'];
    $advantages = [
        ['title' => 'Hizli', 'text' => 'Performans odakli arayuz ve sade is akislari.', 'icon' => 'heroicon-o-bolt'],
        ['title' => 'Guvenli', 'text' => 'Yetkilendirme, veri kontrolu ve guvenli altyapi yaklasimi.', 'icon' => 'heroicon-o-shield-check'],
        ['title' => 'Yonetilebilir', 'text' => 'Panelden kolayca kontrol edilen moduler yapi.', 'icon' => 'heroicon-o-adjustments-horizontal'],
        ['title' => 'Olceklenebilir', 'text' => 'Yeni ihtiyaclara gore genisleyebilen urun mimarisi.', 'icon' => 'heroicon-o-arrows-pointing-out'],
    ];
@endphp

@section('title', ($product->seo_title ?: $product->title) . ' | Argnest')
@section('description', $product->seo_description ?: $product->short_description ?: 'Argnest urun detay sayfasi.')

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(59,130,246,0.34),transparent_34%),radial-gradient(circle_at_82%_10%,rgba(124,58,237,0.30),transparent_32%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto grid max-w-7xl gap-10 px-4 py-20 sm:px-6 lg:grid-cols-[0.95fr_1.05fr] lg:px-8 lg:py-24">
            <div class="flex flex-col justify-center">
                <div class="mb-7 flex flex-wrap items-center gap-3">
                    <a href="{{ route('home') }}#urunler" class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-black uppercase tracking-widest text-blue-100 transition hover:border-blue-200">Urunler</a>
                    <span class="rounded-full bg-cyan-300 px-3 py-1.5 text-xs font-black uppercase tracking-widest text-slate-950">{{ $statusLabel }}</span>
                    @if ($product->is_featured)
                        <span class="rounded-full bg-blue-400/15 px-3 py-1.5 text-xs font-black uppercase tracking-widest text-blue-100">Ana Vitrin</span>
                    @endif
                </div>
                <h1 class="text-4xl font-black tracking-tight sm:text-5xl lg:text-6xl">{{ $product->title }}</h1>
                <p class="mt-6 max-w-2xl text-base leading-8 text-slate-300 sm:text-lg">
                    {{ $product->short_description ?: 'Kurumsal ekipler icin tasarlanan, premium ve yonetilebilir Argnest SaaS urunu.' }}
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ $contactUrl }}" class="rounded-2xl bg-cyan-300 px-6 py-3.5 text-center text-sm font-black text-slate-950 shadow-2xl shadow-cyan-500/20 transition hover:-translate-y-0.5 hover:bg-cyan-200">Demo Talep Et</a>
                    <a href="{{ $contactUrl }}" class="rounded-2xl border border-white/15 px-6 py-3.5 text-center text-sm font-black text-white transition hover:-translate-y-0.5 hover:border-cyan-200 hover:text-cyan-100">Teklif Al</a>
                </div>
            </div>

            <div class="relative">
                <div class="rounded-[2rem] border border-white/10 bg-white/[0.04] p-4 shadow-2xl shadow-blue-950/40 backdrop-blur">
                    <div class="flex items-center gap-2 rounded-t-[1.5rem] border border-white/10 border-b-0 bg-white/5 px-5 py-4">
                        <span class="h-2.5 w-2.5 rounded-full bg-red-400"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-amber-300"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-300"></span>
                        <span class="ml-auto text-xs font-black uppercase tracking-widest text-slate-400">SaaS Preview</span>
                    </div>
                    @if ($productImageUrl)
                        <img src="{{ $productImageUrl }}" alt="{{ $product->title }}" class="aspect-[16/11] w-full rounded-b-[1.5rem] object-cover">
                    @else
                        <div class="aspect-[16/11] rounded-b-[1.5rem] bg-[linear-gradient(135deg,#020617_0%,#1d4ed8_48%,#7c3aed_100%)] p-5">
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
                                            <span class="h-20 rounded-2xl bg-cyan-100"></span>
                                            <span class="h-20 rounded-2xl bg-blue-100"></span>
                                            <span class="h-20 rounded-2xl bg-violet-100"></span>
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
                <p class="text-sm font-black uppercase tracking-widest text-blue-600">Urun Icerigi</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">{{ $product->title }} nasil deger uretir?</h2>
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
                <p class="text-sm font-black uppercase tracking-widest text-blue-600">Ozellikler</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">SaaS deneyimini guclendiren moduller</h2>
            </div>
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($features as $feature)
                    <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-2xl hover:shadow-blue-100">
                        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-linear-to-br from-slate-950 via-blue-700 to-violet-600 text-white shadow-lg shadow-blue-200">
                            <x-dynamic-component :component="$feature['icon']" class="h-6 w-6" />
                        </div>
                        <h3 class="font-black text-slate-950">{{ $feature['title'] }}</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $feature['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-slate-950 py-20 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
                <div class="max-w-3xl">
                    <p class="text-sm font-black uppercase tracking-widest text-blue-300">Teknoloji</p>
                    <h2 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">Modern ve surdurulebilir altyapi</h2>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach ($techBadges as $tech)
                        <span class="rounded-full border border-white/10 bg-white/10 px-4 py-2 text-xs font-black text-cyan-100">{{ $tech }}</span>
                    @endforeach
                </div>
            </div>
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($advantages as $advantage)
                    <article class="rounded-3xl border border-white/10 bg-white/5 p-6">
                        <div class="mb-5 flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-500 text-white shadow-lg shadow-blue-950/30">
                            <x-dynamic-component :component="$advantage['icon']" class="h-6 w-6" />
                        </div>
                        <h3 class="font-black">{{ $advantage['title'] }}</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-300">{{ $advantage['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-[2rem] bg-[linear-gradient(135deg,#020617_0%,#1d4ed8_56%,#6d28d9_100%)] p-8 text-white shadow-2xl shadow-slate-300 md:p-10">
                <div class="grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
                    <div>
                        <p class="text-sm font-black uppercase tracking-widest text-blue-100">Urunu Incelemek Ister misiniz?</p>
                        <h2 class="mt-3 max-w-3xl text-3xl font-black tracking-tight sm:text-4xl">{{ $product->title }} icin demo ve teklif planlayalim</h2>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-blue-100">Ihtiyacinizi anlatin; urunun isletmenize nasil uyarlanacagini birlikte netlestirelim.</p>
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

    @if ($relatedProducts->isNotEmpty())
        <section class="bg-slate-50 py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-10 max-w-3xl">
                    <p class="text-sm font-black uppercase tracking-widest text-blue-600">Diger Urunler</p>
                    <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">Argnest urun ekosistemi</h2>
                </div>
                <div class="grid gap-5 md:grid-cols-3">
                    @foreach ($relatedProducts as $relatedProduct)
                        <a href="{{ route('frontend.products.show', $relatedProduct) }}" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl">
                            <p class="mb-3 text-xs font-black uppercase tracking-widest text-blue-600">{{ $productStatusOptions[$relatedProduct->product_status] ?? 'Aktif' }}</p>
                            <h3 class="text-lg font-black text-slate-950">{{ $relatedProduct->title }}</h3>
                            <p class="mt-3 text-sm leading-6 text-slate-600">{{ $relatedProduct->short_description ?: 'Argnest SaaS ekosisteminin tamamlayici urunu.' }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
