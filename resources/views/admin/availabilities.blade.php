@extends('layouts.admin')

@section('title', 'Créneaux')

@section('content')

<div class="admin-head">
    <h1>Créneaux</h1>
    <p>Pour les promenades et les visites à domicile : ajoutez les créneaux horaires que vos clients peuvent réserver. Les gardes d'un ou plusieurs jours se gèrent dans « Gardes ».</p>
</div>

{{-- ===== Ajouter un créneau ===== --}}
<div class="admin-card admin-card-pad mb-4">
    <h2 class="admin-card-title">Ajouter un créneau</h2>

    <form method="POST" action="{{ route('admin.availabilities.store') }}" class="row g-3 align-items-start">
        @csrf
        <div class="col-md-3">
            <label class="form-label" for="date">Date</label>
            <input type="date" name="date" id="date" min="{{ today()->toDateString() }}" required
                   class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}">
            @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3">
            <label class="form-label" for="start_time">Heure de début</label>
            <input type="time" name="start_time" id="start_time" required
                   class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}">
            @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3">
            <label class="form-label" for="end_time">Heure de fin</label>
            <input type="time" name="end_time" id="end_time" required
                   class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}">
            @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3 d-flex flex-column">
            <span class="form-label d-none d-md-block">&nbsp;</span>
            <button type="submit" class="btn btn-cobalt">Ajouter le créneau</button>
        </div>
    </form>
</div>

{{-- ===== Liste des créneaux ===== --}}
<div class="admin-card">
    <div class="table-responsive">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Horaire</th>
                    <th>État</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($availabilities as $slot)
                @php $rdv = $slot->activeAppointment; @endphp
                <tr>
                    <td><strong>{{ ucfirst($slot->date->translatedFormat('l j F Y')) }}</strong></td>
                    <td>{{ substr($slot->start_time, 0, 5) }} à {{ substr($slot->end_time, 0, 5) }}</td>
                    <td>
                        @if ($rdv && $rdv->status === 'confirme')
                            <span class="pill pill-success">Réservé</span>
                        @elseif ($rdv)
                            <span class="pill pill-warning">Demande en attente</span>
                        @elseif ($slot->is_open)
                            <span class="pill pill-info">Ouvert aux clients</span>
                        @else
                            <span class="pill pill-muted">Fermé</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @if ($rdv)
                            {{-- Créneau pris : on ne le modifie pas, on renvoie vers la demande --}}
                            <a href="{{ route('admin.appointments.index') }}" class="btn btn-line btn-sm">Voir la demande</a>
                        @else
                            <div class="d-flex gap-2 justify-content-end">
                                <form method="POST" action="{{ route('admin.availabilities.toggle', $slot) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-line btn-sm">
                                        {{ $slot->is_open ? 'Fermer' : 'Ouvrir' }}
                                    </button>
                                </form>

                                @if ($slot->appointments_count === 0)
                                    <form method="POST" action="{{ route('admin.availabilities.destroy', $slot) }}"
                                          onsubmit="return confirm('Supprimer ce créneau ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-line-danger btn-sm">Supprimer</button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="empty">Aucun créneau à venir. Ajoutez-en un avec le formulaire ci-dessus.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
