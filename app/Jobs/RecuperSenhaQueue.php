<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SendPasswordMail;
use Illuminate\Support\Facades\Mail;

class RecuperSenhaQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    protected $nome;
    protected $link;
    protected $email;
    public function __construct($email, $nome, $link)
    {
        $this->email = $email;
        $this->nome = $nome;
        $this->link = $link;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->email)
                ->queue(
                    new SendPasswordMail(
                        $this->nome,
                        $this->link
                    )
                );
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
