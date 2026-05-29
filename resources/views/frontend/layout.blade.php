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
<body class="bg-slate-50 text-slate-900 antialiased">
    <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 backdrop-blur">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                @if ($settings?->logo)
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ $siteName }}" class="h-10 w-auto">
                @else
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600 text-lg font-bold text-white">A</span>
                @endif
                <span class="text-lg font-bold tracking-tight text-slate-950">{{ $siteName }}</span>
            </a>

            <div class="hidden items-center gap-8 text-sm font-medium text-slate-600 lg:flex">
                <a href="#hizmetler" class="hover:text-blue-700">Hizmetler</a>
                <a href="#urunler" class="hover:text-blue-700">Ürünler</a>
                <a href="#referanslar" class="hover:text-blue-700">Referanslar</a>
                <a href="#blog" class="hover:text-blue-700">Blog</a>
                <a href="#teklif" class="rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">Teklif Al</a>
            </div>

            <details class="relative lg:hidden">
                <summary class="cursor-pointer list-none rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700">Menü</summary>
                <div class="absolute right-0 mt-3 w-56 rounded-xl border border-slate-200 bg-white p-3 shadow-xl">
                    <a href="#hizmetler" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Hizmetler</a>
                    <a href="#urunler" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Ürünler</a>
                    <a href="#referanslar" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Referanslar</a>
                    <a href="#blog" class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Blog</a>
                    <a href="#teklif" class="mt-2 block rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white">Teklif Al</a>
                </div>
            </details>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="bg-slate-950 text-white">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 lg:grid-cols-[1.2fr_0.8fr_0.8fr] lg:px-8">
            <div>
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-500 text-lg font-bold">A</span>
                    <span class="text-lg font-bold">{{ $siteName }}</span>
                </div>
                <p class="max-w-xl text-sm leading-6 text-slate-300">
                    {{ $settings?->footer_text ?: 'Modern, güvenli ve yönetilebilir dijital ürünler geliştiriyoruz.' }}
                </p>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-white">İletişim</h2>
                <div class="mt-4 space-y-2 text-sm text-slate-300">
                    @if ($settings?->phone)<p>{{ $settings->phone }}</p>@endif
                    @if ($settings?->email)<p>{{ $settings->email }}</p>@endif
                    @if ($settings?->address)<p>{{ $settings->address }}</p>@endif
                </div>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-white">Sosyal Medya</h2>
                <div class="mt-4 grid gap-2 text-sm text-slate-300">
                    @foreach ([
                        'Facebook' => $settings?->facebook_url,
                        'Instagram' => $settings?->instagram_url,
                        'LinkedIn' => $settings?->linkedin_url,
                        'YouTube' => $settings?->youtube_url,
                        'X / Twitter' => $settings?->x_url,
                    ] as $label => $url)
                        @if ($url)
                            <a href="{{ $url }}" target="_blank" rel="noreferrer" class="hover:text-blue-300">{{ $label }}</a>
                        @endif
                    @endforeach
                </div>
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
            class="fixed bottom-5 right-5 z-50 rounded-full bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-2xl shadow-blue-600/30 hover:bg-blue-700"
        >
            WhatsApp
        </a>
    @endif
</body>
</html>
