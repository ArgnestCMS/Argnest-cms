@extends('frontend.layout')

@php
    $whatsappNumber = preg_replace('/\D+/', '', $settings?->whatsapp ?: $settings?->phone ?: '');
    $contactItems = [
        [
            'title' => 'Telefon',
            'value' => $settings?->phone ?: 'Proje gorusmesi icin formu doldurun',
            'href' => $settings?->phone ? 'tel:' . preg_replace('/\s+/', '', $settings->phone) : null,
            'icon' => 'heroicon-o-phone',
        ],
        [
            'title' => 'E-posta',
            'value' => $settings?->email ?: 'iletisim@argnest.com',
            'href' => $settings?->email ? 'mailto:' . $settings->email : null,
            'icon' => 'heroicon-o-envelope',
        ],
        [
            'title' => 'Adres',
            'value' => $settings?->address ?: 'Online ve yerinde proje gorusmeleri',
            'href' => $settings?->google_maps_url,
            'icon' => 'heroicon-o-map-pin',
        ],
    ];
    $trustCards = [
        ['title' => 'Hizli donus', 'text' => 'Talebiniz ekibe duser ve en kisa surede proje kapsamiyla birlikte degerlendirilir.', 'icon' => 'heroicon-o-bolt'],
        ['title' => 'Ucretsiz on analiz', 'text' => 'Ihtiyac, hedef ve teknik yol haritasi icin ilk kapsam gorusmesini netlestiririz.', 'icon' => 'heroicon-o-magnifying-glass'],
        ['title' => 'KVKK uyumlu iletisim', 'text' => 'Paylastiginiz bilgiler yalnizca talebinizi yanitlamak icin kullanilir.', 'icon' => 'heroicon-o-shield-check'],
    ];
@endphp

