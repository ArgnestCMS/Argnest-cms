@extends('frontend.layout')

@section('title', 'Yeni Adres | Argnest')
@section('description', 'Argnest musteri paneli yeni adres ekleme.')

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.36),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.30),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
            <p class="text-sm font-black uppercase tracking-widest text-blue-200">Adreslerim</p>
            <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">Yeni Adres Ekle</h1>
            <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">Kargo ve teslimat surecleri icin adres bilgilerinizi kaydedin.</p>
        </div>
    </section>

    <section class="bg-slate-50 py-20">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <form action="{{ route('frontend.customer.addresses.store') }}" method="POST" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70">
                @csrf
                @include('frontend.customer.addresses.form', ['buttonLabel' => 'Adresi Kaydet'])
            </form>
        </div>
    </section>
@endsection
