@php
    $status = \App\Models\SupportTicket::statusOptions()[$ticket->status] ?? $ticket->status;
    $priority = \App\Models\SupportTicket::priorityOptions()[$ticket->priority] ?? $ticket->priority;
@endphp

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin: 20px 0; font-family: Arial, sans-serif;">
    <tr>
        <td style="padding: 8px 0; color: #475569; width: 130px;">Ticket No</td>
        <td style="padding: 8px 0; color: #0f172a; font-weight: 700;">{{ $ticket->ticket_no }}</td>
    </tr>
    <tr>
        <td style="padding: 8px 0; color: #475569;">Konu</td>
        <td style="padding: 8px 0; color: #0f172a;">{{ $ticket->subject }}</td>
    </tr>
    <tr>
        <td style="padding: 8px 0; color: #475569;">Durum</td>
        <td style="padding: 8px 0; color: #0f172a;">{{ $status }}</td>
    </tr>
    <tr>
        <td style="padding: 8px 0; color: #475569;">Oncelik</td>
        <td style="padding: 8px 0; color: #0f172a;">{{ $priority }}</td>
    </tr>
    <tr>
        <td style="padding: 8px 0; color: #475569; vertical-align: top;">Son mesaj</td>
        <td style="padding: 8px 0; color: #0f172a; white-space: pre-line;">{{ $message->message }}</td>
    </tr>
</table>

<p style="margin: 24px 0;">
    <a href="{{ $ticketUrl }}" style="display: inline-block; padding: 12px 18px; background: #2563eb; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 700;">Ticketi Goruntule</a>
</p>

<p style="margin: 0; color: #64748b; font-size: 13px; word-break: break-all;">
    Link: <a href="{{ $ticketUrl }}" style="color: #2563eb;">{{ $ticketUrl }}</a>
</p>
