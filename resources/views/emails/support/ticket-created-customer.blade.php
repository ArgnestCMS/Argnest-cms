<div style="font-family: Arial, sans-serif; color: #0f172a; line-height: 1.6;">
    <h1 style="margin: 0 0 12px; font-size: 22px;">Destek talebiniz alindi</h1>

    <p style="margin: 0 0 12px;">Merhaba {{ $ticket->customer?->name }},</p>
    <p style="margin: 0;">Destek talebiniz basariyla olusturuldu. Ekibimiz talebinizi inceleyip en kisa surede size donus yapacak.</p>

    @include('emails.support._ticket-summary')
</div>
