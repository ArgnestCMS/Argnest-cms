@extends('frontend.layout')

@php
    $productStatusOptions = \App\Models\Product::statusOptions();
    $whatsappNumber = preg_replace('/\D+/', '', $settings?->whatsapp ?: $settings?->phone ?: '');
    $featuredProduct = $products->firstWhere('slug', 'argnest-cms') ?? $products->firstWhere('is_featured', true) ?? $products->first();
    $secondaryProducts = $products->reject(fn ($product) => $featuredProduct && $product->id === $featuredProduct->id);
    $trustBadges = ['Mobil Uyumlu', 'SEO Odaklı', 'Güvenli Altyapı', 'Teknik Destek'];
    $serviceIconStyles = [
        'from-blue-500 to-cyan-400',
        'from-indigo-500 to-blue-500',
        'from-sky-500 to-blue-700',
        'from-slate-700 to-blue-600',
        'from-blue-600 to-indigo-600',
        'from-cyan-500 to-blue-600',
    ];
    $comparisonRows = [
        ['label' => 'Özelleştirme', 'ready' => 'Tema sınırları içinde kalır', 'argnest' => 'Markaya özel arayüz ve akış tasarlanır'],
        ['label' => 'Yönetim Paneli', 'ready' => 'Kısıtlı ve standart panel', 'argnest' => 'İhtiyaca özel Filament panel'],
        ['label' => 'SEO', 'ready' => 'Genel ayarlar ve sınırlı kontrol', 'argnest' => 'Teknik SEO ve içerik yönetimi birlikte'],
        ['label' => 'Veri Kontrolü', 'ready' => 'Platforma bağımlı veri yapısı', 'argnest' => 'Veri ve altyapı üzerinde tam kontrol'],
        ['label' => 'Destek', 'ready' => 'Genel destek süreçleri', 'argnest' => 'Projeye hakim teknik ekip'],
        ['label' => 'Ölçeklenebilirlik', 'ready' => 'Paket limitlerine bağlı büyüme', 'argnest' => 'İşletmeyle birlikte genişleyen mimari'],
    ];
@endphp

