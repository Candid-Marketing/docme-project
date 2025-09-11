<?php

namespace App\Jobs;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateInvoicePdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payment;
    protected $user;

    public function __construct($payment, $user)
    {
        $this->payment = $payment;
        $this->user = $user;
    }
    public function handle()
    {
        $fileName = "{$this->user->first_name}_{$this->user->last_name}_receipt_{$this->payment->id}.pdf";
        $storagePath = "public/invoices/{$fileName}";

        $pdf = Pdf::loadView('payments.invoice', [
            'payment' => $this->payment,
            'user'    => $this->user,
        ]);

        Storage::put($storagePath, $pdf->output());
    }
}
