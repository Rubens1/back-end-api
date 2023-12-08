<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SendColaboradorSenha;
use Illuminate\Support\Facades\Mail;


class SendColaboradorSenhaEmailQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $email;
    public $nome;
    public $link;

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
                ->queue (
                    new SendColaboradorSenha($this->nome, $this->link)
                );
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
