<?php

namespace App\Services;

use App\Mail\TicketCreatedMail;
use App\Models\Ticket;
use App\Models\TicketReporter;
use App\Models\User;
use App\Notifications\NewTicketNotification;
use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Repositories\Contracts\TicketReporterRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class HelpdeskService
{
    public function __construct(private
        TicketRepositoryInterface $ticketRepo, private
        TicketReporterRepositoryInterface $reporterRepo, private
        TicketService $ticketService,
        )
    {
    }

    /**
     * Create a ticket from public submission.
     */
    public function createPublicTicket(array $data, Request $request): array
    {
        return DB::transaction(function () use ($data, $request) {
            // 1. Find or create reporter
            $reporter = $this->reporterRepo->findOrCreateByData([
                'full_name' => $data['full_name'],
                'nik' => $data['nik'],
                'whatsapp' => $data['whatsapp'],
                'email' => $data['email'],
            ]);

            // 2. Handle attachment
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $attachmentPath = $file->store('tickets/attachments', 'public');
            }

            // 3. Create ticket
            $ticketData = [
                'ticket_number' => Ticket::generateTicketNumber(),
                'title' => strip_tags($data['title']),
                'description' => strip_tags($data['description']),
                'category_id' => $data['category_id'],
                'priority' => $data['priority'] ?? 'Medium',
                'status' => 'Open',
                'user_id' => null,
                'reporter_id' => $reporter->id,
                'assigned_to' => null,
                'sla_deadline' => $this->ticketService->calculateSlaDeadline($data['priority'] ?? 'Medium'),
                'ip_address' => $request->ip(),
                'user_agent' => substr($request->userAgent() ?? '', 0, 500),
                'source' => 'public',
            ];

            $ticket = $this->ticketRepo->create($ticketData);

            // 4. Create attachment record if file uploaded
            if ($attachmentPath && $request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $ticket->attachments()->create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $attachmentPath,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'uploaded_by' => null,
                ]);
            }

            // 5. Generate signed tracking URL (valid for 7 days)
            $trackingUrl = URL::signedRoute('helpdesk.track.result', [
                'ticket' => $ticket->ticket_number,
            ], now()->addDays(7));

            // 6. Send emails (gracefully skip if mail not configured)
            $this->sendNotifications($ticket, $reporter, $trackingUrl);

            return [
                'ticket' => $ticket,
                'reporter' => $reporter,
                'tracking_url' => $trackingUrl,
            ];
        });
    }

    /**
     * Send email notifications, silently skip on failure.
     */
    private function sendNotifications(Ticket $ticket, TicketReporter $reporter, string $trackingUrl): void
    {
        if (!$this->isMailConfigured()) {
            \Illuminate\Support\Facades\Log::info('Mail not configured — skipping email notifications for ticket: ' . $ticket->ticket_number);
            return;
        }

        try {
            // Email to reporter
            Mail::to($reporter->email)->queue(
                new TicketCreatedMail($ticket, $reporter, $trackingUrl)
            );

            // Notify admin & manager
            $adminsAndManagers = User::whereHas('roles', function ($q) {
                $q->whereIn('slug', ['admin', 'manager']);
            })->get();

            if ($adminsAndManagers->isNotEmpty()) {
                Notification::send($adminsAndManagers, new NewTicketNotification($ticket, $reporter));
            }
        }
        catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to send ticket email notifications: ' . $e->getMessage());
        }
    }

    /**
     * Check if mail is properly configured for sending.
     */
    private function isMailConfigured(): bool
    {
        $mailer = config('mail.default', 'log');

        // log and array drivers don't actually send emails
        if (in_array($mailer, ['log', 'array'])) {
            return false;
        }

        // For SMTP, check host is set
        if ($mailer === 'smtp') {
            return !empty(config('mail.mailers.smtp.host'))
                && !empty(config('mail.mailers.smtp.port'));
        }

        return true;
    }

    /**
     * Track a ticket by ticket number only.
     */
    public function trackTicket(string $ticketNumber): ?Ticket
    {
        return Ticket::with(['category', 'reporter', 'assignee', 'comments' => function ($q) {
            $q->where('is_internal', false)->latest();
        }])
            ->where('ticket_number', $ticketNumber)
            ->first();
    }

    /**
     * Generate a new signed tracking URL.
     */
    public function generateTrackingUrl(string $ticketNumber): string
    {
        return URL::signedRoute('helpdesk.track.result', [
            'ticket' => $ticketNumber,
        ], now()->addDays(7));
    }
}
