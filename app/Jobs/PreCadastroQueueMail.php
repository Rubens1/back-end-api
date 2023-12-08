<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\PreCadastroMail;
use Illuminate\Support\Facades\Mail;

class PreCadastroQueueMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $nome;
    public $empresa;
    public $celular;
    public $mensagem;
    /**
     * Create a new job instance.
     */
    public function __construct($email, $nome, $celular, $mensagem, $empresa)
    {
        $this->email = $email;

        $this->nome = $nome;

        $this->celular = $celular;

        $this->mensagem = $mensagem;

        $this->empresa = $empresa;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->email)
                ->queue (
                    new PreCadastroMail($this->email, $this->nome, $this->celular, $this->mensagem)
                );
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
