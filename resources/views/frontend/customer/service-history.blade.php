@extends('frontend.layout')

@section('title', 'Hizmet Gecmisi | Argnest')
@section('description', 'Argnest musteri paneli hizmet gecmisi sayfasi.')

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.36),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.30),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
            <p class="text-sm font-black uppercase tracking-widest text-blue-200">Musteri Paneli</p>
            <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">Hizmet Gecmisi</h1>
            <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">Gecmiste ve aktif olarak aldiginiz hizmetlerin kayitlarini tek ekrandan takip edin.</p>
        </div>
    </section>

    <section class="bg-slate-50 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <nav class="mb-8 flex flex-wrap gap-3 rounded-3xl border border-slate-200 bg-white p-3 shadow-sm">
                <a href="{{ route('frontend.customer.dashboard') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Dashboard</a>
                <a href="{{ route('frontend.customer.services') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Hizmetlerim</a>
                <a href="{{ route('frontend.customer.service-history') }}" class="rounded-2xl bg-slate-950 px-5 py-3 text-sm font-black text-white">Hizmet Gecmisi</a>
                <a href="{{ route('frontend.customer.files.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Dosyalarim</a>
                <a href="{{ route('frontend.customer.notifications.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Bildirimlerim</a>
                <a href="{{ route('frontend.customer.reviews.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Yorumlarim</a>
                <a href="{{ route('frontend.customer.activities.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Aktivitelerim</a>
                <a href="{{ route('frontend.customer.support.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Destek</a>
                <a href="{{ route('frontend.customer.profile') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Profilim</a>
                <a href="{{ route('frontend.customer.password') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Sifre Degistir</a>
                <a href="{{ route('frontend.customer.security') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Guvenlik Merkezi</a>
            </nav>

            <div class="mb-6 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['label' => 'Toplam hizmet', 'value' => $serviceStats['total'] ?? 0],
                    ['label' => 'Aktif hizmet', 'value' => $serviceStats['active'] ?? 0],
                    ['label' => 'Pasif hizmet', 'value' => $serviceStats['passive'] ?? 0],
                    ['label' => 'Suresi gecen hizmet', 'value' => $serviceStats['expired'] ?? 0],
                ] as $stat)
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-3xl font-black text-slate-950">{{ $stat['value'] }}</p>
                        <p class="mt-2 text-sm font-bold text-slate-500">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-200/70">
                @if ($services->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                            <thead class="bg-slate-50 text-xs font-black uppercase tracking-widest text-slate-500">
                                <tr>
                                    <th class="px-6 py-4">Hizmet</th>
                                    <th class="px-6 py-4">Domain</th>
                                    <th class="px-6 py-4">Olusturulma</th>
                                    <th class="px-6 py-4">Son kullanim</th>
                                    <th class="px-6 py-4">Durum</th>
                                    <th class="px-6 py-4">Aktif/Pasif</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($services as $service)
                                    @php
                                        $renewalStatus = $service->renewalStatus();
                                        $statusClass = match ($renewalStatus) {
                                            'expired' => 'bg-red-50 text-red-700 ring-red-200',
                                            'critical' => 'bg-red-50 text-red-700 ring-red-200',
                                            'warning' => 'bg-amber-50 text-amber-700 ring-amber-200',
                                            'safe' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                                            default => 'bg-slate-100 text-slate-600 ring-slate-200',
                                        };
                                    @endphp
                                    <tr class="align-top">
                                        <td class="px-6 py-5">
                                            <p class="font-black text-slate-950">{{ $service->service_name }}</p>
                                            <p class="mt-1 text-xs font-bold text-slate-500">{{ $service->hosting_package ?: 'Paket belirtilmedi' }}</p>
                                        </td>
                                        <td class="px-6 py-5 font-bold text-slate-700">{{ $service->domain_name ?: 'Belirtilmedi' }}</td>
                                        <td class="px-6 py-5 font-bold text-slate-700">{{ $service->created_at?->timezone('Europe/Istanbul')->format('d.m.Y H:i') ?: 'Belirtilmedi' }}</td>
                                        <td class="px-6 py-5 font-bold text-slate-700">{{ $service->expiry_date?->format('d.m.Y') ?: 'Tarih belirtilmedi' }}</td>
                                        <td class="px-6 py-5">
                                            <span class="inline-flex rounded-full px-3 py-1.5 text-xs font-black ring-1 {{ $statusClass }}">
                                                {{ $renewalStatusOptions[$renewalStatus] ?? $renewalStatus }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5">
                                            <span class="inline-flex rounded-full px-3 py-1.5 text-xs font-black ring-1 {{ $service->is_active ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-slate-100 text-slate-600 ring-slate-200' }}">
                                                {{ $service->is_active ? 'Aktif' : 'Pasif' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8">
                        <p class="text-xl font-black text-slate-950">Henuz hizmet gecmisi yok.</p>
                        <p class="mt-3 text-sm leading-6 text-slate-600">Hesabiniza hizmet tanimlandiginda aktif ve pasif tum kayitlar burada gorunecek.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
