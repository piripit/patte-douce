@extends('layouts.admin')

@section('title', 'Gardes')

@section('content')

<div class="admin-head">
    <h1>Gardes</h1>
    <p>Ouvrez les jours où vous pouvez accueillir des chiens, pour une journée, un week-end ou plusieurs semaines. Vos clients choisissent leurs dates d'arrivée et de départ parmi ces jours.</p>
</div>

<div class="row g-4 mb-4">

    {{-- ===== Ouvrir ===== --}}
    <div class="col-lg-7">
        <div class="admin-card admin-card-pad h-100">
            <h2 class="admin-card-title">Ouvrir des jours</h2>
            <form method="POST" action="{{ route('admin.boarding.open') }}" class="row g-3">
                @csrf
                <div class="col-sm-6">
                    <label class="form-label" for="open_from">Du</label>
                    <input type="date" name="open_from" id="open_from" min="{{ today()->toDateString() }}" required
                           class="form-control @error('open_from') is-invalid @enderror" value="{{ old('open_from') }}">
                    @error('open_from') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="open_to">Au (inclus)</label>
                    <input type="date" name="open_to" id="open_to" min="{{ today()->toDateString() }}" required
                           class="form-control @error('open_to') is-invalid @enderror" value="{{ old('open_to') }}">
                    @error('open_to') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="capacity">Chiens accueillis en même temps</label>
                    <input type="number" name="capacity" id="capacity" min="1" max="10" required
                           class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity', 1) }}">
                    @error('capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-sm-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-cobalt w-100">Ouvrir ces jours</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== Fermer ===== --}}
    <div class="col-lg-5">
        <div class="admin-card admin-card-pad h-100">
            <h2 class="admin-card-title">Fermer des jours</h2>
            <form method="POST" action="{{ route('admin.boarding.close') }}" class="row g-3">
                @csrf
                <div class="col-sm-6">
                    <label class="form-label" for="close_from">Du</label>
                    <input type="date" name="close_from" id="close_from" min="{{ today()->toDateString() }}" required
                           class="form-control @error('close_from') is-invalid @enderror" value="{{ old('close_from') }}">
                    @error('close_from') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="close_to">Au (inclus)</label>
                    <input type="date" name="close_to" id="close_to" min="{{ today()->toDateString() }}" required
                           class="form-control @error('close_to') is-invalid @enderror" value="{{ old('close_to') }}">
                    @error('close_to') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-line w-100">Fermer ces jours</button>
                </div>
            </form>
            <p class="text-muted small mt-3 mb-0">Impossible de fermer un jour où un séjour est déjà prévu.</p>
        </div>
    </div>
</div>

{{-- ===== Légende ===== --}}
<div class="cal-legend mb-3">
    <span><i class="cal-swatch cal-open"></i>Ouvert, libre</span>
    <span><i class="cal-swatch cal-partial"></i>Ouvert, en partie réservé</span>
    <span><i class="cal-swatch cal-full"></i>Complet</span>
    <span><i class="cal-swatch cal-closed"></i>Fermé</span>
</div>

{{-- ===== Calendrier ===== --}}
<div class="row g-3">
@foreach ($months as $month)
    <div class="col-xl-4 col-md-6">
        <div class="admin-card admin-card-pad h-100">
            <h2 class="admin-card-title">{{ $month['label'] }}</h2>
            <div class="cal">
                @foreach (['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $weekday)
                    <div class="cal-head">{{ $weekday }}</div>
                @endforeach

                @for ($i = 0; $i < $month['offset']; $i++)
                    <div></div>
                @endfor

                @foreach ($month['cells'] as $cell)
                    <div class="cal-day cal-{{ $cell['state'] }}" title="{{ $cell['title'] }}">
                        <span class="cal-num">{{ $cell['day'] }}</span>
                        @if (in_array($cell['state'], ['open', 'partial', 'full'], true))
                            <span class="cal-occ">{{ $cell['booked'] }}/{{ $cell['capacity'] }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endforeach
</div>
<p class="text-muted small mt-3">Le chiffre sous la date indique les chiens prévus sur le nombre de places (par exemple 1/2).</p>

@endsection
