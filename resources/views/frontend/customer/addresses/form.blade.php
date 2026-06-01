@php
    $address ??= null;
    $fieldClass = 'mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-800 outline-none transition focus:border-blue-300 focus:ring-4 focus:ring-blue-100';
    $labelClass = 'text-xs font-black uppercase tracking-widest text-blue-600';
@endphp

@if ($errors->any())
    <div class="mb-6 rounded-3xl border border-red-200 bg-red-50 p-5 text-sm font-bold text-red-700">
        <p>Lutfen formdaki alanlari kontrol edin.</p>
    </div>
@endif

<div class="grid gap-5 md:grid-cols-2">
    <label>
        <span class="{{ $labelClass }}">Adres Basligi</span>
        <input name="title" value="{{ old('title', $address?->title) }}" class="{{ $fieldClass }}" required>
        @error('title') <span class="mt-2 block text-xs font-bold text-red-600">{{ $message }}</span> @enderror
    </label>
    <label>
        <span class="{{ $labelClass }}">Ulke</span>
        <input name="country" value="{{ old('country', $address?->country ?? 'Turkiye') }}" class="{{ $fieldClass }}" required>
        @error('country') <span class="mt-2 block text-xs font-bold text-red-600">{{ $message }}</span> @enderror
    </label>
    <label>
        <span class="{{ $labelClass }}">Il</span>
        <input name="city" value="{{ old('city', $address?->city) }}" class="{{ $fieldClass }}" required>
        @error('city') <span class="mt-2 block text-xs font-bold text-red-600">{{ $message }}</span> @enderror
    </label>
    <label>
        <span class="{{ $labelClass }}">Ilce</span>
        <input name="district" value="{{ old('district', $address?->district) }}" class="{{ $fieldClass }}" required>
        @error('district') <span class="mt-2 block text-xs font-bold text-red-600">{{ $message }}</span> @enderror
    </label>
    <label>
        <span class="{{ $labelClass }}">Mahalle</span>
        <input name="neighborhood" value="{{ old('neighborhood', $address?->neighborhood) }}" class="{{ $fieldClass }}">
        @error('neighborhood') <span class="mt-2 block text-xs font-bold text-red-600">{{ $message }}</span> @enderror
    </label>
    <label>
        <span class="{{ $labelClass }}">Sokak/Cadde</span>
        <input name="street" value="{{ old('street', $address?->street) }}" class="{{ $fieldClass }}">
        @error('street') <span class="mt-2 block text-xs font-bold text-red-600">{{ $message }}</span> @enderror
    </label>
    <label>
        <span class="{{ $labelClass }}">Bina No</span>
        <input name="building_no" value="{{ old('building_no', $address?->building_no) }}" class="{{ $fieldClass }}">
        @error('building_no') <span class="mt-2 block text-xs font-bold text-red-600">{{ $message }}</span> @enderror
    </label>
    <label>
        <span class="{{ $labelClass }}">Daire No</span>
        <input name="apartment_no" value="{{ old('apartment_no', $address?->apartment_no) }}" class="{{ $fieldClass }}">
        @error('apartment_no') <span class="mt-2 block text-xs font-bold text-red-600">{{ $message }}</span> @enderror
    </label>
    <label>
        <span class="{{ $labelClass }}">Posta Kodu</span>
        <input name="postal_code" value="{{ old('postal_code', $address?->postal_code) }}" class="{{ $fieldClass }}">
        @error('postal_code') <span class="mt-2 block text-xs font-bold text-red-600">{{ $message }}</span> @enderror
    </label>
    <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
        <input type="checkbox" name="is_default" value="1" class="h-5 w-5 rounded border-slate-300 text-blue-600" @checked(old('is_default', $address?->is_default))>
        <span class="text-sm font-black text-slate-800">Varsayilan Adres</span>
    </label>
    <label class="md:col-span-2">
        <span class="{{ $labelClass }}">Acik Adres</span>
        <textarea name="address" rows="5" class="{{ $fieldClass }}" required>{{ old('address', $address?->address) }}</textarea>
        @error('address') <span class="mt-2 block text-xs font-bold text-red-600">{{ $message }}</span> @enderror
    </label>
</div>

<div class="mt-8 flex flex-wrap gap-3">
    <button class="rounded-2xl bg-blue-600 px-6 py-3.5 text-sm font-black text-white shadow-lg shadow-blue-100 transition hover:-translate-y-0.5 hover:bg-blue-700">{{ $buttonLabel }}</button>
    <a href="{{ route('frontend.customer.addresses.index') }}" class="rounded-2xl border border-slate-200 bg-white px-6 py-3.5 text-sm font-black text-slate-800 transition hover:-translate-y-0.5 hover:border-blue-200 hover:text-blue-700">Vazgec</a>
</div>
