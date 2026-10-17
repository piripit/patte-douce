<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// Email envoyé au CLIENT (demande reçue, confirmée, refusée, annulée)
class AppointmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Appointment $appointment, public string $event)
    {
    }

    public function envelope(): Envelope
    {
        $subject = match ($this->event) {
            'received' => 'Votre demande a bien été reçue',
            'confirmed' => 'Rendez-vous confirmé pour '.$this->appointment->dog_name,
            'refused' => 'Votre demande de rendez-vous',
            'cancelled' => 'Votre rendez-vous est annulé',
        };

        return new Envelope(
            subject: $subject.' – '.config('patte.name'),
            // Quand le client répond à l'email, la réponse arrive chez le prestataire
            replyTo: [new Address(config('patte.email'), config('patte.name'))],
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.appointment');
    }
}
