<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Throwable;

class TestMail extends Command
{
    protected $signature = 'patte:test-mail {to : Adresse qui recevra le message de test}';

    protected $description = 'Envoie un email de test pour vérifier la configuration SMTP';

    public function handle(): int
    {
        $to = $this->argument('to');

        try {
            Mail::raw(
                'Ceci est un email de test envoyé par '.config('patte.name').'. Si vous le lisez, l\'envoi fonctionne.',
                function ($message) use ($to) {
                    $message->to($to)->subject('Test d\'envoi – '.config('patte.name'));
                }
            );
        } catch (Throwable $e) {
            $this->error('Échec de l\'envoi : '.$e->getMessage());

            return self::FAILURE;
        }

        $this->info('Email envoyé à '.$to.'. Vérifiez la boîte de réception et les courriers indésirables.');
        $this->line('Mode d\'envoi actuel : '.config('mail.default'));

        return self::SUCCESS;
    }
}
