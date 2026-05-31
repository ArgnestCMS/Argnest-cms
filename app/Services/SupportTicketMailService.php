<?php

namespace App\Services;

use App\Filament\Resources\SupportTickets\SupportTicketResource;
use App\Mail\SupportTicketCreatedAdminMail;
use App\Mail\SupportTicketCreatedCustomerMail;
use App\Mail\SupportTicketReplyAdminMail;
use App\Mail\SupportTicketReplyCustomerMail;
use App\Models\SiteSetting;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SupportTicketMailService
{
    public function ticketCreated(SupportTicket $ticket, SupportMessage $message): void
    {
        try {
            $ticket->loadMissing('customer');

            $this->sendToCustomer(
                $ticket,
                new SupportTicketCreatedCustomerMail($ticket, $message, $this->customerTicketUrl($ticket)),
                'support_ticket_created_customer',
            );

            $this->sendToAdmins(
                new SupportTicketCreatedAdminMail($ticket, $message, $this->adminTicketUrl($ticket)),
                'support_ticket_created_admin',
                $ticket,
            );
        } catch (Throwable $exception) {
            $this->logNotificationFailure('support_ticket_created', $ticket, $exception);
        }
    }

    public function customerReplied(SupportTicket $ticket, SupportMessage $message): void
    {
        try {
            $this->sendToAdmins(
                new SupportTicketReplyAdminMail($ticket, $message, $this->adminTicketUrl($ticket)),
                'support_ticket_reply_admin',
                $ticket,
            );
        } catch (Throwable $exception) {
            $this->logNotificationFailure('support_ticket_reply_admin', $ticket, $exception);
        }
    }

    public function adminReplied(SupportTicket $ticket, SupportMessage $message): void
    {
        try {
            $ticket->loadMissing('customer');

            $this->sendToCustomer(
                $ticket,
                new SupportTicketReplyCustomerMail($ticket, $message, $this->customerTicketUrl($ticket)),
                'support_ticket_reply_customer',
            );
        } catch (Throwable $exception) {
            $this->logNotificationFailure('support_ticket_reply_customer', $ticket, $exception);
        }
    }

    private function sendToCustomer(SupportTicket $ticket, Mailable $mail, string $event): void
    {
        $email = $ticket->customer?->email;

        if (blank($email)) {
            Log::warning('Support ticket mail skipped: customer email missing.', [
                'event' => $event,
                'ticket_id' => $ticket->id,
                'ticket_no' => $ticket->ticket_no,
            ]);

            return;
        }

        $this->send($email, $mail, $event, $ticket);
    }

    private function sendToAdmins(Mailable $mail, string $event, ?SupportTicket $ticket = null): void
    {
        $recipients = $this->adminRecipients();

        if ($recipients === []) {
            Log::warning('Support ticket mail skipped: admin email missing.', [
                'event' => $event,
                'ticket_id' => $ticket?->id,
                'ticket_no' => $ticket?->ticket_no,
            ]);

            return;
        }

        foreach ($recipients as $recipient) {
            $this->send($recipient, clone $mail, $event, $ticket);
        }
    }

    private function send(string $recipient, Mailable $mail, string $event, ?SupportTicket $ticket = null): void
    {
        try {
            app(MailConfigurationService::class)->apply();

            Mail::to($recipient)->send($mail);
        } catch (Throwable $exception) {
            Log::error('Support ticket mail could not be sent.', [
                'event' => $event,
                'recipient' => $recipient,
                'ticket_id' => $ticket?->id,
                'ticket_no' => $ticket?->ticket_no,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function adminRecipients(): array
    {
        $settings = SiteSetting::query()->first();

        foreach ([
            $settings?->support_notification_email,
            $settings?->admin_notification_email,
            $settings?->contact_email,
            $settings?->email,
            config('mail.from.address'),
        ] as $email) {
            $recipients = $this->parseRecipients($email);

            if ($recipients !== []) {
                return $recipients;
            }
        }

        return [];
    }

    private function parseRecipients(?string $email): array
    {
        return collect(preg_split('/[,;]/', (string) $email))
            ->map(fn (string $recipient): string => trim($recipient))
            ->filter(fn (string $recipient): bool => filter_var($recipient, FILTER_VALIDATE_EMAIL) !== false)
            ->unique()
            ->values()
            ->all();
    }

    private function customerTicketUrl(SupportTicket $ticket): string
    {
        return route('frontend.customer.support.show', $ticket);
    }

    private function adminTicketUrl(SupportTicket $ticket): string
    {
        return SupportTicketResource::getUrl('edit', ['record' => $ticket]);
    }

    private function logNotificationFailure(string $event, SupportTicket $ticket, Throwable $exception): void
    {
        Log::error('Support ticket notification failed before mail send.', [
            'event' => $event,
            'ticket_id' => $ticket->id,
            'ticket_no' => $ticket->ticket_no,
            'error' => $exception->getMessage(),
        ]);
    }
}
