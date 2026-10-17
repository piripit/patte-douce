@extends('layouts.app')

@section('title', 'Services et tarifs')
@section('description', 'Promenade, garde à la journée, garde de nuit et visite à domicile : services et tarifs.')

@section('content')

<section class="page-head">
    <div class="container">
        <h1>Services et tarifs</h1>
        <p>Les tarifs sont indicatifs : ils peuvent varier selon le chien et la distance. Je vous confirme le prix exact avant le rendez-vous.</p>
    </div>
</section>

<section class="band band-white" style="padding-top: 2rem;">
    <div class="container">
        @include('partials.menu-services')
    </div>
</section>

<section class="band">
    <div class="container">
        <div class="band-head">
            <h2>Questions fréquentes</h2>
        </div>
        <div class="faq" style="max-width: 46rem;">
            <details>
                <summary>Comment réserver ?</summary>
                <p>Choisissez un créneau sur la page de prise de rendez-vous, indiquez le nom de votre chien et vos coordonnées, puis envoyez la demande.</p>
            </details>
            <details>
                <summary>Puis-je confier mon chien plus d'une semaine ?</summary>
                <p>Oui. Choisissez une garde, indiquez vos dates d'arrivée et de départ : je valide chaque séjour, quelle que soit sa durée. Au-delà de {{ config('patte.max_stay_days') }} jours, contactez-moi directement.</p>
            </details>
            <details>
                <summary>Ma demande est-elle confirmée tout de suite ?</summary>
                <p>Non. Je valide chaque demande en vérifiant mon planning, puis je vous réponds par email ou par téléphone. Tant que vous n'avez pas ma réponse, le rendez-vous n'est pas confirmé.</p>
            </details>
            <details>
                <summary>Aucun créneau ne me convient, que faire ?</summary>
                <p>Contactez-moi : il est parfois possible d'ouvrir une disponibilité supplémentaire.</p>
            </details>
            <details>
                <summary>Comment annuler ou modifier un rendez-vous ?</summary>
                <p>Prévenez-moi dès que possible par téléphone ou par email, en indiquant le nom de votre chien et la date prévue.</p>
            </details>
        </div>
    </div>
</section>

@include('partials.cta-reserver')

@endsection
