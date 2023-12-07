<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\ContatoMail;
use Illuminate\Support\Facades\Mail;

class ContatoQueueMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $nome;
    public $celular;
    public $mensagem;
    /**
     * Create a new job instance.
     */
    public function __construct($email, $nome, $celular, $mensagem)
    {
        $this->email = $email;

        $this->nome = $nome;

        $this->celular = $celular;

        $this->mensagem = $mensagem;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
        try {
            Mail::to($this->email)
                ->queue (
                    new ContatoMail($this->email, $this->nome, $this->celular, $this->mensagem)
                );
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
