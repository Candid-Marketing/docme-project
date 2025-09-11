<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $payment;
    public $user;
    public $invoice;

    public function __construct($payment, $user, $invoice)
    {
        $this->payment = $payment;
        $this->user = $user;
        $this->invoice = $invoice;
    }

    public function build()
    {
        return $this->subject('Your Invoice')
                    ->view('emails.invoice') // assuming you have an invoice view
                    ->attach($this->invoice->invoice_file, [
                        'as' => 'invoice.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}
