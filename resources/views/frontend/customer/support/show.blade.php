@extends('frontend.layout')

@section('title', $ticket->ticket_no . ' | Destek | Argnest')
@section('description', 'Argnest musteri paneli destek bileti detayi.')

@section('content')
    <section class="relative overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(37,99,235,0.36),transparent_34%),radial-gradient(circle_at_84%_8%,rgba(124,58,237,0.30),transparent_30%),linear-gradient(135deg,#020617_0%,#0f172a_55%,#111827_100%)]"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
            <p class="text-sm font-black uppercase tracking-widest text-blue-200">{{ $ticket->ticket_no }}</p>
            <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">{{ $ticket->subject }}</h1>
            <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">{{ $ticket->category ?: 'Genel Destek' }} / {{ $ticket->created_at?->format('d.m.Y H:i') }}</p>
        </div>
    </section>

    <section class="bg-slate-50 py-20">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-[1fr_360px] lg:px-8">
            <div>
                @if (session('success'))
                    <div class="mb-6 rounded-3xl border border-emerald-200 bg-emerald-50 p-5 text-sm font-bold text-emerald-700">{{ session('success') }}</div>
                @endif

                <div class="space-y-5">
                    @foreach ($ticket->messages as $supportMessage)
                        <article class="rounded-3xl border {{ $supportMessage->is_admin ? 'border-blue-200 bg-blue-50' : 'border-slate-200 bg-white' }} p-6 shadow-lg shadow-slate-200/70">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm font-black text-slate-950">{{ $supportMessage->is_admin ? 'Argnest Destek Ekibi' : ($supportMessage->user?->name ?: 'Musteri') }}</p>
                                    <p class="mt-1 text-xs font-bold text-slate-500">{{ $supportMessage->created_at?->format('d.m.Y H:i') }}</p>
                                </div>
                                <span class="w-fit rounded-full px-3 py-1.5 text-xs font-black {{ $supportMessage->is_admin ? 'bg-blue-600 text-white' : 'bg-slate-950 text-white' }}">{{ $supportMessage->is_admin ? 'Admin Cevabi' : 'Musteri Mesaji' }}</span>
                            </div>
                            <p class="mt-5 whitespace-pre-line text-sm leading-7 text-slate-700">{{ $supportMessage->message }}</p>

                            @if ($supportMessage->attachments->isNotEmpty())
                                <div class="mt-5 flex flex-wrap gap-2">
                                    @foreach ($supportMessage->attachments as $attachment)
                                        <a href="{{ route('frontend.customer.support.attachments.download', $attachment) }}" class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-xs font-black text-slate-700 transition hover:border-blue-200 hover:text-blue-700">
                                            {{ $attachment->original_name }} ({{ number_format($attachment->file_size / 1024, 1) }} KB)
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>

                @if ($ticket->status !== 'closed')
                    <form action="{{ route('frontend.customer.support.reply', $ticket) }}" method="POST" enctype="multipart/form-data" class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70">
                        @csrf
                        <label for="message" class="text-sm font-black text-slate-800">Cevabiniz</label>
                        <textarea id="message" name="message" rows="6" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">{{ old('message') }}</textarea>
                        @error('message')<p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>@enderror

                        <label for="attachments" class="mt-5 block text-sm font-black text-slate-800">Dosyalar (opsiyonel)</label>
                        <input id="attachments" name="attachments[]" type="file" multiple class="mt-2 w-full rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-4 text-sm font-bold text-slate-700">
                        @error('attachments.*')<p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>@enderror

                        <button class="mt-6 rounded-2xl bg-blue-600 px-6 py-3.5 text-sm font-black text-white shadow-lg shadow-blue-100 transition hover:-translate-y-0.5 hover:bg-blue-700">Cevap Gonder</button>
                    </form>
                @endif
            </div>

            <aside class="h-fit rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70">
                <p class="text-sm font-black uppercase tracking-widest text-blue-600">Bilet Ozeti</p>
                <div class="mt-5 grid gap-3">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-black uppercase tracking-widest text-blue-600">Durum</p>
                        <p class="mt-2 text-sm font-bold text-slate-800">{{ $statusOptions[$ticket->status] ?? $ticket->status }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-black uppercase tracking-widest text-blue-600">Oncelik</p>
                        <p class="mt-2 text-sm font-bold text-slate-800">{{ $priorityOptions[$ticket->priority] ?? $ticket->priority }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-black uppercase tracking-widest text-blue-600">Son Guncelleme</p>
                        <p class="mt-2 text-sm font-bold text-slate-800">{{ $ticket->updated_at?->format('d.m.Y H:i') }}</p>
                    </div>
                </div>
                <a href="{{ route('frontend.customer.support.index') }}" class="mt-6 inline-flex rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-black text-slate-800 transition hover:-translate-y-0.5 hover:border-blue-200 hover:text-blue-700">Tum Biletlere Don</a>
            </aside>
        </div>
    </section>
@endsection
