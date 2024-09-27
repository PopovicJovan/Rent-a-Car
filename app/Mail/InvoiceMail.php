<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoiceData;

    public function __construct($invoiceData)
    {
        $this->invoiceData = $invoiceData;
    }

    public function build()
    {
        $pdf = PDF::loadView('invoices.invoice', ['invoiceData' => $this->invoiceData]);

        return $this->view('view.invoice-email')
            ->attachData($pdf->output(), 'invoice.pdf', [
                'mime' => 'application/pdf',
            ])
            ->subject('Your Invoice');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.invoice-email',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
