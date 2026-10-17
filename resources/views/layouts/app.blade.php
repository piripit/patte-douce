<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@hasSection('title')@yield('title') – @endif{{ config('patte.name') }}</title>
    <meta name="description" content="@yield('description', config('patte.tagline'))">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ config('patte.name') }} – {{ config('patte.tagline') }}">
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ctext y='.9em' font-size='90'%3E🐾%3C/text%3E%3C/svg%3E">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400..800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/brand.css') }}?v={{ filemtime(public_path('css/brand.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ filemtime(public_path('css/site.css')) }}">
</head>
<body>
<a class="skip-link" href="#contenu">Aller au contenu</a>

<header class="site-header">
    <nav class="navbar navbar-expand-lg" aria-label="Navigation principale">
        <div class="container">
            <a class="brand" href="{{ route('accueil') }}">
                <span class="brand-tag" aria-hidden="true">🐾</span>
                {{ config('patte.name') }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu"
                    aria-controls="mainMenu" aria-expanded="false" aria-label="Ouvrir le menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainMenu">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('services') ? 'active' : '' }}" href="{{ route('services') }}">Services</a>
                    </li>
                    <li class="nav-item my-2 my-lg-0">
                        <a class="btn btn-cobalt btn-sm" href="{{ route('rendez-vous') }}">Prise de rendez-vous</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main id="contenu">
    <div class="container">
        @include('partials.alerts')
    </div>

    @yield('content')
</main>

<footer class="site-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <span class="brand mb-3">
                    <span class="brand-tag" aria-hidden="true">🐾</span>
                    {{ config('patte.name') }}
                </span>
                <p class="mb-0">{{ config('patte.tagline') }}.<br>{{ config('patte.area') }}.</p>
            </div>
            <div class="col-6 col-lg-3">
                <h2 class="footer-title">Le site</h2>
                <ul class="footer-list">
                    <li><a href="{{ route('services') }}">Services</a></li>
                    <li><a href="{{ route('rendez-vous') }}">Prise de rendez-vous</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-4">
                <h2 class="footer-title">Me joindre</h2>
                <ul class="footer-list">
                    <li><a href="tel:{{ preg_replace('/\s+/', '', config('patte.phone')) }}">{{ config('patte.phone') }}</a></li>
                    <li><a href="mailto:{{ config('patte.email') }}">{{ config('patte.email') }}</a></li>
                    <li>{{ config('patte.hours') }}</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <span>© {{ date('Y') }} {{ config('patte.name') }}</span>
            <span>
                <a href="{{ route('mentions-legales') }}">Mentions légales</a>
                &nbsp;&nbsp;
                <a href="{{ route('login') }}">Espace prestataire</a>
            </span>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
