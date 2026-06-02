@extends('install.layout')

@section('title', 'Argnest Kurulum | Yedekten Geri Yükle')

@section('content')
    <div class="steps"><span class="step">Adım 2.5</span><span class="step">Yedekten geri yükleme</span></div>
    <h2>{{ $type === 'full_zip' ? 'Full ZIP yedeği yükle' : 'SQL yedeği yükle' }}</h2>
    <p class="muted">Mevcut kurulum tespit edilirse geri yükleme öncesi <strong>pre-restore-backup.zip</strong> güvenlik yedeği denenir; boş veritabanında bu adım atlanır.</p>

    <form method="POST" action="{{ route('install.restore.store') }}" enctype="multipart/form-data">
        @csrf
        <label>{{ $type === 'full_zip' ? 'full-backup-xxxx.zip' : '.sql dosyası' }}</label>
        <input type="file" name="backup_file" accept="{{ $type === 'full_zip' ? '.zip' : '.sql,.txt' }}" required>
        <div class="actions"><button class="btn btn-primary">Yükle ve Devam Et</button></div>
    </form>
@endsection
