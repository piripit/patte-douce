@extends('emails.layout')

@section('content')

<p style="margin:0 0 16px;"><strong>Nouvelle demande de rendez-vous.</strong></p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#edf1f2;border-radius:12px;margin:0 0 16px;">
    <tr>
        <td style="padding:14px 18px;font-size:15px;line-height:1.7;">
            <strong>Service :</strong> {{ $appointment->service }}<br>
            <strong>Quand :</strong> {{ $appointment->whenLabel() }}<br>
            <strong>Chien :</strong> {{ $appointment->dog_name }}<br>
            <strong>Client :</strong> {{ $appointment->client_name }}<br>
            <strong>Email :</strong> {{ $appointment->client_email }}<br>
            <strong>Téléphone :</strong> {{ $appointment->client_phone }}
        </td>
    </tr>
</table>

@if ($appointment->message)
    <p style="margin:0 0 16px;"><strong>Message du client :</strong><br>{{ $appointment->message }}</p>
@endif

<p style="margin:0;">
    <a href="{{ route('admin.appointments.index') }}"
       style="display:inline-block;background:#2347c5;color:#ffffff;text-decoration:none;font-weight:bold;padding:12px 22px;border-radius:999px;">
        Répondre à la demande
    </a>
</p>

@endsection
