@extends('layouts.app')

@section('title', 'Promenade, garde et visites pour chiens')
@section('description', 'Dog-sitter – '.config('patte.area').' : promenade, garde et visites à domicile. Réservez votre créneau en ligne.')

@section('content')

{{-- ===== Accroche ===== --}}
<section class="hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <h1>Votre chien est entre de bonnes pattes.</h1>
                <p class="hero-lead">
                    Promenades, garde à la journée ou de nuit, visites à domicile.
                    Je m'occupe de votre chien comme du mien, {{ config('patte.area') }}.
                </p>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <a href="{{ route('rendez-vous') }}" class="btn btn-cobalt btn-lg">Réserver un créneau</a>
                    <a href="{{ route('services') }}" class="btn btn-line btn-lg">Voir les services et tarifs</a>
                </div>
                <ul class="hero-points">
                    <li>Réservation en ligne, sans appel ni attente</li>
                    <li>Chaque demande est validée par mes soins</li>
                    <li>Un seul interlocuteur, du début à la fin</li>
                </ul>
            </div>

            <div class="col-lg-5 hero-art">
                {{-- La médaille : l'élément visuel de la page --}}
                <svg class="hero-tag" viewBox="0 0 400 470" role="img"
                     aria-label="Médaille de chien portant le nom {{ config('patte.name') }}">
                    <rect x="184" y="-10" width="32" height="130" rx="6" fill="#2347c5"/>
                    <circle cx="200" cy="300" r="158" fill="#d6e34b" stroke="#1d2a36" stroke-width="6"/>
                    <circle cx="200" cy="300" r="136" fill="none" stroke="#1d2a36" stroke-width="2.5"
                            stroke-dasharray="3 9" stroke-linecap="round"/>
                    <circle cx="200" cy="160" r="10" fill="#edf1f2" stroke="#1d2a36" stroke-width="4"/>
                    <circle cx="200" cy="126" r="28" fill="none" stroke="#1d2a36" stroke-width="8"/>
                    <g fill="#1d2a36" transform="translate(145 200) scale(1.1)">
                        <ellipse cx="50" cy="66" rx="22" ry="18"/>
                        <ellipse cx="22" cy="42" rx="9" ry="12" transform="rotate(-18 22 42)"/>
                        <ellipse cx="40" cy="26" rx="9" ry="13" transform="rotate(-6 40 26)"/>
                        <ellipse cx="60" cy="26" rx="9" ry="13" transform="rotate(6 60 26)"/>
                        <ellipse cx="78" cy="42" rx="9" ry="12" transform="rotate(18 78 42)"/>
                    </g>
                    <text x="200" y="352" text-anchor="middle" font-size="40" font-weight="800" fill="#1d2a36"
                          font-family="'Bricolage Grotesque', system-ui, sans-serif">{{ config('patte.name') }}</text>
                    <text x="200" y="388" text-anchor="middle" font-size="20" font-weight="500" fill="#1d2a36"
                          font-family="'Bricolage Grotesque', system-ui, sans-serif">{{ config('patte.area') }}</text>
                </svg>
            </div>
        </div>
    </div>
</section>

{{-- ===== Services ===== --}}
<section class="band band-white">
    <div class="container">
        <div class="band-head">
            <h2>Les services</h2>
            <p>Choisissez une prestation, puis un créneau parmi mes disponibilités.</p>
        </div>
        @include('partials.menu-services')
    </div>
</section>

{{-- ===== Déroulé ===== --}}
<section class="band">
    <div class="container">
        <div class="band-head">
            <h2>Comment ça se passe</h2>
        </div>
        <ol class="steps">
            <li>
                <h3>Vous choisissez un créneau</h3>
                <p>Parmi mes disponibilités, en ligne, avec le service qui convient à votre chien.</p>
            </li>
            <li>
                <h3>Je valide votre demande</h3>
                <p>Je vérifie mon planning et je vous réponds par email ou par téléphone.</p>
            </li>
            <li>
                <h3>Votre chien est pris en charge</h3>
                <p>Au jour prévu, je m'occupe de lui et je vous donne de ses nouvelles.</p>
            </li>
        </ol>
    </div>
</section>

@include('partials.cta-reserver')

@endsection
