@extends('frontend.layout')

@section('title', 'Yeni Yorum | Argnest')
@section('description', 'Argnest musteri panelinde yeni yorum gonderin.')

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.36),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.30),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
            <p class="text-sm font-black uppercase tracking-widest text-blue-200">Musteri Yorumlari</p>
            <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">Yeni Yorum</h1>
            <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">Yorumunuz admin onayindan sonra sitedeki guven alanlarinda gorunebilir.</p>
        </div>
    </section>

    <section class="bg-slate-50 py-20">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <form action="{{ route('frontend.customer.reviews.store') }}" method="POST" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70 sm:p-8">
                @csrf

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-800">
                        Lutfen formdaki eksik veya hatali alanlari kontrol edin.
                    </div>
                @endif

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="rating" class="text-sm font-black text-slate-800">Puan</label>
                        <select id="rating" name="rating" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                            <option value="">Puan secin</option>
                            @for ($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" @selected((string) old('rating') === (string) $i)>{{ $i }} / 5</option>
                            @endfor
                        </select>
                        @error('rating')<p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="title" class="text-sm font-black text-slate-800">Baslik</label>
                        <input id="title" name="title" value="{{ old('title') }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                        @error('title')<p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="comment" class="text-sm font-black text-slate-800">Yorum</label>
                        <textarea id="comment" name="comment" rows="7" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">{{ old('comment') }}</textarea>
                        @error('comment')<p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <input name="hide_name" type="hidden" value="0">
                        <input name="hide_name" type="checkbox" value="1" @checked(old('hide_name')) class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600">
                        <span>
                            <span class="block text-sm font-black text-slate-800">Ismim gizli kalsin</span>
                            <span class="mt-1 block text-xs font-bold text-slate-500">Onaylanan yorumda adiniz yerine Argnest Musterisi yazilir.</span>
                        </span>
                    </label>
                    <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <input name="hide_contact" type="hidden" value="0">
                        <input name="hide_contact" type="checkbox" value="1" @checked(old('hide_contact', true)) class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600">
                        <span>
                            <span class="block text-sm font-black text-slate-800">Iletisim bilgilerim gizli kalsin</span>
                            <span class="mt-1 block text-xs font-bold text-slate-500">Mail ve telefon bilgileriniz public alanda gosterilmez.</span>
                        </span>
                    </label>
                </div>
                <div class="mt-8 flex flex-wrap gap-3">
                    <button class="rounded-2xl bg-blue-600 px-6 py-3.5 text-sm font-black text-white shadow-lg shadow-blue-100 transition hover:-translate-y-0.5 hover:bg-blue-700">Yorumu Gonder</button>
                    <a href="{{ route('frontend.customer.reviews.index') }}" class="rounded-2xl border border-slate-200 bg-white px-6 py-3.5 text-sm font-black text-slate-800 transition hover:-translate-y-0.5 hover:border-blue-200 hover:text-blue-700">Geri Don</a>
                </div>
            </form>
        </div>
    </section>
@endsection
