<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Administration') – {{ config('patte.name') }}</title>
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ctext y='.9em' font-size='90'%3E🐾%3C/text%3E%3C/svg%3E">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400..800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/brand.css') }}?v={{ filemtime(public_path('css/brand.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
</head>
<body class="admin-body">

<div class="admin-shell">

    {{-- ===== Menu latéral (réservé au prestataire) ===== --}}
    <aside class="admin-sidebar">
        <div class="admin-sidebar-top">
            <a class="admin-brand" href="{{ route('admin.appointments.index') }}">
                <span class="brand-tag" aria-hidden="true">🐾</span>
                <span>
                    <strong>{{ config('patte.name') }}</strong>
                    <small>Espace prestataire</small>
                </span>
            </a>
            <button class="btn btn-line-light btn-sm d-md-none" type="button" data-bs-toggle="collapse"
                    data-bs-target="#adminMenu" aria-controls="adminMenu" aria-expanded="false" aria-label="Ouvrir le menu">
                Menu
            </button>
        </div>

        <div class="collapse" id="adminMenu">
            <ul class="admin-nav">
                <li>
                    <a href="{{ route('admin.appointments.index') }}"
                       class="{{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
                        <span>Demandes</span>
                        @if ($pendingCount > 0)
                            <span class="admin-count" title="Demandes en attente">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.availabilities.index') }}"
                       class="{{ request()->routeIs('admin.availabilities.*') ? 'active' : '' }}">
                        <span>Créneaux</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.boarding.index') }}"
                       class="{{ request()->routeIs('admin.boarding.*') ? 'active' : '' }}">
                        <span>Gardes</span>
                    </a>
                </li>
            </ul>

            <div class="admin-side-foot">
                <a href="{{ route('accueil') }}" target="_blank" rel="noopener">Voir le site public</a>
                <div class="admin-user">Connecté : {{ auth()->user()->name }}</div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-line-light btn-sm w-100">Se déconnecter</button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ===== Contenu ===== --}}
    <main class="admin-main">
        @include('partials.alerts')
        @yield('content')
    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
