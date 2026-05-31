@extends('frontend.layout')

@php
    $content = $settings?->kvkk_text ?: "Argnest olarak kişisel verilerinizi yalnizca hizmet, teklif, destek ve iletisim sureclerini yurutmek amaciyla isleriz.\n\nToplanan veriler; ad soyad, e-posta, telefon, firma bilgisi, talep icerigi ve iletisim kayitlariyla sinirlidir. Bu veriler hukuka uygun, olculu ve amacla baglantili sekilde saklanir.\n\nKişisel verileriniz, yasal zorunluluklar ve hizmetin gerektirdigi teknik is ortaklari disinda ucuncu kisilerle paylasilmaz. KVKK kapsamindaki erisim, duzeltme, silme ve itiraz haklariniz icin bizimle iletisime gecebilirsiniz.";
    $cards = [
        ['title' => 'Veri guvenligi', 'text' => 'Talepleriniz kontrollu sistemlerde ve yetkili ekip erisimiyle degerlendirilir.'],
        ['title' => 'Amacla sinirli isleme', 'text' => 'Bilgiler yalnizca iletisim, teklif ve hizmet surecleri icin kullanilir.'],
        ['title' => 'Basvuru haklari', 'text' => 'KVKK kapsamindaki haklariniz icin iletisim kanallarindan basvuru yapabilirsiniz.'],
    ];
@endphp

@section('title', 'KVKK Aydinlatma Metni | Argnest')
@section('description', 'Argnest KVKK aydinlatma metni ve kisisel verilerin korunmasi yaklasimi.')

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.36),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.30),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
            <div class="max-w-4xl">
                <p class="text-sm font-black uppercase tracking-widest text-blue-200">Hukuki Bilgilendirme</p>
                <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl lg:text-6xl">KVKK Aydinlatma Metni</h1>
                <p class="mt-6 max-w-2xl text-base leading-8 text-slate-300 sm:text-lg">Kisisel verilerin korunmasi ve seffaf iletisim surecleri icin temel bilgilendirme.</p>
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
                <a href="{{ route('frontend.contact') }}" class="block rounded-3xl bg-blue-600 p-6 text-sm font-black text-white shadow-xl shadow-blue-100 transition hover:-translate-y-0.5 hover:bg-blue-700">KVKK basvurusu icin iletisime gec</a>
            </aside>
        </div>
    </section>
@endsection
