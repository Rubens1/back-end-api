<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
class PreCadastroMail extends Mailable
{
    use Queueable, SerializesModels;

    public $dados = [];

    /**
     * Create a new message instance.
     */
    public function __construct($dados = [])
    {

        $this->dados = $dados;

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pre Cadastro Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.pre-cadastro', 
            with:[
                "nome" => $this->dados["nome"],
                "celular" => $this->dados["celular"],
                "empresa" => $this->dados["empresa"],
                "email" => $this->dados["email"],
                "mensagem" => $this->dados["mensagem"]
            ]
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
