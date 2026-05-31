@extends('frontend.layout')

@section('title', 'Yorumlarim | Argnest')
@section('description', 'Argnest musteri paneli yorumlarim sayfasi.')

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.36),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.30),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-sm font-black uppercase tracking-widest text-blue-200">Musteri Yorumlari</p>
                    <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">Yorumlarim</h1>
                    <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">Argnest deneyiminizi paylasin, onay sonrasi yorumunuz sitede referans olarak kullanilabilsin.</p>
                </div>
                <a href="{{ route('frontend.customer.reviews.create') }}" class="inline-flex rounded-2xl bg-white px-6 py-3.5 text-sm font-black text-slate-950 shadow-xl shadow-blue-950/20 transition hover:-translate-y-0.5 hover:bg-blue-50">Yeni Yorum Yaz</a>
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <nav class="mb-8 flex flex-wrap gap-3 rounded-3xl border border-slate-200 bg-white p-3 shadow-sm">
                <a href="{{ route('frontend.customer.dashboard') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Dashboard</a>
                <a href="{{ route('frontend.customer.services') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Hizmetlerim</a>
                <a href="{{ route('frontend.customer.reviews.index') }}" class="rounded-2xl bg-slate-950 px-5 py-3 text-sm font-black text-white">Yorumlarim</a>
                <a href="{{ route('frontend.customer.activities.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Aktivitelerim</a>
                <a href="{{ route('frontend.customer.support.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Destek</a>
                <a href="{{ route('frontend.customer.dashboard') }}#profil" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Profilim</a>
            </nav>

            @if (session('success'))
                <div class="mb-6 rounded-3xl border border-emerald-200 bg-emerald-50 p-5 text-sm font-bold text-emerald-700">{{ session('success') }}</div>
            @endif

            <div class="grid gap-5">
                @forelse ($reviews as $review)
                    @php
                        $statusClass = match ($review->status) {
                            'approved' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                            'rejected' => 'bg-red-50 text-red-700 ring-red-200',
                            default => 'bg-amber-50 text-amber-700 ring-amber-200',
                        };
                    @endphp
                    <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70">
                        <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="rounded-full px-3 py-1.5 text-xs font-black ring-1 {{ $statusClass }}">{{ $statusOptions[$review->status] ?? $review->status }}</span>
                                    @if ($review->rating)
                                        <span class="text-sm font-black text-amber-500">{{ str_repeat('*', $review->rating) }}</span>
                                    @endif
                                </div>
                                <h2 class="mt-4 text-2xl font-black tracking-tight text-slate-950">{{ $review->title ?: 'Baslik belirtilmedi' }}</h2>
                                <p class="mt-3 whitespace-pre-line text-sm leading-7 text-slate-600">{{ $review->comment }}</p>
                            </div>
                            <div class="shrink-0 rounded-2xl border border-slate-100 bg-slate-50 p-4 text-sm font-bold text-slate-600">
                                <p>Gonderim: {{ $review->created_at?->format('d.m.Y H:i') }}</p>
                                <p class="mt-2">Isim: {{ $review->hide_name ? 'Gizli' : 'Gorunebilir' }}</p>
                                <p class="mt-2">Iletisim: {{ $review->hide_contact ? 'Gizli' : 'Gorunebilir' }}</p>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/70">
                        <p class="text-xl font-black text-slate-950">Henuz yorumunuz yok.</p>
                        <p class="mt-3 text-sm leading-6 text-slate-600">Argnest deneyiminizi paylasarak yeni musteriler icin guven referansi olabilirsiniz.</p>
                        <a href="{{ route('frontend.customer.reviews.create') }}" class="mt-6 inline-flex rounded-2xl bg-blue-600 px-6 py-3.5 text-sm font-black text-white shadow-lg shadow-blue-100 transition hover:-translate-y-0.5 hover:bg-blue-700">Ilk Yorumu Yaz</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $reviews->links() }}
            </div>
        </div>
    </section>
@endsection
