@extends('layouts.app')

@section('title', 'Page introuvable')

@section('content')
<section class="error-page">
    <div class="container">
        <div class="error-code">404</div>
        <h1 class="h2 mt-3">Cette page n'existe pas.</h1>
        <p class="text-muted">Elle a peut-être été déplacée ou l'adresse contient une erreur.</p>
        <a href="{{ route('accueil') }}" class="btn btn-cobalt">Retour à l'accueil</a>
    </div>
</section>
@endsection
