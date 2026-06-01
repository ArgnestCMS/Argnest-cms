@extends('install.layout')

@section('title', 'Argnest Kurulum | Sistem Kontrolü')

@section('content')
    <div class="steps"><span class="step">Adım 1</span><span class="step">Sistem kontrolü</span></div>
    <h2>Sistem hazır mı?</h2>
    <p class="muted">Eksik gereksinimler varsa kurulumdan önce sunucu yapılandırmasını tamamlayın.</p>

    <div class="grid grid-2" style="margin-top:18px;">
        <div class="card">
            @foreach ($checks as $check)
                <div class="check">
                    <strong>{{ $check['label'] }}</strong>
                    <span class="pill {{ $check['ok'] ? 'ok' : 'bad' }}">{{ $check['ok'] ? 'Uygun' : 'Eksik' }}</span>
                </div>
            @endforeach
        </div>
        <form method="POST" action="{{ route('install.type') }}" class="card">
            @csrf
            <h2>Kurulum türü</h2>
            <label class="choice card"><input type="radio" name="type" value="clean" @checked($selectedType === 'clean')> Temiz Kurulum <p class="muted">Yeni veritabanı, seed verileri, site ayarı ve ilk admin oluşturulur.</p></label>
            <label class="choice card"><input type="radio" name="type" value="full_zip" @checked($selectedType === 'full_zip')> Full ZIP Yedekten Geri Yükle <p class="muted">database.sql ve yedek içindeki storage/public dosyaları geri yüklenir.</p></label>
            <label class="choice card"><input type="radio" name="type" value="sql" @checked($selectedType === 'sql')> Sadece SQL Yedeği Yükle <p class="muted">Yalnızca veritabanı import edilir, dosyalara dokunulmaz.</p></label>
            <div class="actions"><button class="btn btn-primary">Devam Et</button></div>
        </form>
    </div>
@endsection
