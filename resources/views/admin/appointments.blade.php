@extends('layouts.admin')

@section('title', 'Demandes')

@section('content')

<div class="admin-head">
    <h1>Demandes</h1>
    <p>Répondez aux demandes de vos clients : le client reçoit un email à chaque réponse.</p>
</div>

{{-- ===== Chiffres clés ===== --}}
<div class="row g-3">
    <div class="col-6 col-lg-3">
        <div class="stat {{ $pending->count() > 0 ? 'is-alert' : '' }}">
            <div class="stat-num">{{ $pending->count() }}</div>
            <div class="stat-label">à traiter</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat">
            <div class="stat-num">{{ $upcoming->count() }}</div>
            <div class="stat-label">confirmés à venir</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat">
            <div class="stat-num">{{ $openSlots }}</div>
            <div class="stat-label">créneaux horaires ouverts</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat">
            <div class="stat-num">{{ $openDays }}</div>
            <div class="stat-label">jours de garde ouverts</div>
        </div>
    </div>
</div>

{{-- ===== À traiter ===== --}}
<h2 class="admin-section-title">À traiter</h2>

@forelse ($pending as $a)
    <article class="request">
        <div class="row g-3 align-items-center">
            <div class="col-lg-8">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <h3>{{ $a->service }} pour {{ $a->dog_name }}</h3>
                    <span class="pill pill-warning">En attente</span>
                    @if ($a->isStay())
                        <span class="pill pill-info">Séjour</span>
                    @endif
                </div>

                <p class="request-when">{{ $a->whenLabel() }}</p>

                <p class="request-meta">
                    <strong>{{ $a->client_name }}</strong><br>
                    <a href="mailto:{{ $a->client_email }}">{{ $a->client_email }}</a><br>
                    <a href="tel:{{ preg_replace('/\s+/', '', $a->client_phone) }}">{{ $a->client_phone }}</a>
                </p>

                @if ($a->message)
                    <p class="request-note">{{ $a->message }}</p>
                @endif

                <p class="request-meta mt-2">Reçue {{ $a->created_at->diffForHumans() }}</p>
            </div>

            <div class="col-lg-4 d-flex flex-lg-column gap-2">
                <form method="POST" action="{{ route('admin.appointments.confirm', $a) }}" class="flex-fill">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-ok w-100">Confirmer</button>
                </form>
                <form method="POST" action="{{ route('admin.appointments.refuse', $a) }}" class="flex-fill">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-line-danger w-100">Refuser</button>
                </form>
            </div>
        </div>
    </article>
@empty
    <div class="admin-card">
        <div class="empty">Aucune demande en attente.</div>
    </div>
@endforelse

{{-- ===== Confirmés à venir ===== --}}
<h2 class="admin-section-title">Confirmés à venir</h2>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th>Quand</th>
                    <th>Client</th>
                    <th>Chien et service</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($upcoming as $a)
                <tr>
                    <td><strong>{{ $a->whenLabel() }}</strong></td>
                    <td>
                        {{ $a->client_name }}
                        <span class="sub">
                            <a href="tel:{{ preg_replace('/\s+/', '', $a->client_phone) }}">{{ $a->client_phone }}</a>
                        </span>
                    </td>
                    <td>
                        {{ $a->dog_name }}
                        <span class="sub">{{ $a->service }}</span>
                    </td>
                    <td class="text-end">
                        <form method="POST" action="{{ route('admin.appointments.cancel', $a) }}"
                              onsubmit="return confirm('Annuler ce rendez-vous ? Le client recevra un email.')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-line-danger btn-sm">Annuler</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="empty">Aucun rendez-vous confirmé à venir.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ===== Historique ===== --}}
<h2 class="admin-section-title">Historique</h2>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th>Quand</th>
                    <th>Client</th>
                    <th>Chien et service</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($history as $a)
                <tr>
                    <td>{{ $a->whenLabel() }}</td>
                    <td>
                        {{ $a->client_name }}
                        <span class="sub">{{ $a->client_phone }}</span>
                    </td>
                    <td>
                        {{ $a->dog_name }}
                        <span class="sub">{{ $a->service }}</span>
                    </td>
                    <td>
                        @if ($a->status === 'refuse')
                            <span class="pill pill-muted">Refusé</span>
                        @elseif ($a->status === 'annule')
                            <span class="pill pill-danger">Annulé</span>
                        @else
                            <span class="pill pill-info">Terminé</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="empty">L'historique est vide pour le moment.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
