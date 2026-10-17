@extends('layouts.app')

@section('title', 'Session expirée')

@section('content')
<section class="error-page">
    <div class="container">
        <div class="error-code">419</div>
        <h1 class="h2 mt-3">Votre session a expiré.</h1>
        <p class="text-muted">Pour votre sécurité, la page est restée ouverte trop longtemps. Rechargez-la et recommencez.</p>
        <a href="{{ url()->previous() }}" class="btn btn-cobalt">Revenir en arrière</a>
    </div>
</section>
@endsection
