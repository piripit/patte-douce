@extends('layouts.app')

@section('title', 'Mentions légales')

@section('content')

{{--
    MODÈLE À COMPLÉTER ET À FAIRE VÉRIFIER avant la mise en ligne.
    Les informations entre crochets se renseignent dans config/patte.php.
--}}

<section class="page-head">
    <div class="container">
        <h1>Mentions légales</h1>
    </div>
</section>

<section class="container pb-5">
    <div class="legal">

        <h2>Éditeur du site</h2>
        <p>
            {{ config('patte.legal.owner') }}<br>
            {{ config('patte.legal.status') }}<br>
            SIRET : {{ config('patte.legal.siret') }}<br>
            Adresse : {{ config('patte.legal.address') }}<br>
            Téléphone : {{ config('patte.phone') }}<br>
            Email : {{ config('patte.email') }}
        </p>

        <h2>Hébergement</h2>
        <p>{{ config('patte.legal.host') }}</p>

        <h2>Données personnelles</h2>
        <p>
            Le formulaire de prise de rendez-vous recueille votre nom, votre email, votre téléphone, le nom de votre chien
            et, si vous le souhaitez, un message. Ces informations servent uniquement à traiter votre demande de rendez-vous
            et à vous répondre.
        </p>
        <p>Durée de conservation : [à préciser, par exemple 3 ans après le dernier contact].</p>
        <p>
            Vous pouvez demander l'accès, la rectification ou la suppression de vos données en écrivant à
            {{ config('patte.email') }}.
        </p>

        <h2>Cookies</h2>
        <p>
            Le site utilise uniquement des cookies techniques, nécessaires à son fonctionnement (sécurité des formulaires,
            connexion de l'espace prestataire). Ils ne servent à aucun suivi publicitaire.
        </p>

    </div>
</section>

@endsection
