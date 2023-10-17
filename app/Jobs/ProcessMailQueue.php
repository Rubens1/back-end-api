<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\OrderStatusMail;
use Illuminate\Support\Facades\Mail;

class ProcessMailQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    public $email;
    public $nome;
    public $message;
    public $subject;
    public function __construct($email, $nome, $message, $subject)
    {
        $this->email = $email;

        $this->nome = $nome;

        $this->message = $message;

        $this->subject = $subject;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::queue(
            new OrderStatusMail(
                $this->nome,
                $this->email,
                $this->message,
                $this->subject
            )
        );
    }
}
