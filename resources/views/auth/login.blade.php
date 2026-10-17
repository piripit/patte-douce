<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Connexion – {{ config('patte.name') }}</title>
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ctext y='.9em' font-size='90'%3E🐾%3C/text%3E%3C/svg%3E">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400..800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/brand.css') }}?v={{ filemtime(public_path('css/brand.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
</head>
<body>
<div class="auth-wrap">
    <div class="auth-box">

        <a class="brand" href="{{ route('accueil') }}">
            <span class="brand-tag" aria-hidden="true">🐾</span>
            {{ config('patte.name') }}
        </a>

        @include('partials.alerts')

        <div class="auth-card mt-3">
            <h1>Espace prestataire</h1>
            <p class="text-muted">Connectez-vous pour gérer vos demandes et vos disponibilités.</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" name="email" id="email" autocomplete="username" autofocus required
                           class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-4">
                    <label class="form-label" for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" autocomplete="current-password" required
                           class="form-control @error('password') is-invalid @enderror">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="btn btn-cobalt btn-lg w-100">Se connecter</button>
            </form>
        </div>

        <a class="auth-back" href="{{ route('accueil') }}">Retour au site</a>
    </div>
</div>
</body>
</html>
