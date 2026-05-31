@extends('frontend.layout')

@php
    $productStatusOptions = \App\Models\Product::statusOptions();
    $whatsappNumber = preg_replace('/\D+/', '', $settings?->whatsapp ?: $settings?->phone ?: '');
    $featuredProduct = $products->firstWhere('slug', 'argnest-cms') ?? $products->firstWhere('is_featured', true) ?? $products->first();
    $secondaryProducts = $products->reject(fn ($product) => $featuredProduct && $product->id === $featuredProduct->id);
    $productFeatureMap = [
        'argnest-cms' => ['Sayfa ve icerik yonetimi', 'SEO odakli yayin altyapisi', 'Teklif, hizmet ve blog modulleri', 'Rol bazli panel deneyimi'],
        'argnest-crm' => ['Musteri kartlari', 'Talep ve teklif takibi', 'Satis sureci gorunurlugu'],
        'argnest-fit' => ['Uye ve paket yonetimi', 'Randevu ve ders planlama', 'Salon operasyon paneli'],
    ];
    $productTechMap = [
        'argnest-cms' => ['Laravel', 'Filament', 'Tailwind CSS', 'SEO Ready'],
        'argnest-crm' => ['Laravel', 'Workflow', 'Reports'],
        'argnest-fit' => ['Laravel', 'Booking', 'Membership'],
    ];
    $trustBadges = ['Mobil Uyumlu', 'SEO Odaklı', 'Güvenli Altyapı', 'Teknik Destek'];
    $serviceIconMap = [
        'Kurumsal Web Siteleri' => 'heroicon-o-globe-alt',
        'Özel Yazılım Çözümleri' => 'heroicon-o-code-bracket-square',
        'Müşteri Takip Sistemleri' => 'heroicon-o-users',
        'Hosting ve Sunucu Hizmetleri' => 'heroicon-o-server-stack',
        'SEO ve Google Çözümleri' => 'heroicon-o-chart-bar-square',
        'Oto Galeri Yazılımları' => 'heroicon-o-truck',
        'kurumsal-web-siteleri' => 'heroicon-o-globe-alt',
        'ozel-yazilim-cozumleri' => 'heroicon-o-code-bracket-square',
        'musteri-takip-sistemleri' => 'heroicon-o-users',
        'hosting-ve-sunucu-hizmetleri' => 'heroicon-o-server-stack',
        'seo-ve-google-cozumleri' => 'heroicon-o-chart-bar-square',
        'oto-galeri-yazilimlari' => 'heroicon-o-truck',
    ];
    $comparisonRows = [
        ['label' => 'Özelleştirme', 'ready' => 'Tema sınırları içinde kalır', 'argnest' => 'Markaya özel arayüz ve akış tasarlanır'],
        ['label' => 'Yönetim Paneli', 'ready' => 'Kısıtlı ve standart panel', 'argnest' => 'İhtiyaca özel Filament panel'],
        ['label' => 'SEO', 'ready' => 'Genel ayarlar ve sınırlı kontrol', 'argnest' => 'Teknik SEO ve içerik yönetimi birlikte'],
        ['label' => 'Veri Kontrolü', 'ready' => 'Platforma bağımlı veri yapısı', 'argnest' => 'Veri ve altyapı üzerinde tam kontrol'],
        ['label' => 'Destek', 'ready' => 'Genel destek süreçleri', 'argnest' => 'Projeye hakim teknik ekip'],
        ['label' => 'Ölçeklenebilirlik', 'ready' => 'Paket limitlerine bağlı büyüme', 'argnest' => 'İşletmeyle birlikte genişleyen mimari'],
    ];
    $heroButtonClasses = [
        'primary' => 'bg-linear-to-r from-violet-600 to-blue-600 text-white shadow-2xl shadow-blue-600/35 hover:shadow-violet-500/35',
        'outline' => 'border border-violet-400/50 bg-slate-950/35 text-white shadow-lg shadow-slate-950/20 backdrop-blur hover:border-violet-300 hover:bg-white/10',
        'secondary' => 'border border-blue-300/50 bg-slate-950/35 text-blue-200 shadow-lg shadow-slate-950/20 backdrop-blur hover:border-blue-200 hover:bg-blue-300/10',
        'whatsapp' => 'border border-emerald-300/50 bg-slate-950/35 text-cyan-200 shadow-lg shadow-slate-950/20 backdrop-blur hover:border-cyan-200 hover:bg-emerald-300/10',
    ];
@endphp

