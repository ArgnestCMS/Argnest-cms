@php
    $siteName = $settings?->site_name ?? 'Argnest';
    $seoTitle = $settings?->seo_title ?: $siteName . ' | Modern Dijital Çözümler';
    $seoDescription = $settings?->seo_description ?: 'Argnest; kurumsal web siteleri, özel yazılım çözümleri, müşteri takip sistemleri, hosting ve dijital büyüme hizmetleri geliştirir.';
    $whatsappNumber = preg_replace('/\D+/', '', $settings?->whatsapp ?: $settings?->phone ?: '');
@endphp

<!DOCTYPE html>
<html lang="tr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $seoTitle)</title>
    <meta name="description" content="@yield('description', $seoDescription)">

    @if ($settings?->favicon)
        <link rel="icon" href="{{ asset('storage/' . $settings->favicon) }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-950 antialiased">
    <header class="sticky top-0 z-40 border-b border-slate-200/70 bg-white/85 backdrop-blur-xl">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                @if ($settings?->logo)
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ $siteName }}" class="h-10 w-auto">
                @else
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-950 text-lg font-black text-white ring-4 ring-blue-100">A</span>
                @endif
                <span class="text-lg font-black tracking-tight">{{ $siteName }}</span>
            </a>

            <div class="hidden items-center gap-7 text-sm font-semibold text-slate-600 lg:flex">
                <a href="#hizmetler" class="transition hover:text-blue-700">Hizmetler</a>
                <a href="#urunler" class="transition hover:text-blue-700">Ürünler</a>
                <a href="#referanslar" class="transition hover:text-blue-700">Referanslar</a>
                <a href="#blog" class="transition hover:text-blue-700">Blog</a>
                <a href="#teklif" class="transition hover:text-blue-700">İletişim</a>
                <a href="#teklif" class="rounded-xl bg-blue-600 px-4 py-2.5 font-bold text-white shadow-lg shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700">Teklif Al</a>
            </div>

            <details class="relative lg:hidden">
                <summary class="cursor-pointer list-none rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-bold text-slate-800 shadow-sm">Menü</summary>
                <div class="absolute right-0 mt-3 w-64 rounded-2xl border border-slate-200 bg-white p-3 shadow-2xl shadow-slate-200">
                    <a href="#hizmetler" class="block rounded-xl px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Hizmetler</a>
                    <a href="#urunler" class="block rounded-xl px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Ürünler</a>
                    <a href="#referanslar" class="block rounded-xl px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Referanslar</a>
                    <a href="#blog" class="block rounded-xl px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Blog</a>
                    <a href="#teklif" class="block rounded-xl px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">İletişim</a>
                    <a href="#teklif" class="mt-2 block rounded-xl bg-blue-600 px-3 py-2.5 text-center text-sm font-bold text-white">Teklif Al</a>
                </div>
            </details>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="bg-slate-950 text-white">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-16 sm:px-6 md:grid-cols-2 lg:grid-cols-[1.2fr_0.75fr_0.75fr_0.75fr_1fr] lg:px-8">
            <div>
                <div class="mb-5 flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-lg font-black">A</span>
                    <span class="text-xl font-black">{{ $siteName }}</span>
                </div>
                <p class="max-w-sm text-sm leading-7 text-slate-300">
                    {{ $settings?->footer_text ?: 'Modern, güvenli ve yönetilebilir dijital ürünler geliştiriyoruz.' }}
                </p>
                <div class="mt-6 flex flex-wrap gap-3 text-sm text-slate-300">
                    @foreach ([
                        'Facebook' => $settings?->facebook_url,
                        'Instagram' => $settings?->instagram_url,
                        'LinkedIn' => $settings?->linkedin_url,
                        'YouTube' => $settings?->youtube_url,
                        'X' => $settings?->x_url,
                    ] as $label => $url)
                        @if ($url)
                            <a href="{{ $url }}" target="_blank" rel="noreferrer" class="rounded-full border border-white/10 px-3 py-1.5 hover:border-blue-400 hover:text-blue-300">{{ $label }}</a>
                        @endif
                    @endforeach
                </div>
            </div>

            <div>
                <h2 class="text-sm font-bold text-white">Kurumsal</h2>
                <div class="mt-4 grid gap-3 text-sm text-slate-300">
                    <a href="#neden" class="hover:text-blue-300">Neden Argnest</a>
                    <a href="#guvenlik" class="hover:text-blue-300">Güvenlik ve KVKK</a>
                    <a href="#referanslar" class="hover:text-blue-300">Referanslar</a>
                    <a href="#blog" class="hover:text-blue-300">Blog</a>
                </div>
            </div>

            <div>
                <h2 class="text-sm font-bold text-white">Hizmetler</h2>
                <div class="mt-4 grid gap-3 text-sm text-slate-300">
                    <a href="#hizmetler" class="hover:text-blue-300">Kurumsal Web</a>
                    <a href="#hizmetler" class="hover:text-blue-300">Özel Yazılım</a>
                    <a href="#hizmetler" class="hover:text-blue-300">CRM Çözümleri</a>
                    <a href="#hizmetler" class="hover:text-blue-300">SEO ve Hosting</a>
                </div>
            </div>

            <div>
                <h2 class="text-sm font-bold text-white">Ürünler</h2>
                <div class="mt-4 grid gap-3 text-sm text-slate-300">
                    <a href="#urunler" class="hover:text-blue-300">Argnest CMS</a>
                    <a href="#urunler" class="hover:text-blue-300">Argnest CRM</a>
                    <a href="#urunler" class="hover:text-blue-300">Argnest Fit</a>
                    <a href="#urunler" class="hover:text-blue-300">Yakında</a>
                </div>
            </div>

            <div>
                <h2 class="text-sm font-bold text-white">İletişim</h2>
                <div class="mt-4 space-y-3 text-sm leading-6 text-slate-300">
                    @if ($settings?->phone)<p>{{ $settings->phone }}</p>@endif
                    @if ($settings?->email)<p>{{ $settings->email }}</p>@endif
                    @if ($settings?->address)<p>{{ $settings->address }}</p>@endif
                </div>
                <a href="#teklif" class="mt-6 inline-flex rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-blue-700">Teklif Al</a>
            </div>
        </div>

        <div class="border-t border-white/10 px-4 py-5 text-center text-xs text-slate-400">
            {{ $settings?->copyright_text ?: '© ' . date('Y') . ' Argnest. Tüm hakları saklıdır.' }}
        </div>
    </footer>

    @if ($whatsappNumber)
        <a
            href="https://wa.me/{{ $whatsappNumber }}"
            target="_blank"
            rel="noreferrer"
            class="fixed bottom-5 right-5 z-50 inline-flex items-center gap-2 rounded-full bg-blue-600 px-5 py-3 text-sm font-black text-white shadow-2xl shadow-blue-600/30 transition hover:-translate-y-0.5 hover:bg-blue-700"
        >
            <span class="h-2.5 w-2.5 rounded-full bg-emerald-300"></span>
            WhatsApp
        </a>
    @endif
</body>
</html>
