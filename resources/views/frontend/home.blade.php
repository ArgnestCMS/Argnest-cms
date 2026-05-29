@extends('frontend.layout')

@section('content')
    <section class="overflow-hidden bg-white">
        <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 py-20 sm:px-6 lg:grid-cols-2 lg:px-8 lg:py-24">
            <div>
                <p class="mb-5 inline-flex rounded-full border border-blue-100 bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700">
                    Kurumsal yazılım ve dijital büyüme ortağınız
                </p>
                <h1 class="max-w-3xl text-4xl font-bold tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">
                    İşletmeniz İçin Modern Dijital Çözümler
                </h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600">
                    Kurumsal web siteleri, özel yazılımlar, müşteri takip sistemleri, hosting ve dijital büyüme çözümleri geliştiriyoruz.
                </p>
                <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                    <a href="#teklif" class="rounded-xl bg-blue-600 px-6 py-3 text-center text-sm font-bold text-white shadow-lg shadow-blue-600/20 hover:bg-blue-700">Teklif Al</a>
                    <a href="#hizmetler" class="rounded-xl border border-slate-300 px-6 py-3 text-center text-sm font-bold text-slate-800 hover:border-blue-300 hover:text-blue-700">Hizmetleri İncele</a>
                </div>
            </div>
            <div class="relative">
                <div class="rounded-[2rem] border border-slate-200 bg-slate-950 p-4 shadow-2xl shadow-slate-300">
                    <div class="rounded-3xl bg-white p-5">
                        <div class="mb-5 flex items-center justify-between border-b border-slate-100 pb-4">
                            <div>
                                <p class="text-xs font-semibold text-blue-600">Argnest Panel</p>
                                <p class="text-lg font-bold text-slate-950">Dijital Operasyon Merkezi</p>
                            </div>
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">Güvenli</span>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            @foreach (['Lead Yönetimi', 'Web Sitesi', 'SEO İçerikleri', 'Raporlama'] as $item)
                                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                    <div class="mb-4 h-2 w-16 rounded-full bg-blue-500"></div>
                                    <p class="font-semibold text-slate-900">{{ $item }}</p>
                                    <p class="mt-2 text-sm text-slate-500">Kontrol edilebilir, ölçülebilir, geliştirilebilir.</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="hizmetler" class="bg-slate-50 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 max-w-2xl">
                <p class="text-sm font-bold uppercase tracking-widest text-blue-600">Hizmetler</p>
                <h2 class="mt-3 text-3xl font-bold text-slate-950">İhtiyacınıza göre tasarlanan dijital hizmetler</h2>
            </div>
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($services as $service)
                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-lg font-bold text-blue-700">{{ mb_substr($service->title, 0, 1) }}</div>
                        <h3 class="text-lg font-bold text-slate-950">{{ $service->title }}</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $service->short_description ?: 'İş hedeflerinize göre ölçeklenebilir, hızlı ve yönetilebilir çözüm.' }}</p>
                    </article>
                @empty
                    <p class="text-slate-600">Aktif hizmet kaydı bulunamadı.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section id="urunler" class="bg-white py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
                <div class="max-w-2xl">
                    <p class="text-sm font-bold uppercase tracking-widest text-blue-600">Ürünler</p>
                    <h2 class="mt-3 text-3xl font-bold text-slate-950">Argnest ürün ailesi</h2>
                </div>
                <p class="max-w-xl text-sm leading-6 text-slate-600">CMS, CRM ve sektörel çözümler tek bir vizyonla geliştirilir: sade kullanım, güçlü yönetim, tam kontrol.</p>
            </div>
            <div class="grid gap-5 lg:grid-cols-3">
                @forelse ($products as $product)
                    <article class="{{ $product->is_featured ? 'border-blue-200 bg-blue-50/60 lg:col-span-2' : 'border-slate-200 bg-white' }} rounded-2xl border p-6 shadow-sm">
                        <div class="mb-5 flex items-center justify-between">
                            <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-blue-700">{{ \App\Models\Product::statusOptions()[$product->product_status] ?? 'Aktif' }}</span>
                            @if ($product->is_featured)
                                <span class="rounded-full bg-blue-600 px-3 py-1 text-xs font-bold text-white">Öne Çıkan</span>
                            @endif
                        </div>
                        <h3 class="text-2xl font-bold text-slate-950">{{ $product->title }}</h3>
                        <p class="mt-4 text-sm leading-6 text-slate-600">{{ $product->short_description ?: 'Argnest kalitesiyle geliştirilen modern ve güvenli ürün altyapısı.' }}</p>
                    </article>
                @empty
                    <p class="text-slate-600">Aktif ürün kaydı bulunamadı.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-20">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
            <div>
                <p class="text-sm font-bold uppercase tracking-widest text-blue-600">Neden Argnest</p>
                <h2 class="mt-3 text-3xl font-bold text-slate-950">Hazır site değil, işletmenize ait özel dijital altyapı</h2>
                <p class="mt-5 text-base leading-8 text-slate-600">
                    Hazır sistemler çoğu zaman sınırlı özelleştirme, kısıtlı yönetim ve platform bağımlılığı oluşturur. Argnest; özel tasarım, özel yönetim paneli, müşteri takip sistemi, SEO uyumlu yapı ve tam kontrol sunar.
                </p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach (['Özel Tasarım', 'Yönetilebilir Panel', 'Müşteri Takip Sistemi', 'SEO Uyumlu Yapı'] as $item)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5">
                        <div class="mb-4 h-10 w-10 rounded-lg bg-blue-600"></div>
                        <h3 class="font-bold text-slate-950">{{ $item }}</h3>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-slate-950 py-20 text-white">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-8">
            <div>
                <p class="text-sm font-bold uppercase tracking-widest text-blue-300">Güven ve Gizlilik</p>
                <h2 class="mt-3 text-3xl font-bold">Verinizi koruyan, erişimi yöneten, güvenliği merkeze alan yapı</h2>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach (['KVKK bilinci', 'SSL ve güvenli bağlantı', 'Veri güvenliği', 'Yetkilendirilmiş erişim'] as $item)
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                        <p class="font-bold">{{ $item }}</p>
                        <p class="mt-2 text-sm leading-6 text-slate-300">Kurumsal süreçlere uygun, izlenebilir ve güvenli yaklaşım.</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="referanslar" class="bg-white py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 max-w-2xl">
                <p class="text-sm font-bold uppercase tracking-widest text-blue-600">Referanslar</p>
                <h2 class="mt-3 text-3xl font-bold text-slate-950">Tamamlanan projeler</h2>
            </div>
            <div class="grid gap-5 md:grid-cols-3">
                @forelse ($portfolios as $portfolio)
                    <article class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                        <p class="text-sm font-semibold text-blue-700">{{ $portfolio->client_name ?: 'Referans' }}</p>
                        <h3 class="mt-3 text-xl font-bold text-slate-950">{{ $portfolio->title }}</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $portfolio->short_description ?: 'Özel ihtiyaçlara göre planlanan ve yayına alınan dijital proje.' }}</p>
                    </article>
                @empty
                    <p class="text-slate-600">Öne çıkan referans kaydı bulunamadı.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section id="blog" class="bg-slate-50 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 max-w-2xl">
                <p class="text-sm font-bold uppercase tracking-widest text-blue-600">Blog</p>
                <h2 class="mt-3 text-3xl font-bold text-slate-950">Dijital büyüme notları</h2>
            </div>
            <div class="grid gap-5 md:grid-cols-3">
                @forelse ($blogPosts as $post)
                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-widest text-blue-600">{{ $post->category?->name ?: 'Blog' }}</p>
                        <h3 class="mt-3 text-lg font-bold text-slate-950">{{ $post->title }}</h3>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 120) }}</p>
                    </article>
                @empty
                    <p class="text-slate-600">Aktif blog yazısı bulunamadı.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section id="teklif" class="bg-white py-20">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[0.8fr_1.2fr] lg:px-8">
            <div>
                <p class="text-sm font-bold uppercase tracking-widest text-blue-600">İletişim / Teklif Al</p>
                <h2 class="mt-3 text-3xl font-bold text-slate-950">Projenizi birlikte planlayalım</h2>
                <p class="mt-5 text-base leading-8 text-slate-600">Formu doldurun, ihtiyaçlarınızı inceleyip en kısa sürede sizinle iletişime geçelim.</p>
            </div>
            <form action="{{ route('frontend.leads.store') }}" method="POST" class="rounded-3xl border border-slate-200 bg-slate-50 p-6 shadow-sm">
                @csrf

                @if (session('success'))
                    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
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
                            <span class="mb-2 block text-sm font-semibold text-slate-700">{{ $label }}</span>
                            <input
                                name="{{ $field }}"
                                value="{{ old($field) }}"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none ring-blue-500/20 focus:border-blue-500 focus:ring-4"
                            >
                            @error($field)
                                <span class="mt-2 block text-xs font-semibold text-red-600">{{ $message }}</span>
                            @enderror
                        </label>
                    @endforeach
                    <label class="block md:col-span-2">
                        <span class="mb-2 block text-sm font-semibold text-slate-700">Mesaj</span>
                        <textarea name="message" rows="5" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none ring-blue-500/20 focus:border-blue-500 focus:ring-4">{{ old('message') }}</textarea>
                        @error('message')
                            <span class="mt-2 block text-xs font-semibold text-red-600">{{ $message }}</span>
                        @enderror
                    </label>
                </div>
                <button class="mt-6 rounded-xl bg-blue-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 hover:bg-blue-700">Talep Gönder</button>
            </form>
        </div>
    </section>
@endsection
