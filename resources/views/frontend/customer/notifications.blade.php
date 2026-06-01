@extends('frontend.layout')

@section('title', 'Bildirimlerim | Argnest')
@section('description', 'Argnest musteri paneli bildirim merkezi.')

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.36),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.30),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <p class="text-sm font-black uppercase tracking-widest text-blue-200">Musteri Paneli</p>
            <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">Bildirimlerim</h1>
            <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">Sistem bildirimlerinizi ve okunma durumlarini buradan takip edebilirsiniz.</p>
        </div>
    </section>

    <section class="bg-slate-50 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <nav class="mb-8 flex flex-wrap gap-3 rounded-3xl border border-slate-200 bg-white p-3 shadow-sm">
                <a href="{{ route('frontend.customer.dashboard') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Dashboard</a>
                <a href="{{ route('frontend.customer.services') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Hizmetlerim</a>
                <a href="{{ route('frontend.customer.files.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Dosyalarim</a>
                <a href="{{ route('frontend.customer.notifications.index') }}" class="rounded-2xl bg-slate-950 px-5 py-3 text-sm font-black text-white">Bildirimlerim</a>
                <a href="{{ route('frontend.customer.reviews.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Yorumlarim</a>
                <a href="{{ route('frontend.customer.support.index') }}" class="rounded-2xl px-5 py-3 text-sm font-black text-slate-700 transition hover:bg-blue-50 hover:text-blue-700">Destek</a>
            </nav>

            @if (session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @forelse ($notifications as $notification)
                <article class="mb-4 rounded-3xl border {{ $notification->is_read ? 'border-slate-200 bg-white' : 'border-blue-200 bg-blue-50' }} p-6 shadow-sm">
                    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="text-lg font-black text-slate-950">{{ $notification->title }}</h2>
                                <span class="rounded-full px-3 py-1 text-xs font-black {{ $notification->is_read ? 'bg-slate-100 text-slate-600' : 'bg-blue-600 text-white' }}">
                                    {{ $notification->is_read ? 'Okundu' : 'Okunmadi' }}
                                </span>
                                @if ($notification->type)
                                    <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-slate-500 ring-1 ring-slate-200">{{ $notification->type }}</span>
                                @endif
                            </div>
                            <p class="mt-3 text-sm leading-7 text-slate-600">{{ $notification->message }}</p>
                            <p class="mt-3 text-xs font-bold text-slate-400">
                                {{ $notification->created_at?->format('d.m.Y H:i') }}
                                @if ($notification->read_at)
                                    / Okunma: {{ $notification->read_at->format('d.m.Y H:i') }}
                                @endif
                            </p>
                        </div>
                        <div class="flex shrink-0 flex-wrap gap-2">
                            @if (! $notification->is_read)
                                <form action="{{ route('frontend.customer.notifications.read', $notification) }}" method="POST">
                                    @csrf
                                    <button class="rounded-2xl bg-blue-600 px-4 py-2.5 text-sm font-black text-white shadow-lg shadow-blue-100 transition hover:-translate-y-0.5 hover:bg-blue-700">
                                        Okundu Yap
                                    </button>
                                </form>
                            @endif
                            @if ($notification->link)
                                <form action="{{ route('frontend.customer.notifications.open', $notification) }}" method="POST">
                                    @csrf
                                    <button class="rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-black text-slate-800 transition hover:-translate-y-0.5 hover:border-blue-200 hover:text-blue-700">
                                        Baglantiyi Ac
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center shadow-sm">
                    <p class="text-sm font-black uppercase tracking-widest text-blue-600">Bildirim yok</p>
                    <h2 class="mt-3 text-2xl font-black text-slate-950">Henuz bildiriminiz bulunmuyor.</h2>
                    <p class="mx-auto mt-4 max-w-xl text-sm leading-7 text-slate-600">Yeni dosya, destek cevabi, hizmet veya yorum durumlari burada gorunecek.</p>
                </div>
            @endforelse

            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        </div>
    </section>
@endsection
