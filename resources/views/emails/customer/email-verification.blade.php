@php
    $siteName = config('app.name', 'Argnest');
@endphp

<div style="font-family:Arial, sans-serif; color:#0f172a; line-height:1.6;">
    <h1 style="margin:0 0 16px; font-size:24px;">E-posta adresinizi dogrulayin</h1>
    <p>Merhaba {{ $customer->name }},</p>
    <p>{{ $siteName }} musteri panelini kullanmaya devam etmek icin e-posta adresinizi dogrulayin.</p>
    <p style="margin:28px 0;">
        <a href="{{ $verificationUrl }}" style="display:inline-block; border-radius:12px; background:#2563eb; color:#ffffff; padding:12px 18px; text-decoration:none; font-weight:700;">
            E-postami Dogrula
        </a>
    </p>
    <p>Buton calismazsa asagidaki baglantiyi tarayiciniza yapistirabilirsiniz:</p>
    <p style="word-break:break-all;">
        <a href="{{ $verificationUrl }}">{{ $verificationUrl }}</a>
    </p>
    <p>Bu baglanti 24 saat gecerlidir.</p>
</div>
