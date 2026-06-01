@extends('install.layout')

@section('title', 'Argnest Kurulum | Kurulum')

@section('content')
    <div class="steps"><span class="step">Adım 4</span><span class="step">Kurulum</span></div>
    <h2>Kurulumu başlat</h2>
    <p class="muted">
        Seçilen işlem: <strong>{{ $type === 'clean' ? 'Temiz Kurulum' : ($type === 'full_zip' ? 'Full ZIP Restore' : 'SQL Restore') }}</strong>.
        İşlem bitene kadar sayfayı kapatmayın.
    </p>
    <form method="POST" action="{{ route('install.process') }}">
        @csrf
        <div class="actions"><button class="btn btn-primary">Kurulumu Başlat</button></div>
    </form>
@endsection