@section('title', 'Iletisim | Argnest')
@section('description', 'Argnest ile teklif, proje ve destek talepleriniz icin iletisime gecin.')

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.36),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.30),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto grid max-w-7xl gap-10 px-4 py-20 sm:px-6 lg:grid-cols-[0.95fr_1.05fr] lg:px-8 lg:py-24">
            <div class="flex flex-col justify-center">
                <div class="mb-7 flex flex-wrap items-center gap-3">
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-black uppercase tracking-widest text-blue-100">Argnest Iletisim</span>
                    <span class="rounded-full bg-cyan-300 px-3 py-1.5 text-xs font-black uppercase tracking-widest text-slate-950">Teklif ve Destek</span>
                </div>
                <h1 class="text-4xl font-black tracking-tight sm:text-5xl lg:text-6xl">Projenizi birlikte büyütelim</h1>
                <p class="mt-6 max-w-2xl text-base leading-8 text-slate-300 sm:text-lg">
                    Yeni web siteniz, ozel yazilim projeniz, panel ihtiyaciniz veya destek talebiniz icin net ve uygulanabilir bir yol haritasi olusturalim.
                </p>
            </div>

            <div class="rounded-[2rem] border border-white/10 bg-white/[0.04] p-5 shadow-2xl shadow-blue-950/40 backdrop-blur">
                <div class="rounded-[1.5rem] bg-[linear-gradient(135deg,#020617_0%,#1d4ed8_52%,#6d28d9_100%)] p-6">
                    <div class="grid gap-4 sm:grid-cols-3">
                        @foreach ($trustCards as $card)
                            <div class="rounded-3xl border border-white/15 bg-white/10 p-5">
                                <div class="mb-5 flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-blue-700">
                                    <x-dynamic-component :component="$card['icon']" class="h-6 w-6" />
                                </div>
                                <h2 class="font-black text-white">{{ $card['title'] }}</h2>
                                <p class="mt-3 text-sm leading-6 text-blue-100">{{ $card['text'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-20">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-[0.85fr_1.15fr] lg:px-8">
            <div class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70">
                    <p class="text-sm font-black uppercase tracking-widest text-blue-600">Iletisim Bilgileri</p>
                    <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950">Size en uygun kanaldan ulasin</h2>
                    <div class="mt-6 grid gap-4">
                        @foreach ($contactItems as $item)
                            @if ($item['href'])
                                <a href="{{ $item['href'] }}" @if ($item['title'] === 'Adres') target="_blank" rel="noreferrer" @endif class="group rounded-3xl border border-slate-100 bg-slate-50 p-5 transition hover:-translate-y-0.5 hover:border-blue-200 hover:bg-white hover:shadow-lg">
                                    <div class="flex items-start gap-4">
                                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-linear-to-br from-slate-950 via-blue-700 to-violet-600 text-white shadow-lg shadow-blue-100">
                                            <x-dynamic-component :component="$item['icon']" class="h-6 w-6" />
                                        </span>
                                        <span>
                                            <span class="block text-sm font-black text-slate-950">{{ $item['title'] }}</span>
                                            <span class="mt-1 block text-sm leading-6 text-slate-600 group-hover:text-blue-700">{{ $item['value'] }}</span>
                                        </span>
                                    </div>
                                </a>
                            @else
                                <div class="rounded-3xl border border-slate-100 bg-slate-50 p-5">
                                    <div class="flex items-start gap-4">
                                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-linear-to-br from-slate-950 via-blue-700 to-violet-600 text-white shadow-lg shadow-blue-100">
                                            <x-dynamic-component :component="$item['icon']" class="h-6 w-6" />
                                        </span>
                                        <span>
                                            <span class="block text-sm font-black text-slate-950">{{ $item['title'] }}</span>
                                            <span class="mt-1 block text-sm leading-6 text-slate-600">{{ $item['value'] }}</span>
                                        </span>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        @if ($whatsappNumber)
                            <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" rel="noreferrer" class="group rounded-3xl border border-emerald-100 bg-emerald-50 p-5 transition hover:-translate-y-0.5 hover:border-emerald-200 hover:bg-white hover:shadow-lg">
                                <div class="flex items-start gap-4">
                                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-lg shadow-emerald-100">
                                        <x-heroicon-o-chat-bubble-left-right class="h-6 w-6" />
                                    </span>
                                    <span>
                                        <span class="block text-sm font-black text-slate-950">WhatsApp</span>
                                        <span class="mt-1 block text-sm leading-6 text-slate-600 group-hover:text-emerald-700">{{ $settings?->whatsapp ?: $settings?->phone }}</span>
                                    </span>
                                </div>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <form id="contact-form" action="{{ route('frontend.leads.store') }}" method="POST" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-2xl shadow-slate-200/80">
                @csrf

                <div class="mb-8">
                    <p class="text-sm font-black uppercase tracking-widest text-blue-600">Iletisim / Teklif Formu</p>
                    <h2 class="mt-3 text-3xl font-black tracking-tight text-slate-950">Talebinizi anlatin, birlikte netlestirelim</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Formu doldurun; proje, teklif veya destek talebiniz Argnest paneline yeni musteri talebi olarak duser.</p>
                </div>

                @if (session('success'))
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-800">
                        Lutfen formdaki eksik veya hatali alanlari kontrol edin.
                    </div>
                @endif

                <div class="grid gap-5 md:grid-cols-2">
                    @foreach ([
                        'name' => 'Ad soyad',
                        'email' => 'E-posta',
                        'phone' => 'Telefon',
                        'company' => 'Firma adi',
                        'service_type' => 'Hizmet turu',
                    ] as $field => $label)
                        <label class="block {{ $field === 'service_type' ? 'md:col-span-2' : '' }}">
                            <span class="mb-2 block text-sm font-bold text-slate-700">{{ $label }}</span>
                            <input
                                name="{{ $field }}"
                                value="{{ old($field) }}"
                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-blue-500/20 transition focus:border-blue-500 focus:bg-white focus:ring-4"
                            >
                            @error($field)
                                <span class="mt-2 block text-xs font-bold text-red-600">{{ $message }}</span>
                            @enderror
                        </label>
                    @endforeach

                    <label class="block md:col-span-2">
                        <span class="mb-2 block text-sm font-bold text-slate-700">Mesaj</span>
                        <textarea name="message" rows="6" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-blue-500/20 transition focus:border-blue-500 focus:bg-white focus:ring-4">{{ old('message') }}</textarea>
                        @error('message')
                            <span class="mt-2 block text-xs font-bold text-red-600">{{ $message }}</span>
                        @enderror
                    </label>
                </div>

                <button class="mt-6 w-full rounded-2xl bg-blue-600 px-6 py-4 text-sm font-black text-white shadow-xl shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700 sm:w-auto">Gonder</button>
            </form>
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-[2rem] bg-[linear-gradient(135deg,#020617_0%,#1d4ed8_56%,#6d28d9_100%)] p-8 text-white shadow-2xl shadow-slate-300 md:p-10">
                <div class="grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
                    <div>
                        <p class="text-sm font-black uppercase tracking-widest text-blue-100">Yeni projenizi konusalim</p>
                        <h2 class="mt-3 max-w-3xl text-3xl font-black tracking-tight sm:text-4xl">Fikrinizi teknik olarak uygulanabilir bir plana donusturelim</h2>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-blue-100">Ihtiyac, kapsam, sure ve sonraki adimlari birlikte netlestirelim.</p>
                    </div>
                    <a href="#contact-form" class="rounded-xl bg-white px-6 py-3.5 text-center text-sm font-black text-slate-950 shadow-lg shadow-slate-950/20 transition hover:-translate-y-0.5">Formu Doldur</a>
                </div>
            </div>
        </div>
    </section>
@endsection
