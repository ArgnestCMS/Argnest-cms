<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Argnest Kurulum')</title>
    <style>
        :root { --navy:#0f172a; --blue:#2563eb; --ink:#1e293b; --muted:#64748b; --line:#dbe4f0; --bg:#f8fafc; }
        * { box-sizing:border-box; }
        body { margin:0; font-family:Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; background:var(--bg); color:var(--ink); }
        .hero { background:linear-gradient(135deg,#020617 0%,#0f172a 56%,#1e3a8a 100%); color:#fff; position:relative; overflow:hidden; }
        .hero:before { content:""; position:absolute; inset:-120px -80px auto auto; width:360px; height:360px; background:rgba(37,99,235,.34); border-radius:999px; filter:blur(70px); }
        .wrap { width:min(1120px, calc(100% - 32px)); margin:0 auto; position:relative; }
        .top { display:flex; justify-content:space-between; align-items:center; gap:16px; padding:24px 0; }
        .brand { display:flex; align-items:center; gap:12px; font-weight:900; letter-spacing:.02em; }
        .mark { width:40px; height:40px; border-radius:10px; display:grid; place-items:center; background:#fff; color:var(--blue); font-weight:900; }
        .modal-buttons { display:flex; flex-wrap:wrap; gap:8px; justify-content:flex-end; }
        .modal-buttons button, .btn { border:0; border-radius:10px; padding:11px 14px; font-weight:800; cursor:pointer; }
        .modal-buttons button { background:rgba(255,255,255,.1); color:#dbeafe; border:1px solid rgba(255,255,255,.18); }
        .headline { padding:34px 0 52px; max-width:760px; }
        .headline p { color:#bfdbfe; line-height:1.7; }
        h1 { margin:0; font-size:clamp(30px,5vw,56px); line-height:1.05; letter-spacing:-.02em; }
        h2 { margin:0 0 16px; font-size:24px; }
        .panel { margin:-30px auto 56px; background:#fff; border:1px solid var(--line); border-radius:18px; box-shadow:0 24px 70px rgba(15,23,42,.12); padding:24px; position:relative; }
        .grid { display:grid; gap:16px; }
        .grid-3 { grid-template-columns:repeat(3,minmax(0,1fr)); }
        .grid-2 { grid-template-columns:repeat(2,minmax(0,1fr)); }
        .card { border:1px solid var(--line); border-radius:14px; padding:18px; background:#fff; }
        .choice { cursor:pointer; transition:.18s ease; min-height:150px; }
        .choice:hover { border-color:#93c5fd; transform:translateY(-2px); }
        .choice input { margin-right:8px; }
        .muted { color:var(--muted); line-height:1.65; }
        .check { display:flex; justify-content:space-between; gap:12px; align-items:center; padding:12px 0; border-bottom:1px solid #edf2f7; }
        .check:last-child { border-bottom:0; }
        .pill { border-radius:999px; padding:5px 10px; font-size:12px; font-weight:900; }
        .ok { background:#dcfce7; color:#166534; }
        .bad { background:#fee2e2; color:#991b1b; }
        label { display:block; font-size:13px; font-weight:900; margin:0 0 8px; color:#334155; }
        input[type=text], input[type=email], input[type=password], input[type=number], input[type=url], input[type=file] { width:100%; border:1px solid var(--line); border-radius:12px; padding:13px 14px; font:inherit; background:#fff; }
        .actions { display:flex; flex-wrap:wrap; gap:10px; justify-content:flex-end; margin-top:22px; }
        .btn-primary { background:var(--blue); color:#fff; }
        .btn-secondary { background:#e2e8f0; color:#0f172a; text-decoration:none; display:inline-block; }
        .alert { border-radius:12px; padding:14px 16px; margin-bottom:16px; font-weight:800; }
        .alert-success { background:#dcfce7; color:#166534; }
        .alert-error { background:#fee2e2; color:#991b1b; }
        .steps { display:flex; flex-wrap:wrap; gap:8px; margin-bottom:20px; }
        .step { background:#eff6ff; color:#1d4ed8; border-radius:999px; padding:7px 11px; font-size:12px; font-weight:900; }
        dialog { border:0; border-radius:18px; width:min(620px, calc(100% - 28px)); padding:0; box-shadow:0 30px 100px rgba(0,0,0,.28); }
        dialog::backdrop { background:rgba(15,23,42,.62); }
        .modal { padding:24px; }
        .modal header { display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:12px; }
        .x { background:#e2e8f0; border:0; width:34px; height:34px; border-radius:9px; cursor:pointer; font-weight:900; }
        @media (max-width: 760px) { .top { align-items:flex-start; flex-direction:column; } .grid-3,.grid-2 { grid-template-columns:1fr; } .panel { padding:18px; } }
    </style>
</head>
<body>
    <section class="hero">
        <div class="wrap">
            <div class="top">
                <div class="brand"><div class="mark">A</div><div>Argnest Installer</div></div>
                <div class="modal-buttons">
                    <button type="button" onclick="about.showModal()">Biz Kimiz?</button>
                    <button type="button" onclick="project.showModal()">Proje Hakkında</button>
                    <button type="button" onclick="installGuide.showModal()">Kurulum Kılavuzu</button>
                    <button type="button" onclick="usageGuide.showModal()">Kullanım Kılavuzu</button>
                    <button type="button" onclick="license.showModal()">Lisans / GitHub</button>
                </div>
            </div>
            <div class="headline">
                <h1>Argnest kurulum sihirbazı</h1>
                <p>Yeni sunucu, temiz kurulum veya yedekten geri yükleme için kontrollü ve güvenli başlangıç akışı.</p>
            </div>
        </div>
    </section>

    <main class="wrap">
        <section class="panel">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif
            @yield('content')
        </section>
    </main>

    <dialog id="about"><div class="modal"><header><h2>Biz Kimiz?</h2><button class="x" onclick="about.close()">×</button></header><p class="muted">Argnest, işletmeler için web, ürün, destek ve müşteri yönetimi süreçlerini tek panelde toparlayan kurumsal bir dijital altyapıdır.</p></div></dialog>
    <dialog id="project"><div class="modal"><header><h2>Proje Hakkında</h2><button class="x" onclick="project.close()">×</button></header><p class="muted">Bu paket; içerik yönetimi, müşteri paneli, destek sistemi, canlı sohbet, güvenlik logları ve yedekleme araçlarıyla dağıtıma hazır bir Laravel uygulamasıdır.</p></div></dialog>
    <dialog id="installGuide"><div class="modal"><header><h2>Kurulum Kılavuzu</h2><button class="x" onclick="installGuide.close()">×</button></header><p class="muted">Önce sistem kontrollerini doğrulayın, kurulum türünü seçin, veritabanı bağlantısını test edin ve son adımda kurulumu başlatın. Kurulum tamamlanınca installer otomatik kilitlenir.</p></div></dialog>
    <dialog id="usageGuide"><div class="modal"><header><h2>Kullanım Kılavuzu</h2><button class="x" onclick="usageGuide.close()">×</button></header><p class="muted">Kurulumdan sonra admin paneline girerek site ayarlarını, kullanıcıları, destek taleplerini, canlı sohbeti ve sistem yedeklerini yönetebilirsiniz.</p></div></dialog>
    <dialog id="license"><div class="modal"><header><h2>Lisans / GitHub</h2><button class="x" onclick="license.close()">×</button></header><p class="muted">GitHub dağıtımı için projeyi kendi ortam değişkenlerinizle yapılandırın. Yeniden kurulum için <strong>APP_INSTALLED=false</strong> yapın ve <strong>storage/framework/argnest-installed.lock</strong> dosyasını silin.</p></div></dialog>
</body>
</html>
