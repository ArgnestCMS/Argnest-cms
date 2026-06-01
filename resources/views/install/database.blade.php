@extends('install.layout')

@section('title', 'Argnest Kurulum | Veritabanı')

@section('content')
    <div class="steps"><span class="step">Adım 2</span><span class="step">Veritabanı</span></div>
    <h2>Veritabanı bağlantısı</h2>
    <p class="muted">Bağlantı başarılıysa bilgiler .env dosyasına yazılır.</p>

    <form method="POST" action="{{ route('install.database.store') }}">
        @csrf
        <div class="grid grid-2">
            <div><label>DB_HOST</label><input type="text" name="host" value="{{ old('host', $database['host']) }}" required></div>
            <div><label>DB_PORT</label><input type="number" name="port" value="{{ old('port', $database['port']) }}" required></div>
            <div><label>DB_DATABASE</label><input type="text" name="database" value="{{ old('database', $database['database']) }}" required></div>
            <div><label>DB_USERNAME</label><input type="text" name="username" value="{{ old('username', $database['username']) }}" required></div>
            <div><label>DB_PASSWORD</label><input type="password" name="password" value="{{ old('password', $database['password']) }}"></div>
        </div>
        <div class="actions">
            <button class="btn btn-secondary" formaction="{{ route('install.database.test') }}">Bağlantıyı Test Et</button>
            <button class="btn btn-primary">Kaydet ve Devam Et</button>
        </div>
    </form>
@endsection
