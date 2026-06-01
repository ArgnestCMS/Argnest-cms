@extends('frontend.layout')

@section('title', 'Adreslerim | Argnest')
@section('description', 'Argnest musteri paneli adres/kargo bilgileri.')

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.36),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.30),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-sm font-black uppercase tracking-widest text-blue-200">Musteri Paneli</p>
                    <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">Adreslerim</h1>
                    <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">Fatura, teslimat ve kargo surecleri icin adres bilgilerinizi yonetin.</p>
                </div>
                <a href="{{ route('frontend.customer.addresses.create') }}" class="inline-flex rounded-2xl bg-white px-6 py-3.5 text-sm font-black text-slate-950 shadow-xl shadow-blue-950/20 transition hover:-translate-y-0.5 hover:bg-blue-50">Yeni Adres Ekle</a>
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <nav class="mb-8 flex flex-wrap gap-3 rounded-3xl border border-slate-200 bg-white p-3 shadow-sm">
                <a href="{{ route('frontend.customer.dashboard') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Dashboard</a>
                <a href="{{ route('frontend.customer.services') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Hizmetlerim</a>
                <a href="{{ route('frontend.customer.service-history') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Hizmet Gecmisi</a>
                <a href="{{ route('frontend.customer.addresses.index') }}" class="rounded-2xl bg-slate-950 px-5 py-3 text-sm font-black text-white">Adreslerim</a>
                <a href="{{ route('frontend.customer.files.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Dosyalarim</a>
                <a href="{{ route('frontend.customer.notifications.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Bildirimlerim</a>
                <a href="{{ route('frontend.customer.support.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Destek</a>
                <a href="{{ route('frontend.customer.profile') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Profilim</a>
            </nav>

            @if (session('success'))
                <div class="mb-6 rounded-3xl border border-emerald-200 bg-emerald-50 p-5 text-sm font-bold text-emerald-700">{{ session('success') }}</div>
            @endif

            <div class="grid gap-5 lg:grid-cols-2">
                @forelse ($addresses as $address)
                    <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="text-xs font-black uppercase tracking-widest text-blue-600">Adres</p>
                                <h2 class="mt-2 text-2xl font-black text-slate-950">{{ $address->title }}</h2>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @if ($address->is_default)
                                    <span class="rounded-full bg-blue-50 px-3 py-1.5 text-xs font-black text-blue-700 ring-1 ring-blue-200">Varsayilan</span>
                                @endif
                                <a href="{{ route('frontend.customer.addresses.edit', $address) }}" class="rounded-full bg-slate-950 px-4 py-1.5 text-xs font-black text-white transition hover:bg-blue-700">Duzenle</a>
                            </div>
                        </div>
                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            @foreach ([
                                'Ulke' => $address->country,
                                'Il' => $address->city,
                                'Ilce' => $address->district,
                                'Mahalle' => $address->neighborhood ?: 'Belirtilmedi',
                                'Sokak/Cadde' => $address->street ?: 'Belirtilmedi',
                                'Bina / Daire' => trim(($address->building_no ?: '-') . ' / ' . ($address->apartment_no ?: '-')),
                                'Posta Kodu' => $address->postal_code ?: 'Belirtilmedi',
                            ] as $label => $value)
                                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                    <p class="text-xs font-black uppercase tracking-widest text-blue-600">{{ $label }}</p>
                                    <p class="mt-2 break-words text-sm font-bold text-slate-800">{{ $value }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-5 rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <p class="text-xs font-black uppercase tracking-widest text-blue-600">Acik Adres</p>
                            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $address->address }}</p>
                        </div>
                    </article>
                @empty
                    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/70 lg:col-span-2">
                        <p class="text-xl font-black text-slate-950">Henuz adres kaydiniz yok.</p>
                        <p class="mt-3 text-sm leading-6 text-slate-600">Teslimat ve fatura surecleri icin ilk adresinizi ekleyebilirsiniz.</p>
                        <a href="{{ route('frontend.customer.addresses.create') }}" class="mt-6 inline-flex rounded-2xl bg-blue-600 px-6 py-3.5 text-sm font-black text-white shadow-lg shadow-blue-100 transition hover:-translate-y-0.5 hover:bg-blue-700">Adres Ekle</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $addresses->links() }}
            </div>
        </div>
    </section>
@endsection
