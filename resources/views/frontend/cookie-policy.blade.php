@extends('frontend.layout')

@php
    $content = $settings?->cookie_policy ?: "Argnest web sitesi, temel site fonksiyonlarini calistirmak, guvenligi saglamak ve ziyaretci deneyimini iyilestirmek icin cerezlerden yararlanabilir.\n\nZorunlu cerezler sitenin guvenli ve dogru calismasi icin kullanilir. Performans ve analitik cerezler ise sayfa deneyimini anlamak ve hizmetleri gelistirmek amaciyla degerlendirilebilir.\n\nTarayici ayarlarinizdan cerez tercihlerinizi yonetebilir, mevcut cerezleri silebilir veya belirli cerezleri engelleyebilirsiniz. Cerezleri kapatmaniz durumunda bazi site ozellikleri sinirli calisabilir.";
    $cards = [
        ['title' => 'Zorunlu cerezler', 'text' => 'Sitenin guvenli ve kararlı calismasi icin gerekli teknik cerezlerdir.'],
        ['title' => 'Performans cerezleri', 'text' => 'Sayfa deneyimini ve teknik performansi anlamaya yardimci olur.'],
        ['title' => 'Tercih yonetimi', 'text' => 'Cerez ayarlarinizi tarayiciniz uzerinden dilediginiz zaman duzenleyebilirsiniz.'],
    ];
@endphp

@section('title', 'Cerez Politikasi | Argnest')
@section('description', 'Argnest cerez politikasi ve cerez kullanimi bilgilendirmesi.')

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.36),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.30),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
            <div class="max-w-4xl">
                <p class="text-sm font-black uppercase tracking-widest text-blue-200">Hukuki Bilgilendirme</p>
                <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl lg:text-6xl">Cerez Politikasi</h1>
                <p class="mt-6 max-w-2xl text-base leading-8 text-slate-300 sm:text-lg">Cerez kullanimi, tercih yonetimi ve web sitesi deneyimi hakkinda bilgilendirme.</p>
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-20">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-[1fr_22rem] lg:px-8">
            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70 sm:p-8 lg:p-10">
                <div class="prose prose-slate max-w-none text-sm leading-8 text-slate-700">
                    {!! nl2br(e($content)) !!}
                </div>
            </article>
            <aside class="space-y-4">
                @foreach ($cards as $card)
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="font-black text-slate-950">{{ $card['title'] }}</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $card['text'] }}</p>
                    </div>
                @endforeach
                <a href="{{ route('frontend.contact') }}" class="block rounded-3xl bg-blue-600 p-6 text-sm font-black text-white shadow-xl shadow-blue-100 transition hover:-translate-y-0.5 hover:bg-blue-700">Cerezler hakkinda iletisime gec</a>
            </aside>
        </div>
    </section>
@endsection
