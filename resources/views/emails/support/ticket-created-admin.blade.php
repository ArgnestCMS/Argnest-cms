<div style="font-family: Arial, sans-serif; color: #0f172a; line-height: 1.6;">
    <h1 style="margin: 0 0 12px; font-size: 22px;">Yeni destek talebi var</h1>

    <p style="margin: 0 0 12px;">{{ $ticket->customer?->name ?? 'Musteri' }} yeni bir destek talebi olusturdu.</p>

    @include('emails.support._ticket-summary')
</div>
