@extends('install.layout')

@section('title', 'Argnest Kurulum Tamamlandı')

@section('content')
    <div class="steps"><span class="step">Adım 5</span><span class="step">Tamamlandı</span></div>
    <h2>Kurulum başarıyla tamamlandı.</h2>
    <p class="muted">Kurulum kilitlendi. Installer tekrar çalışmaz.</p>

    <div class="card">
        <p><strong>Lock dosyası:</strong> storage/framework/argnest-installed.lock</p>
        <p><strong>Yeniden kurulum için:</strong> APP_INSTALLED=false yapılmalı ve lock dosyası silinmelidir.</p>
    </div>

    <div class="actions">
        <a class="btn btn-secondary" href="{{ route('home') }}">Ana Sayfa</a>
        <a class="btn btn-primary" href="{{ url('/admin') }}">Admin Paneli</a>
    </div>
@endsection
