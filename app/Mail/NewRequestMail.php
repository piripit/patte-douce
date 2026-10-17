<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// Email envoyé au PRESTATAIRE quand un client fait une demande
class NewRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Appointment $appointment)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle demande : '.$this->appointment->service.' pour '.$this->appointment->dog_name,
            // Répondre à cet email écrit directement au client
            replyTo: [new Address($this->appointment->client_email, $this->appointment->client_name)],
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.new-request');
    }
}
