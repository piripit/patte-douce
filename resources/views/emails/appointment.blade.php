@extends('emails.layout')

@section('content')

<p style="margin:0 0 16px;">Bonjour {{ $appointment->client_name }},</p>

@if ($event === 'received')
    <p style="margin:0 0 16px;">
        J'ai bien reçu votre demande. <strong>Elle n'est pas encore confirmée</strong> :
        je vérifie mon planning et je vous réponds par email ou par téléphone dès que possible.
    </p>
@elseif ($event === 'confirmed')
    <p style="margin:0 0 16px;">
        <strong>Bonne nouvelle : votre rendez-vous est confirmé.</strong>
        Voici le récapitulatif.
    </p>
@elseif ($event === 'refused')
    <p style="margin:0 0 16px;">
        Je ne peux malheureusement pas donner suite à votre demande pour ces dates.
        Voici ce que vous aviez demandé.
    </p>
@elseif ($event === 'cancelled')
    <p style="margin:0 0 16px;">
        Je dois annuler le rendez-vous ci-dessous, et je le regrette.
        Je vous contacte pour vous proposer une solution.
    </p>
@endif

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#edf1f2;border-radius:12px;margin:0 0 16px;">
    <tr>
        <td style="padding:14px 18px;font-size:15px;line-height:1.7;">
            <strong>Service :</strong> {{ $appointment->service }}<br>
            <strong>Chien :</strong> {{ $appointment->dog_name }}<br>
            <strong>Quand :</strong> {{ $appointment->whenLabel() }}
        </td>
    </tr>
</table>

@if ($event === 'confirmed')
    <p style="margin:0 0 16px;">
        Pour toute modification, répondez simplement à cet email ou appelez-moi au {{ config('patte.phone') }}.
    </p>
@elseif ($event === 'refused' || $event === 'cancelled')
    <p style="margin:0 0 16px;">
        Vous pouvez faire une nouvelle demande sur d'autres dates :
        <a href="{{ route('rendez-vous') }}" style="color:#2347c5;font-weight:bold;">choisir d'autres dates</a>,
        ou me joindre au {{ config('patte.phone') }}.
    </p>
@endif

<p style="margin:0;">À bientôt,<br>{{ config('patte.name') }}</p>

@endsection
