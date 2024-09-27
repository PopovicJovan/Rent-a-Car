<?php

namespace App\Listeners;

use App\Events\InvoiceCreated;
use App\Mail\InvoiceMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendInvoiceMail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(InvoiceCreated $event): void
    {
        Mail::to($event->user->email)->send(new InvoiceMail($event->invoiceData));
    }
}
