<div style="font-family: Arial, sans-serif; color: #0f172a; line-height: 1.6;">
    <h1 style="margin: 0 0 12px; font-size: 22px;">Musteri destek talebine cevap yazdi</h1>

    <p style="margin: 0 0 12px;">{{ $ticket->customer?->name ?? 'Musteri' }} destek talebine yeni bir cevap ekledi.</p>

    @include('emails.support._ticket-summary')
</div>
