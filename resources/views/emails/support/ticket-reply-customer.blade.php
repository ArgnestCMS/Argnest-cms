<div style="font-family: Arial, sans-serif; color: #0f172a; line-height: 1.6;">
    <h1 style="margin: 0 0 12px; font-size: 22px;">Destek talebinize cevap verildi</h1>

    <p style="margin: 0 0 12px;">Merhaba {{ $ticket->customer?->name }},</p>
    <p style="margin: 0;">Destek ekibimiz talebinize yeni bir cevap ekledi.</p>

    @include('emails.support._ticket-summary')
</div>
