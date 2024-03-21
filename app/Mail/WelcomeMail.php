<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $invoiceNumber;
    protected $amount;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $invoiceNumber, int $amount)
    {
        $this->invoiceNumber = $invoiceNumber;
        $this->amount = $amount;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Payment Processed')->view('welcome-mail')->with([
            'invoiceNumber' => $this->invoiceNumber,
            'amount' => $this->amount,
        ]);
    }
}
