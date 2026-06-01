@extends('frontend.layout')

@section('title', 'Müşteri Yorumları | ' . ($settings?->site_name ?? 'Argnest'))
@section('description', 'Argnest müşterilerinin onaylı deneyimleri ve değerlendirmeleri.')
@section('canonical', route('frontend.customer-reviews.index'))

@section('content')
    <section class="relative overflow-hidden border-b border-slate-200 bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_18%_18%,rgba(37,99,235,0.30),transparent_34%),radial-gradient(circle_at_82%_12%,rgba(14,165,233,0.22),transparent_32%),linear-gradient(135deg,#020617_0%,#0f172a_58%,#111827_100%)]"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
            <div class="max-w-3xl">
                <p class="text-sm font-black uppercase tracking-widest text-blue-200">Onaylı Değerlendirmeler</p>
                <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">Müşteri Yorumları</h1>
                <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300 sm:text-lg">
                    Argnest müşterilerinin onaylı deneyimleri.
                </p>
                <a href="{{ $reviewButtonUrl }}" class="mt-8 inline-flex rounded-2xl bg-blue-600 px-5 py-3 text-sm font-black text-white shadow-xl shadow-blue-950/30 transition hover:-translate-y-0.5 hover:bg-blue-700">
                    Yorum Yap
                </a>
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if ($reviews->isNotEmpty())
                <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($reviews as $review)
                        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-100/60">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-black text-slate-950">{{ $review->publicName() }}</p>
                                    <p class="mt-1 text-xs font-semibold text-slate-400">
                                        {{ ($review->approved_at ?? $review->created_at)?->format('d.m.Y') }}
                                    </p>
                                </div>
                                @if ($review->rating)
                                    <span class="shrink-0 rounded-full bg-amber-50 px-3 py-1 text-xs font-black text-amber-600">{{ $review->rating }}/5</span>
                                @endif
                            </div>

                            @if ($review->title)
                                <h2 class="mt-6 text-xl font-black tracking-tight text-slate-950">{{ $review->title }}</h2>
                            @endif

                            <p class="mt-4 text-sm leading-7 text-slate-600">
                                {{ $review->comment }}
                            </p>
                        </article>
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $reviews->links() }}
                </div>
            @else
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center shadow-sm">
                    <p class="text-sm font-black uppercase tracking-widest text-blue-600">Henüz yorum yok</p>
                    <h2 class="mt-3 text-2xl font-black text-slate-950">Onaylı müşteri yorumu bulunmuyor.</h2>
                    <p class="mx-auto mt-4 max-w-xl text-sm leading-7 text-slate-600">
                        İlk onaylı deneyimler yayınlandığında bu sayfada listelenecek.
                    </p>
                    <a href="{{ $reviewButtonUrl }}" class="mt-7 inline-flex rounded-2xl bg-blue-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-blue-100 transition hover:-translate-y-0.5 hover:bg-blue-700">
                        Yorum Yap
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection
