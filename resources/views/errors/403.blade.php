@extends('layouts.app')

@section('title', 'Accès refusé')

@section('content')
<section class="error-page">
    <div class="container">
        <div class="error-code">403</div>
        <h1 class="h2 mt-3">Cette page est réservée.</h1>
        <p class="text-muted">Vous n'avez pas l'autorisation d'accéder à cette page.</p>
        <a href="{{ route('accueil') }}" class="btn btn-cobalt">Retour à l'accueil</a>
    </div>
</section>
@endsection
