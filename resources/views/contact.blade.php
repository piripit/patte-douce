@extends('layouts.app')

@section('title', 'Contact')
@section('description', 'Contactez-moi par téléphone ou par email pour toute question sur la garde ou la promenade de votre chien.')

@section('content')

<section class="page-head">
    <div class="container">
        <h1>Contact</h1>
        <p>Une question, un besoin particulier ? Appelez-moi ou écrivez-moi.</p>
    </div>
</section>

<section class="container pb-5">
    <ul class="contact-list">
        <li>
            <span class="contact-label">Téléphone</span>
            <span class="contact-value"><a href="tel:{{ preg_replace('/\s+/', '', config('patte.phone')) }}">{{ config('patte.phone') }}</a></span>
        </li>
        <li>
            <span class="contact-label">Email</span>
            <span class="contact-value"><a href="mailto:{{ config('patte.email') }}">{{ config('patte.email') }}</a></span>
        </li>
        <li>
            <span class="contact-label">Zone d'intervention</span>
            <span class="contact-value">{{ config('patte.area') }}</span>
        </li>
        <li>
            <span class="contact-label">Horaires</span>
            <span class="contact-value">{{ config('patte.hours') }}</span>
        </li>
    </ul>
</section>

@include('partials.cta-reserver')

@endsection
