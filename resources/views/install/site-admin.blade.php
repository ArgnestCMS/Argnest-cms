@extends('install.layout')

@section('title', 'Argnest Kurulum | Site ve Yönetici')

@section('content')
    <div class="steps"><span class="step">Adım 3</span><span class="step">Site ve yönetici</span></div>
    <h2>İlk ayarlar</h2>
    <p class="muted">Temiz kurulum için site bilgileri ve ilk admin hesabı zorunludur.</p>

    <form method="POST" action="{{ route('install.site-admin.store') }}">
        @csrf
        <div class="grid grid-2">
            <div><label>Site Adı</label><input type="text" name="site_name" value="{{ old('site_name', $data['site_name']) }}" required></div>
            <div><label>Site URL</label><input type="url" name="site_url" value="{{ old('site_url', $data['site_url']) }}" required></div>
            <div><label>Ad Soyad</label><input type="text" name="admin_name" value="{{ old('admin_name', $data['admin_name']) }}" required></div>
            <div><label>E-posta</label><input type="email" name="admin_email" value="{{ old('admin_email', $data['admin_email']) }}" required></div>
            <div><label>Şifre</label><input type="password" name="admin_password" required minlength="8"></div>
            <div><label>Şifre Tekrar</label><input type="password" name="admin_password_confirmation" required minlength="8"></div>
        </div>
        <div class="actions"><button class="btn btn-primary">Devam Et</button></div>
    </form>
@endsection
