@extends('frontend.layout')

@section('title', 'Destek Taleplerim | Argnest')
@section('description', 'Argnest musteri paneli teknik destek biletleri.')

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.36),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.30),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-sm font-black uppercase tracking-widest text-blue-200">Teknik Destek</p>
                    <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">Destek Taleplerim</h1>
                    <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">Bilet gecmisinizi, durumlari ve admin cevaplarini tek ekrandan takip edin.</p>
                </div>
                <a href="{{ route('frontend.customer.support.create') }}" class="inline-flex rounded-2xl bg-white px-6 py-3.5 text-sm font-black text-slate-950 shadow-xl shadow-blue-950/20 transition hover:-translate-y-0.5 hover:bg-blue-50">Yeni Bilet Ac</a>
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <nav class="mb-8 flex flex-wrap gap-3 rounded-3xl border border-slate-200 bg-white p-3 shadow-sm">
                <a href="{{ route('frontend.customer.dashboard') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Dashboard</a>
                <a href="{{ route('frontend.customer.services') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Hizmetlerim</a>
                <a href="{{ route('frontend.customer.files.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Dosyalarim</a>
                <a href="{{ route('frontend.customer.notifications.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Bildirimlerim</a>
                <a href="{{ route('frontend.customer.reviews.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Yorumlarim</a>
                <a href="{{ route('frontend.customer.activities.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Aktivitelerim</a>
                <a href="{{ route('frontend.customer.support.index') }}" class="rounded-2xl bg-slate-950 px-5 py-3 text-sm font-black text-white">Destek</a>
                <a href="{{ route('frontend.customer.profile') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Profilim</a>
                <a href="{{ route('frontend.customer.password') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Şifre Değiştir</a>
                <a href="{{ route('frontend.customer.security') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Güvenlik Merkezi</a>
            </nav>

            @if (session('success'))
                <div class="mb-6 rounded-3xl border border-emerald-200 bg-emerald-50 p-5 text-sm font-bold text-emerald-700">{{ session('success') }}</div>
            @endif

            <div class="grid gap-5">
                @forelse ($tickets as $ticket)
                    @php
                        $statusClass = match ($ticket->status) {
                            'answered' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                            'pending' => 'bg-amber-50 text-amber-700 ring-amber-200',
                            'closed' => 'bg-slate-100 text-slate-600 ring-slate-200',
                            default => 'bg-blue-50 text-blue-700 ring-blue-200',
                        };
                        $priorityClass = match ($ticket->priority) {
                            'urgent' => 'bg-red-50 text-red-700 ring-red-200',
                            'high' => 'bg-amber-50 text-amber-700 ring-amber-200',
                            'low' => 'bg-slate-100 text-slate-600 ring-slate-200',
                            default => 'bg-blue-50 text-blue-700 ring-blue-200',
                        };
                    @endphp
                    <a href="{{ route('frontend.customer.support.show', $ticket) }}" class="block rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70 transition hover:-translate-y-1 hover:shadow-2xl">
                        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <p class="text-xs font-black uppercase tracking-widest text-blue-600">{{ $ticket->ticket_no }}</p>
                                <h2 class="mt-2 text-2xl font-black tracking-tight text-slate-950">{{ $ticket->subject }}</h2>
                                <p class="mt-3 text-sm font-bold text-slate-500">{{ $ticket->category ?: 'Genel Destek' }} / Son guncelleme: {{ $ticket->updated_at?->format('d.m.Y H:i') }}</p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span class="rounded-full px-3 py-1.5 text-xs font-black ring-1 {{ $statusClass }}">{{ $statusOptions[$ticket->status] ?? $ticket->status }}</span>
                                <span class="rounded-full px-3 py-1.5 text-xs font-black ring-1 {{ $priorityClass }}">{{ $priorityOptions[$ticket->priority] ?? $ticket->priority }}</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/70">
                        <p class="text-xl font-black text-slate-950">Henuz destek biletiniz yok.</p>
                        <p class="mt-3 text-sm leading-6 text-slate-600">Bir teknik konu, talep veya servis sorusu icin yeni bilet olusturabilirsiniz.</p>
                        <a href="{{ route('frontend.customer.support.create') }}" class="mt-6 inline-flex rounded-2xl bg-blue-600 px-6 py-3.5 text-sm font-black text-white shadow-lg shadow-blue-100 transition hover:-translate-y-0.5 hover:bg-blue-700">Ilk Bileti Ac</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $tickets->links() }}
            </div>
        </div>
    </section>
@endsection
