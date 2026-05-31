@extends('frontend.layout')

@section('title', 'Yeni Destek Bileti | Argnest')
@section('description', 'Argnest musteri panelinde yeni teknik destek bileti olusturun.')

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.36),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.30),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
            <p class="text-sm font-black uppercase tracking-widest text-blue-200">Teknik Destek</p>
            <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">Yeni Destek Bileti</h1>
            <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">Talebinizi detaylandirin, gerekirse dosya ekleyin ve sureci panelinizden takip edin.</p>
        </div>
    </section>

    <section class="bg-slate-50 py-20">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <form action="{{ route('frontend.customer.support.store') }}" method="POST" enctype="multipart/form-data" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70 sm:p-8">
                @csrf
                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="subject" class="text-sm font-black text-slate-800">Konu</label>
                        <input id="subject" name="subject" value="{{ old('subject') }}" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                        @error('subject')<p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="category" class="text-sm font-black text-slate-800">Kategori</label>
                        <input id="category" name="category" value="{{ old('category') }}" placeholder="Hosting, domain, e-posta..." class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                        @error('category')<p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="priority" class="text-sm font-black text-slate-800">Oncelik</label>
                        <select id="priority" name="priority" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                            @foreach ($priorityOptions as $value => $label)
                                <option value="{{ $value }}" @selected(old('priority', 'normal') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('priority')<p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="message" class="text-sm font-black text-slate-800">Mesaj</label>
                        <textarea id="message" name="message" rows="7" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">{{ old('message') }}</textarea>
                        @error('message')<p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="attachments" class="text-sm font-black text-slate-800">Dosyalar (opsiyonel)</label>
                        <input id="attachments" name="attachments[]" type="file" multiple class="mt-2 w-full rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-4 text-sm font-bold text-slate-700">
                        <p class="mt-2 text-xs font-bold text-slate-500">PDF, Office, txt, gorsel, zip ve rar dosyalari desteklenir. Dosya basina maksimum 20 MB.</p>
                        @error('attachments.*')<p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="mt-8 flex flex-wrap gap-3">
                    <button class="rounded-2xl bg-blue-600 px-6 py-3.5 text-sm font-black text-white shadow-lg shadow-blue-100 transition hover:-translate-y-0.5 hover:bg-blue-700">Bileti Gonder</button>
                    <a href="{{ route('frontend.customer.support.index') }}" class="rounded-2xl border border-slate-200 bg-white px-6 py-3.5 text-sm font-black text-slate-800 transition hover:-translate-y-0.5 hover:border-blue-200 hover:text-blue-700">Geri Don</a>
                </div>
            </form>
        </div>
    </section>
@endsection