@section('content')
    <section
        class="relative overflow-hidden border-b border-slate-900/10 bg-slate-950 bg-cover bg-center"
        @if ($settings?->hero_background) style="background-image: url('{{ asset('storage/' . $settings->hero_background) }}')" @endif
    >
        @if ($settings?->hero_background)
            <div class="absolute inset-0 bg-slate-950/60"></div>
        @else
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_18%_18%,rgba(37,99,235,0.32),transparent_34%),radial-gradient(circle_at_82%_12%,rgba(124,58,237,0.24),transparent_32%),linear-gradient(135deg,#020617_0%,#0f172a_52%,#111827_100%)]"></div>
        @endif
        <div class="absolute inset-x-0 top-0 h-px bg-linear-to-r from-transparent via-blue-200/70 to-transparent"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8 lg:py-20">
            @if ($settings?->hero_banner)
                <img
                    src="{{ asset('storage/' . $settings->hero_banner) }}"
                    alt="Argnest ana sayfa hero banner"
                    class="w-full rounded-[2rem] object-cover shadow-2xl shadow-slate-950/40"
                >
            @else
                <div class="overflow-hidden rounded-[2rem] border border-white/10 bg-white/[0.04] shadow-2xl shadow-slate-950/30 backdrop-blur">
                    <div class="relative bg-slate-950 px-6 py-16 text-center sm:px-10 lg:px-16 lg:py-24">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_22%_20%,rgba(59,130,246,0.22),transparent_32%),radial-gradient(circle_at_78%_12%,rgba(99,102,241,0.18),transparent_34%),linear-gradient(135deg,#020617_0%,#0f172a_58%,#111827_100%)]"></div>
                        <div class="relative mx-auto max-w-4xl">
                            <h1 class="text-4xl font-black tracking-tight text-white sm:text-5xl lg:text-6xl">
                                &#304;&#351;letmeniz &#304;&ccedil;in Modern Dijital &Ccedil;&ouml;z&uuml;mler
                            </h1>
                            <p class="mx-auto mt-6 max-w-2xl text-base leading-8 text-slate-300 sm:text-lg">
                                Kurumsal web siteleri, &ouml;zel yaz&#305;l&#305;mlar, m&uuml;&#351;teri takip sistemleri, hosting ve dijital b&uuml;y&uuml;me &ccedil;&ouml;z&uuml;mleri geli&#351;tiriyoruz.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($heroButtons->isNotEmpty())
                <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                    @foreach ($heroButtons as $button)
                        <a
                            href="{{ $button->url }}"
                            @if ($button->target === \App\Models\HeroButton::TARGET_BLANK) target="_blank" rel="noreferrer" @endif
                            class="rounded-2xl px-6 py-3.5 text-center text-sm font-black transition duration-300 hover:scale-105 {{ $heroButtonClasses[$button->style] ?? $heroButtonClasses['primary'] }}"
                        >
                            {{ $button->title }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
    <section class="bg-white py-10">
        <div class="mx-auto grid max-w-7xl gap-4 px-4 sm:px-6 md:grid-cols-2 lg:grid-cols-4 lg:px-8">
            @foreach ($stats as $stat)
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-4xl font-black text-slate-950">{{ $stat['value'] }}</p>
                    <p class="mt-2 text-sm font-bold text-slate-500">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section id="hizmetler" class="bg-slate-50 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12 flex flex-col justify-between gap-5 lg:flex-row lg:items-end">
                <div class="max-w-2xl">
                    <p class="text-sm font-black uppercase tracking-widest text-blue-600">Hizmetler</p>
                    <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">Dijital büyüme için uçtan uca hizmetler</h2>
                </div>
                <p class="max-w-xl text-sm leading-6 text-slate-600">Strateji, tasarım, yazılım, yayın ve sürdürülebilir geliştirme tek süreçte ele alınır.</p>
            </div>
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($services as $service)
                    <article class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-2xl hover:shadow-blue-100">
                        <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-linear-to-br from-slate-950 via-blue-800 to-blue-500 shadow-lg shadow-blue-200">
                            @if ($service->icon)
                                <img src="{{ asset('storage/' . $service->icon) }}" alt="{{ $service->title }}" class="h-8 w-8 object-contain">
                            @else
                                <x-dynamic-component
                                    :component="$serviceIconMap[$service->title] ?? $serviceIconMap[$service->slug] ?? 'heroicon-o-sparkles'"
                                    class="h-7 w-7 text-white"
                                />
                            @endif
                        </div>
                        <h3 class="text-lg font-black text-slate-950">{{ $service->title }}</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $service->short_description ?: 'İş hedeflerinize göre ölçeklenebilir, hızlı ve yönetilebilir çözüm.' }}</p>
                        <div class="mt-6 h-px bg-slate-100"></div>
                        <a href="{{ route('frontend.services.show', $service) }}" class="mt-4 inline-flex text-sm font-black text-slate-950 transition hover:text-blue-700">Detay -&gt;</a>
                        <p class="mt-4 text-sm font-bold text-blue-700">Detaylı planlama ve sürdürülebilir geliştirme</p>
                    </article>
                @empty
                    <p class="text-slate-600">Aktif hizmet kaydı bulunamadı.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section id="urunler" class="overflow-hidden bg-slate-950 py-20 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12 flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
                <div class="max-w-3xl">
                    <p class="text-sm font-black uppercase tracking-widest text-cyan-300">&Uuml;r&uuml;nler</p>
                    <h2 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">Argnest SaaS &uuml;r&uuml;n vitrini</h2>
                    <p class="mt-4 text-base leading-7 text-slate-300">Argnest CMS ana vitrin &uuml;r&uuml;n&uuml; olarak konumlan&#305;r; CRM ve Fit ayn&#305; ekosistemin operasyon odakl&#305; yan &uuml;r&uuml;nleri olarak b&uuml;y&uuml;r.</p>
                </div>
                <div class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-black uppercase tracking-widest text-slate-300">CMS First Showcase</div>
            </div>
            @if ($featuredProduct)
                @php
                    $featuredImageUrl = $featuredProduct->cover_image ? \Illuminate\Support\Facades\Storage::url($featuredProduct->cover_image) : null;
                    $featuredFeatures = $productFeatureMap[$featuredProduct->slug] ?? ['Moduler panel altyapisi', 'Guvenli icerik yonetimi', 'Olceklenebilir urun mimarisi'];
                    $featuredTechs = $productTechMap[$featuredProduct->slug] ?? ['Laravel', 'Filament', 'Tailwind CSS'];
                @endphp
                <div class="grid gap-6 lg:grid-cols-[1.35fr_0.75fr]">
                    <article class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/[0.03] p-5 shadow-2xl shadow-cyan-950/40 sm:p-7">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_14%_12%,rgba(34,211,238,0.22),transparent_34%),radial-gradient(circle_at_86%_18%,rgba(59,130,246,0.20),transparent_32%),linear-gradient(135deg,rgba(15,23,42,0.20),rgba(2,6,23,0.96))]"></div>
                        <div class="relative grid gap-8 xl:grid-cols-[0.92fr_1.08fr] xl:items-center">
                            <div class="p-1">
                                <div class="mb-7 flex flex-wrap items-center gap-3">
                                    <span class="rounded-full bg-emerald-400/10 px-3 py-1.5 text-xs font-black text-emerald-200 ring-1 ring-emerald-300/25">{{ $productStatusOptions[$featuredProduct->product_status] ?? 'Aktif' }}</span>
                                    <span class="rounded-full bg-cyan-300 px-3 py-1.5 text-xs font-black text-slate-950">Ana Vitrin &Uuml;r&uuml;n&uuml;</span>
                                </div>
                                <h3 class="text-3xl font-black tracking-tight sm:text-5xl">{{ $featuredProduct->title }}</h3>
                                <p class="mt-5 max-w-xl text-sm leading-7 text-slate-300 sm:text-base">
                                    {{ $featuredProduct->short_description ?: 'Argnest CMS; kurumsal web sitesi, hizmet, blog, teklif ve SEO yonetimini tek modern panelde birlestiren premium icerik yonetim altyapisidir.' }}
                                </p>
                                <div class="mt-7 grid gap-3 sm:grid-cols-2">
                                    @foreach ($featuredFeatures as $feature)
                                        <div class="flex items-start gap-3 rounded-2xl border border-white/10 bg-white/[0.04] p-4">
                                            <span class="mt-1 h-2 w-2 rounded-full bg-cyan-300 shadow-lg shadow-cyan-300/40"></span>
                                            <span class="text-sm font-bold leading-6 text-slate-100">{!! $feature !!}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-7 flex flex-wrap gap-2">
                                    @foreach ($featuredTechs as $tech)
                                        <span class="rounded-full border border-white/10 bg-white/10 px-3 py-1.5 text-xs font-black text-cyan-100">{{ $tech }}</span>
                                    @endforeach
                                </div>
                                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                                    <a href="#teklif" class="rounded-2xl bg-cyan-300 px-6 py-3.5 text-center text-sm font-black text-slate-950 shadow-xl shadow-cyan-500/20 transition hover:-translate-y-0.5 hover:bg-cyan-200">Demo &#304;ste</a>
                                    <a href="{{ route('frontend.products.show', $featuredProduct) }}" class="rounded-2xl border border-white/15 px-6 py-3.5 text-center text-sm font-black text-white transition hover:-translate-y-0.5 hover:border-cyan-200 hover:text-cyan-100">Detaylar&#305; G&ouml;r</a>
                                </div>
                            </div>
                            <div class="relative">
                                <div class="rounded-[1.75rem] border border-white/15 bg-slate-900/80 p-3 shadow-2xl shadow-slate-950/60 backdrop-blur">
                                    <div class="flex items-center gap-2 rounded-t-[1.25rem] border border-white/10 border-b-0 bg-white/5 px-4 py-3">
                                        <span class="h-2.5 w-2.5 rounded-full bg-red-400"></span>
                                        <span class="h-2.5 w-2.5 rounded-full bg-amber-300"></span>
                                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-300"></span>
                                        <span class="ml-auto text-xs font-black uppercase tracking-widest text-slate-400">Argnest CMS</span>
                                    </div>
                                    @if ($featuredImageUrl)
                                        <img src="{{ $featuredImageUrl }}" alt="{{ $featuredProduct->title }}" class="aspect-[16/11] w-full rounded-b-[1.25rem] object-cover">
                                    @else
                                        <div class="aspect-[16/11] rounded-b-[1.25rem] bg-[linear-gradient(135deg,#0f172a_0%,#1d4ed8_48%,#22d3ee_100%)] p-5">
                                            <div class="grid h-full grid-cols-[0.72fr_1fr] gap-4 rounded-2xl border border-white/15 bg-slate-950/35 p-4 backdrop-blur">
                                                <div class="rounded-2xl bg-white/10 p-4">
                                                    <div class="h-3 w-20 rounded-full bg-cyan-200"></div>
                                                    <div class="mt-6 space-y-3">
                                                        <span class="block h-10 rounded-xl bg-white/15"></span>
                                                        <span class="block h-10 rounded-xl bg-white/10"></span>
                                                        <span class="block h-10 rounded-xl bg-white/15"></span>
                                                    </div>
                                                </div>
                                                <div class="grid gap-4">
                                                    <div class="rounded-2xl bg-white/90 p-4">
                                                        <div class="h-3 w-28 rounded-full bg-slate-300"></div>
                                                        <div class="mt-4 grid grid-cols-3 gap-3">
                                                            <span class="h-16 rounded-xl bg-cyan-100"></span>
                                                            <span class="h-16 rounded-xl bg-blue-100"></span>
                                                            <span class="h-16 rounded-xl bg-slate-100"></span>
                                                        </div>
                                                    </div>
                                                    <div class="rounded-2xl bg-white/15 p-4">
                                                        <div class="h-2 w-full rounded-full bg-white/25"></div>
                                                        <div class="mt-3 h-2 w-4/5 rounded-full bg-cyan-200/80"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </article>
                    <div class="grid gap-5">
                        @forelse ($secondaryProducts as $product)
                            @php
                                $productImageUrl = $product->cover_image ? \Illuminate\Support\Facades\Storage::url($product->cover_image) : null;
                                $productFeatures = $productFeatureMap[$product->slug] ?? ['Operasyon paneli', 'Raporlama', 'Argnest ekosistemi'];
                                $productTechs = $productTechMap[$product->slug] ?? ['Laravel', 'Panel', 'SaaS'];
                            @endphp
                            <article class="group overflow-hidden rounded-[1.75rem] border border-white/10 bg-white/[0.04] p-5 shadow-xl shadow-slate-950/20 transition hover:-translate-y-1 hover:border-cyan-300/40">
                                <div class="mb-5 overflow-hidden rounded-2xl border border-white/10 bg-slate-900">
                                    @if ($productImageUrl)
                                        <img src="{{ $productImageUrl }}" alt="{{ $product->title }}" class="h-36 w-full object-cover transition duration-500 group-hover:scale-105">
                                    @else
                                        <div class="h-36 bg-[linear-gradient(135deg,rgba(15,23,42,1),rgba(37,99,235,0.82),rgba(34,211,238,0.82))] p-4">
                                            <div class="h-full rounded-2xl border border-white/15 bg-white/10 p-4">
                                                <div class="h-2 w-20 rounded-full bg-white/80"></div>
                                                <div class="mt-5 grid grid-cols-3 gap-2">
                                                    <span class="h-12 rounded-xl bg-white/20"></span>
                                                    <span class="h-12 rounded-xl bg-white/30"></span>
                                                    <span class="h-12 rounded-xl bg-white/20"></span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="mb-4 flex flex-wrap items-center gap-2">
                                    <span class="rounded-full bg-white/10 px-3 py-1.5 text-xs font-black text-cyan-100">{{ $productStatusOptions[$product->product_status] ?? 'Aktif' }}</span>
                                    @if ($product->product_status === \App\Models\Product::STATUS_COMING_SOON)
                                        <span class="rounded-full bg-amber-300/15 px-3 py-1.5 text-xs font-black text-amber-100">Coming Soon</span>
                                    @endif
                                </div>
                                <h3 class="text-xl font-black">{{ $product->title }}</h3>
                                <p class="mt-3 text-sm leading-6 text-slate-300">{{ $product->short_description ?: 'Argnest urun yol haritasinin premium ve operasyon odakli parcasidir.' }}</p>
                                <div class="mt-5 space-y-2">
                                    @foreach (array_slice($productFeatures, 0, 3) as $feature)
                                        <p class="flex items-center gap-2 text-sm font-bold text-slate-200">
                                            <span class="h-1.5 w-1.5 rounded-full bg-cyan-300"></span>
                                            <span>{!! $feature !!}</span>
                                        </p>
                                    @endforeach
                                </div>
                                <div class="mt-5 flex flex-wrap gap-2">
                                    @foreach ($productTechs as $tech)
                                        <span class="rounded-full border border-white/10 px-2.5 py-1 text-[11px] font-black text-slate-300">{{ $tech }}</span>
                                    @endforeach
                                </div>
                                <div class="mt-6 grid grid-cols-2 gap-3">
                                    <a href="#teklif" class="rounded-xl bg-white px-4 py-3 text-center text-xs font-black text-slate-950 transition hover:bg-cyan-200">Demo</a>
                                    <a href="{{ route('frontend.products.show', $product) }}" class="rounded-xl border border-white/15 px-4 py-3 text-center text-xs font-black text-white transition hover:border-cyan-200">Detay</a>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-[1.75rem] border border-white/10 bg-white/[0.04] p-6 text-sm font-semibold text-slate-300">Yeni &uuml;r&uuml;nler haz&#305;rlan&#305;yor.</div>
                        @endforelse
                    </div>
                </div>
            @else
                <div class="rounded-[1.75rem] border border-white/10 bg-white/[0.04] p-6">
                    <p class="text-slate-300">Aktif &uuml;r&uuml;n kayd&#305; bulunamad&#305;.</p>
                </div>
            @endif
            @if (false)
            <div class="mb-12 max-w-3xl">
                <p class="text-sm font-black uppercase tracking-widest text-blue-600">Ürünler</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">Argnest ürün ailesi</h2>
                <p class="mt-4 text-base leading-7 text-slate-600">CMS, CRM ve sektörel yazılımlar; yönetilebilirlik, hız ve güvenlik odağıyla geliştirilir.</p>
            </div>
            @if ($featuredProduct)
                <div class="grid gap-5 lg:grid-cols-[1.45fr_0.85fr]">
                    <article class="overflow-hidden rounded-3xl border border-blue-200 bg-blue-50/70 p-7 shadow-xl shadow-blue-100">
                        <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
                            <div>
                                <div class="mb-7 flex flex-wrap items-center gap-3">
                                    <span class="rounded-full bg-white px-3 py-1.5 text-xs font-black text-blue-700 shadow-sm">{{ $productStatusOptions[$featuredProduct->product_status] ?? 'Aktif' }}</span>
                                    <span class="rounded-full bg-blue-600 px-3 py-1.5 text-xs font-black text-white">Öne Çıkan</span>
                                </div>
                                <h3 class="text-3xl font-black text-slate-950">{{ $featuredProduct->title }}</h3>
                                <p class="mt-4 text-sm leading-6 text-slate-600">{{ $featuredProduct->short_description ?: 'Argnest CMS; içerik, sayfa, teklif, hizmet ve kurumsal site yönetimini tek panelde toplayan modern altyapıdır.' }}</p>
                                <a href="#teklif" class="mt-7 inline-flex rounded-xl bg-blue-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-blue-600/20 hover:bg-blue-700">CMS için Görüşelim</a>
                            </div>
                            <div class="rounded-3xl border border-white/70 bg-white p-4 shadow-2xl shadow-blue-200">
                                <div class="mb-4 flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-widest text-blue-600">CMS Panel</p>
                                        <p class="text-lg font-black text-slate-950">İçerik Merkezi</p>
                                    </div>
                                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-black text-emerald-700">Aktif</span>
                                </div>
                                <div class="grid gap-3">
                                    @foreach ([['Sayfalar', 82], ['Hizmetler', 68], ['SEO Skoru', 94]] as [$label, $width])
                                        <div class="rounded-2xl bg-slate-50 p-4">
                                            <div class="mb-3 flex items-center justify-between text-xs font-black text-slate-600">
                                                <span>{{ $label }}</span>
                                                <span>{{ $width }}%</span>
                                            </div>
                                            <div class="h-2 rounded-full bg-white">
                                                <div class="h-2 rounded-full bg-blue-600" style="width: {{ $width }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </article>
                    <div class="grid gap-5">
                        @forelse ($secondaryProducts as $product)
                            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                                <div class="mb-5 flex flex-wrap items-center gap-2">
                                    <span class="rounded-full bg-slate-50 px-3 py-1.5 text-xs font-black text-blue-700">{{ $productStatusOptions[$product->product_status] ?? 'Aktif' }}</span>
                                    @if ($product->product_status === \App\Models\Product::STATUS_COMING_SOON)
                                        <span class="rounded-full bg-amber-100 px-3 py-1.5 text-xs font-black text-amber-800">Coming Soon</span>
                                    @endif
                                </div>
                                <h3 class="text-xl font-black text-slate-950">{{ $product->title }}</h3>
                                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $product->short_description ?: 'Planlanan Argnest ürün yol haritasının parçası.' }}</p>
                            </article>
                        @empty
                            <div class="rounded-3xl border border-slate-200 bg-white p-6 text-sm font-semibold text-slate-500">Yeni ürünler hazırlanıyor.</div>
                        @endforelse
                    </div>
                </div>
            @else
                <div>
                    <p class="text-slate-600">Aktif ürün kaydı bulunamadı.</p>
                </div>
            @endif
            @endif
        </div>
    </section>

    <section id="neden" class="bg-slate-50 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12 max-w-3xl">
                <p class="text-sm font-black uppercase tracking-widest text-blue-600">Neden Argnest?</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">Hazır site sınırlarının ötesinde tam kontrol</h2>
            </div>
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-black text-slate-950">Hazır Site Sistemleri</h3>
                    <div class="mt-6 space-y-3">
                        @foreach ($comparisonRows as $row)
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                <p class="text-sm font-black text-slate-900">{{ $row['label'] }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $row['ready'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="rounded-3xl border border-blue-200 bg-slate-950 p-6 text-white shadow-2xl shadow-slate-200">
                    <h3 class="text-xl font-black">Argnest Çözümleri</h3>
                    <div class="mt-6 space-y-3">
                        @foreach ($comparisonRows as $row)
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-sm font-black text-blue-200">{{ $row['label'] }}</p>
                                <p class="mt-1 text-sm text-slate-200">{{ $row['argnest'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="guvenlik" class="bg-slate-950 py-20 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12 max-w-3xl">
                <p class="text-sm font-black uppercase tracking-widest text-blue-300">Güvenlik ve KVKK</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">Kurumsal veriler için güvenilir dijital zemin</h2>
            </div>
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['title' => 'KVKK Uyumlu', 'text' => 'Veri işleme süreçlerinde gizlilik bilinci.'],
                    ['title' => 'SSL Güvenliği', 'text' => 'Güvenli bağlantı ve modern yayın altyapısı.'],
                    ['title' => 'Veri Yedekleme', 'text' => 'Operasyon sürekliliği için düzenli koruma yaklaşımı.'],
                    ['title' => 'Yetkilendirme Sistemi', 'text' => 'Rol bazlı erişim ve kontrollü yönetim deneyimi.'],
                ] as $item)
                    <article class="rounded-3xl border border-white/10 bg-white/5 p-6">
                        @php
                            $securityIcon = match (true) {
                                $item['title'] === 'KVKK Uyumlu' => 'heroicon-o-shield-check',
                                \Illuminate\Support\Str::startsWith($item['title'], 'SSL') => 'heroicon-o-lock-closed',
                                $item['title'] === 'Veri Yedekleme' => 'heroicon-o-circle-stack',
                                $item['title'] === 'Yetkilendirme Sistemi' => 'heroicon-o-user-group',
                                default => 'heroicon-o-shield-check',
                            };
                        @endphp
                        <div class="mb-5 flex h-11 w-11 items-center justify-center rounded-2xl bg-linear-to-br from-blue-500 via-indigo-500 to-cyan-400 shadow-lg shadow-blue-950/30">
                            <x-dynamic-component :component="$securityIcon" class="h-6 w-6 text-white" />
                        </div>
                        <h3 class="font-black">{{ $item['title'] }}</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-300">{{ $item['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    @if ($portfolios->isNotEmpty())
        <section id="referanslar" class="overflow-hidden bg-slate-950 py-20 text-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-12 flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
                    <div class="max-w-3xl">
                        <p class="text-sm font-black uppercase tracking-widest text-blue-300">Referanslar</p>
                        <h2 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">&Ouml;ne &ccedil;&#305;kan kurumsal proje vitrini</h2>
                        <p class="mt-4 text-base leading-7 text-slate-300">Strateji, tasar&#305;m, yaz&#305;l&#305;m ve yay&#305;n s&uuml;reci tek elde y&ouml;netilen se&ccedil;ili Argnest projeleri.</p>
                    </div>
                    <div class="rounded-full border border-blue-300/20 bg-blue-400/10 px-4 py-2 text-xs font-black uppercase tracking-widest text-blue-100">Agency Portfolio</div>
                </div>
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($portfolios as $portfolio)
                        @php
                            $portfolioImageUrl = $portfolio->cover_image ? \Illuminate\Support\Facades\Storage::url($portfolio->cover_image) : null;
                            $portfolioBadge = $portfolio->client_name ?: 'Kurumsal Proje';
                        @endphp
                        <a href="{{ route('frontend.references.show', $portfolio) }}" class="group block overflow-hidden rounded-3xl border border-white/10 bg-[linear-gradient(145deg,rgba(15,23,42,0.96),rgba(30,41,59,0.82),rgba(29,78,216,0.18))] shadow-2xl shadow-slate-950/30 transition duration-300 hover:-translate-y-1 hover:border-blue-300/40 hover:shadow-blue-950/40">
                            <div class="relative overflow-hidden">
                                @if ($portfolioImageUrl)
                                    <img src="{{ $portfolioImageUrl }}" alt="{{ $portfolio->title }}" class="h-56 w-full object-cover opacity-90 transition duration-500 group-hover:scale-105 group-hover:opacity-100">
                                @else
                                    <div class="h-56 bg-[radial-gradient(circle_at_18%_18%,rgba(59,130,246,0.58),transparent_32%),radial-gradient(circle_at_82%_20%,rgba(124,58,237,0.48),transparent_34%),linear-gradient(135deg,#020617_0%,#1e3a8a_52%,#312e81_100%)] p-5">
                                        <div class="h-full rounded-3xl border border-white/15 bg-white/10 p-5 backdrop-blur">
                                            <div class="flex items-center gap-2">
                                                <span class="h-2.5 w-2.5 rounded-full bg-cyan-200"></span>
                                                <span class="h-2.5 w-2.5 rounded-full bg-blue-200"></span>
                                                <span class="h-2.5 w-2.5 rounded-full bg-violet-200"></span>
                                            </div>
                                            <div class="mt-8 grid grid-cols-[0.8fr_1fr] gap-4">
                                                <span class="h-24 rounded-2xl bg-white/20"></span>
                                                <span class="h-24 rounded-2xl bg-white/30"></span>
                                            </div>
                                            <div class="mt-5 h-3 w-36 rounded-full bg-white/70"></div>
                                            <div class="mt-3 h-2 w-52 max-w-full rounded-full bg-white/35"></div>
                                        </div>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-linear-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
                                <div class="absolute left-5 top-5 flex flex-wrap gap-2">
                                    <span class="rounded-full bg-white/90 px-3 py-1.5 text-xs font-black text-slate-950 shadow-lg">{{ $portfolioBadge }}</span>
                                    @if ($portfolio->is_featured)
                                        <span class="rounded-full bg-blue-500 px-3 py-1.5 text-xs font-black text-white shadow-lg shadow-blue-950/30">&Ouml;ne &Ccedil;&#305;kan</span>
                                    @endif
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="mb-4 flex items-center justify-between gap-4">
                                    <p class="text-xs font-black uppercase tracking-widest text-blue-200">Proje Vitrini</p>
                                    @if ($portfolio->completion_date)
                                        <p class="text-xs font-bold text-slate-400">{{ $portfolio->completion_date->format('Y') }}</p>
                                    @endif
                                </div>
                                <h3 class="text-xl font-black tracking-tight text-white">{{ $portfolio->title }}</h3>
                                <p class="mt-3 text-sm leading-6 text-slate-300">{{ $portfolio->short_description ?: 'Ozel ihtiyaclara gore planlanan, tasarlanan ve yayina alinan kurumsal dijital proje.' }}</p>
                                <div class="mt-6 flex items-center justify-between border-t border-white/10 pt-5">
                                    <span class="text-sm font-black text-blue-200">Detayli inceleme</span>
                                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-lg font-black text-white transition group-hover:bg-blue-500">-&gt;</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                @if (false)
                <div class="mb-12 flex flex-col justify-between gap-5 lg:flex-row lg:items-end">
                    <div>
                        <p class="text-sm font-black uppercase tracking-widest text-blue-600">Referanslar</p>
                        <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">Öne çıkan projeler</h2>
                    </div>
                    <p class="max-w-xl text-sm leading-6 text-slate-600">Tamamlanan projeler; ihtiyaç analizi, tasarım, yazılım ve yayın süreçleriyle uçtan uca yönetilir.</p>
                </div>
                <div class="grid gap-5 md:grid-cols-3">
                    @foreach ($portfolios as $portfolio)
                    <article class="rounded-3xl border border-slate-200 bg-slate-50 p-6 transition hover:-translate-y-1 hover:shadow-xl">
                        <p class="text-sm font-black text-blue-700">{{ $portfolio->client_name ?: 'Referans' }}</p>
                        <h3 class="mt-3 text-xl font-black text-slate-950">{{ $portfolio->title }}</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $portfolio->short_description ?: 'Özel ihtiyaçlara göre planlanan ve yayına alınan dijital proje.' }}</p>
                    </article>
                    @endforeach
                </div>
                @endif
            </div>
        </section>
    @endif

    <section id="blog" class="bg-slate-50 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12 flex flex-col justify-between gap-5 lg:flex-row lg:items-end">
                <div class="max-w-3xl">
                <p class="text-sm font-black uppercase tracking-widest text-blue-600">Blog</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">Son içerikler ve dijital büyüme notları</h2>
                </div>
                <a href="{{ route('frontend.blog.index') }}" class="rounded-2xl bg-slate-950 px-5 py-3 text-center text-sm font-black text-white shadow-lg shadow-slate-200 transition hover:-translate-y-0.5 hover:bg-blue-700">Tum Yazilar</a>
            </div>
            <div class="grid gap-5 md:grid-cols-3">
                @forelse ($blogPosts as $post)
                    <a href="{{ route('frontend.blog.show', $post) }}" class="group block overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                        @if ($post->featured_image)
                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="h-44 w-full object-cover">
                        @else
                            <div class="h-44 bg-linear-to-br from-blue-600 via-slate-900 to-cyan-400 p-5">
                                <div class="h-full rounded-2xl border border-white/20 bg-white/10 p-4">
                                    <div class="h-2 w-24 rounded-full bg-white/80"></div>
                                    <div class="mt-4 h-2 w-32 rounded-full bg-white/50"></div>
                                    <div class="mt-8 grid grid-cols-3 gap-2">
                                        <span class="h-12 rounded-xl bg-white/20"></span>
                                        <span class="h-12 rounded-xl bg-white/30"></span>
                                        <span class="h-12 rounded-xl bg-white/20"></span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="p-6">
                            <div class="mb-4 flex flex-wrap items-center gap-2">
                                <p class="rounded-full bg-blue-50 px-3 py-1 text-xs font-black uppercase tracking-widest text-blue-600">{{ $post->category?->name ?: 'Blog' }}</p>
                                @if ($post->published_at)
                                    <p class="text-xs font-bold text-slate-400">{{ $post->published_at->format('d.m.Y') }}</p>
                                @endif
                            </div>
                            <h3 class="text-lg font-black text-slate-950">{{ $post->title }}</h3>
                            <p class="mt-3 text-sm leading-6 text-slate-600">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 120) }}</p>
                            <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                                <span class="text-sm font-black text-blue-700">Yaziyi Oku</span>
                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-50 text-sm font-black text-blue-700 transition group-hover:bg-blue-600 group-hover:text-white">-&gt;</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-slate-600">Aktif blog yazısı bulunamadı.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-[2rem] bg-slate-950 p-8 text-white shadow-2xl shadow-slate-300 md:p-10">
                <div class="grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
                    <div>
                        <p class="text-sm font-black uppercase tracking-widest text-blue-300">Bir sonraki adım</p>
                        <h2 class="mt-3 max-w-3xl text-3xl font-black tracking-tight sm:text-4xl">Projeniz için doğru çözümü birlikte planlayalım</h2>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300">İhtiyacınızı anlatın; web sitesi, CRM, panel, ürün veya SEO tarafında en doğru yol haritasını birlikte çıkaralım.</p>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row lg:flex-col xl:flex-row">
                        <a href="#teklif" class="rounded-xl bg-blue-600 px-6 py-3.5 text-center text-sm font-black text-white shadow-lg shadow-blue-600/20 hover:bg-blue-700">Teklif Al</a>
                        @if ($whatsappNumber)
                            <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" rel="noreferrer" class="rounded-xl border border-white/15 px-6 py-3.5 text-center text-sm font-black text-white hover:border-blue-300 hover:text-blue-200">WhatsApp ile Yaz</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="teklif" class="bg-white py-20">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[0.85fr_1.15fr] lg:px-8">
            <div class="rounded-3xl bg-slate-950 p-8 text-white">
                <p class="text-sm font-black uppercase tracking-widest text-blue-300">İletişim / Teklif Al</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">Projenizi netleştirelim, doğru yol haritasını çıkaralım.</h2>
                <p class="mt-5 text-base leading-8 text-slate-300">Kısa formu doldurun; ihtiyaçlarınıza göre web, yazılım, CRM, hosting veya SEO tarafında en uygun çözümü birlikte planlayalım.</p>
                <div class="mt-8 grid gap-3 text-sm text-slate-300">
                    <p>Yanıt süreci: En kısa sürede dönüş</p>
                    <p>Kaynak: Website talebi olarak panele düşer</p>
                    <p>Durum: Yeni müşteri talebi</p>
                </div>
            </div>

            <form action="{{ route('frontend.leads.store') }}" method="POST" class="rounded-3xl border border-slate-200 bg-slate-50 p-6 shadow-xl shadow-slate-200/70">
                @csrf

                @if (session('success'))
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-800">
                        Lütfen formdaki eksik veya hatalı alanları kontrol edin.
                    </div>
                @endif

                <div class="grid gap-5 md:grid-cols-2">
                    @foreach ([
                        'name' => 'Ad Soyad',
                        'phone' => 'Telefon',
                        'email' => 'E-posta',
                        'company' => 'Firma',
                        'service_type' => 'Hizmet Türü',
                        'budget_range' => 'Bütçe Aralığı',
                    ] as $field => $label)
                        <label class="block">
                            <span class="mb-2 block text-sm font-bold text-slate-700">{{ $label }}</span>
                            <input
                                name="{{ $field }}"
                                value="{{ old($field) }}"
                                class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none ring-blue-500/20 transition focus:border-blue-500 focus:ring-4"
                            >
                            @error($field)
                                <span class="mt-2 block text-xs font-bold text-red-600">{{ $message }}</span>
                            @enderror
                        </label>
                    @endforeach
                    <label class="block md:col-span-2">
                        <span class="mb-2 block text-sm font-bold text-slate-700">Mesaj</span>
                        <textarea name="message" rows="5" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none ring-blue-500/20 transition focus:border-blue-500 focus:ring-4">{{ old('message') }}</textarea>
                        @error('message')
                            <span class="mt-2 block text-xs font-bold text-red-600">{{ $message }}</span>
                        @enderror
                    </label>
                </div>
                <button class="mt-6 w-full rounded-2xl bg-blue-600 px-6 py-4 text-sm font-black text-white shadow-xl shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700 sm:w-auto">Talep Gönder</button>
            </form>
        </div>
    </section>
@endsection