@section('content')
    <section class="relative overflow-hidden border-b border-slate-200 bg-white">
        <div class="absolute inset-x-0 top-0 h-52 bg-linear-to-b from-blue-50 via-slate-50 to-white"></div>
        <div class="relative mx-auto grid max-w-7xl items-center gap-12 px-4 py-20 sm:px-6 lg:grid-cols-[0.95fr_1.05fr] lg:px-8 lg:py-28">
            <div>
                <p class="mb-6 inline-flex items-center gap-2 rounded-full border border-blue-100 bg-white px-4 py-2 text-sm font-black text-blue-700 shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                    Premium SaaS altyapısı ve kurumsal yazılım
                </p>
                <h1 class="max-w-4xl text-4xl font-black tracking-tight text-slate-950 sm:text-5xl lg:text-7xl">
                    İşletmeniz İçin Modern Dijital Çözümler
                </h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600">
                    Kurumsal web siteleri, özel yazılımlar, müşteri takip sistemleri, hosting ve dijital büyüme çözümleri geliştiriyoruz.
                </p>
                <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                    <a href="#teklif" class="rounded-xl bg-blue-600 px-6 py-3.5 text-center text-sm font-black text-white shadow-xl shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700">Teklif Al</a>
                    <a href="#hizmetler" class="rounded-xl border border-slate-300 bg-white px-6 py-3.5 text-center text-sm font-black text-slate-900 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-300 hover:text-blue-700">Hizmetleri İncele</a>
                </div>
                <div class="mt-8 grid gap-3 sm:grid-cols-2">
                    @foreach ($trustBadges as $badge)
                        <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                            <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-50">
                                <span class="h-3 w-3 rounded-full bg-blue-600"></span>
                            </span>
                            <span class="text-sm font-black text-slate-700">{{ $badge }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="relative">
                <div class="absolute -inset-5 rounded-[2rem] bg-blue-100/70 blur-3xl"></div>
                <div class="relative overflow-hidden rounded-[1.75rem] border border-slate-200 bg-slate-950 p-3 shadow-2xl shadow-slate-300">
                    <div class="flex items-center gap-2 border-b border-white/10 px-4 py-3">
                        <span class="h-3 w-3 rounded-full bg-red-400"></span>
                        <span class="h-3 w-3 rounded-full bg-amber-400"></span>
                        <span class="h-3 w-3 rounded-full bg-emerald-400"></span>
                        <span class="ml-3 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-slate-300">argnest.app/dashboard</span>
                    </div>
                    <div class="grid gap-3 bg-slate-950 p-4 md:grid-cols-[0.7fr_1.3fr]">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-xs font-bold uppercase tracking-widest text-blue-300">Panel</p>
                            <div class="mt-5 space-y-3">
                                @foreach (['Müşteri Talepleri', 'Hizmetler', 'Ürünler', 'Blog İçerikleri'] as $item)
                                    <div class="rounded-xl bg-white/10 px-3 py-3 text-sm font-semibold text-white">{{ $item }}</div>
                                @endforeach
                            </div>
                        </div>
                        <div class="rounded-2xl bg-white p-5">
                            <div class="mb-6 flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-blue-600">Operasyon</p>
                                    <h2 class="mt-1 text-xl font-black text-slate-950">Büyüme Özeti</h2>
                                </div>
                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-black text-emerald-700">Canlı</span>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                @foreach ($stats as $stat)
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                        <p class="text-2xl font-black text-slate-950">{{ $stat['value'] }}</p>
                                        <p class="mt-1 text-xs font-bold text-slate-500">{{ $stat['label'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-5 rounded-2xl border border-blue-100 bg-blue-50 p-4">
                                <div class="mb-3 flex items-center justify-between text-xs font-bold text-blue-700">
                                    <span>Proje Akışı</span>
                                    <span>%86</span>
                                </div>
                                <div class="h-2 rounded-full bg-white">
                                    <div class="h-2 w-10/12 rounded-full bg-blue-600"></div>
                                </div>
                            </div>
                            <div class="mt-5 grid gap-3">
                                @foreach ([78, 64, 91] as $width)
                                    <div class="rounded-2xl border border-slate-100 bg-white p-3">
                                        <div class="mb-2 flex items-center justify-between">
                                            <span class="h-2 w-20 rounded-full bg-slate-200"></span>
                                            <span class="h-2 w-10 rounded-full bg-blue-200"></span>
                                        </div>
                                        <div class="h-2 rounded-full bg-slate-100">
                                            <div class="h-2 rounded-full bg-blue-600" style="width: {{ $width }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                        <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-linear-to-br {{ $serviceIconStyles[$loop->index % count($serviceIconStyles)] }} shadow-lg shadow-blue-200">
                            <div class="grid h-7 w-7 grid-cols-2 gap-1">
                                <span class="rounded-sm bg-white/95"></span>
                                <span class="rounded-sm bg-white/70"></span>
                                <span class="rounded-sm bg-white/70"></span>
                                <span class="rounded-sm bg-white/95"></span>
                            </div>
                        </div>
                        <h3 class="text-lg font-black text-slate-950">{{ $service->title }}</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $service->short_description ?: 'İş hedeflerinize göre ölçeklenebilir, hızlı ve yönetilebilir çözüm.' }}</p>
                        <div class="mt-6 h-px bg-slate-100"></div>
                        <p class="mt-4 text-sm font-bold text-blue-700">Detaylı planlama ve sürdürülebilir geliştirme</p>
                    </article>
                @empty
                    <p class="text-slate-600">Aktif hizmet kaydı bulunamadı.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section id="urunler" class="bg-white py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
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
                        <div class="mb-5 h-11 w-11 rounded-2xl bg-blue-500"></div>
                        <h3 class="font-black">{{ $item['title'] }}</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-300">{{ $item['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    @if ($portfolios->isNotEmpty())
        <section id="referanslar" class="bg-white py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
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
            </div>
        </section>
    @endif

    <section id="blog" class="bg-slate-50 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12 max-w-3xl">
                <p class="text-sm font-black uppercase tracking-widest text-blue-600">Blog</p>
                <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">Son içerikler ve dijital büyüme notları</h2>
            </div>
            <div class="grid gap-5 md:grid-cols-3">
                @forelse ($blogPosts as $post)
                    <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
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
                        </div>
                    </article>
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
