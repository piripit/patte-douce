@extends('layouts.app')

@section('title', 'Prise de rendez-vous')
@section('description', 'Choisissez un service, des dates ou un créneau, et envoyez votre demande de réservation en ligne.')

@section('content')

<section class="page-head">
    <div class="container">
        <h1>Prise de rendez-vous</h1>
        <p>Promenade, visite ou garde de quelques heures à plusieurs semaines : choisissez le service, puis vos dates.</p>
    </div>
</section>

<section class="container pb-5">
    <div class="row g-4">
        <div class="col-lg-7">

            @if (session('booking_sent'))

                <div class="panel thanks">
                    <div class="thanks-icon" aria-hidden="true">✓</div>
                    <h2>Demande envoyée</h2>
                    <p>
                        Merci, votre demande est enregistrée. Je la valide dès que possible et je vous réponds par email ou par téléphone.
                        Elle n'est confirmée qu'après ma réponse.
                        @if (session('booking_mail'))
                            Un email de réception vient de vous être envoyé.
                        @endif
                    </p>
                    <a href="{{ route('accueil') }}" class="btn btn-line">Retour à l'accueil</a>
                </div>

            @elseif ($slotsByDay->isEmpty() && count($stayRanges) === 0)

                <div class="panel">
                    <h2 class="h3">Aucune disponibilité n'est ouverte pour le moment.</h2>
                    <p class="text-muted">Mon planning est mis à jour régulièrement. Vous pouvez aussi me contacter pour convenir d'une date.</p>
                    <a href="{{ route('contact') }}" class="btn btn-cobalt">Me contacter</a>
                </div>

            @else

                <form class="panel" method="POST" action="{{ route('rendez-vous.store') }}" id="booking-form">
                    @csrf

                    {{-- 1. Le service --}}
                    <div class="mb-3">
                        <label class="form-label" for="service">Service</label>
                        <select name="service" id="service"
                                class="form-select @error('service') is-invalid @enderror" required>
                            <optgroup label="Sur un créneau horaire">
                                @foreach ($services->where('type', 'slot') as $service)
                                    <option value="{{ $service['name'] }}" data-type="slot"
                                            @selected(old('service', request('service')) === $service['name'])>{{ $service['name'] }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Sur un ou plusieurs jours">
                                @foreach ($services->where('type', 'stay') as $service)
                                    <option value="{{ $service['name'] }}" data-type="stay"
                                            @selected(old('service', request('service')) === $service['name'])>{{ $service['name'] }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                        @error('service') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- 2a. Créneau horaire (promenade, visite) --}}
                    <div class="mb-3" id="box-slot" data-empty="{{ $slotsByDay->isEmpty() ? '1' : '0' }}">
                        @if ($slotsByDay->isEmpty())
                            <div class="alert alert-light border mb-0">
                                Aucun créneau horaire n'est ouvert pour ce service pour le moment.
                                <a href="{{ route('contact') }}">Contactez-moi</a> pour convenir d'une date.
                            </div>
                        @else
                            <label class="form-label" for="availability_id">Créneau</label>
                            <select name="availability_id" id="availability_id"
                                    class="form-select @error('availability_id') is-invalid @enderror" required>
                                <option value="">Choisir un créneau…</option>
                                @foreach ($slotsByDay as $day => $slots)
                                    <optgroup label="{{ ucfirst($slots->first()->date->translatedFormat('l j F Y')) }}">
                                        @foreach ($slots as $slot)
                                            <option value="{{ $slot->id }}" @selected(old('availability_id') == $slot->id)>
                                                de {{ substr($slot->start_time, 0, 5) }} à {{ substr($slot->end_time, 0, 5) }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('availability_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        @endif
                    </div>

                    {{-- 2b. Dates d'un séjour (garde) --}}
                    <div class="mb-3" id="box-stay" data-empty="{{ count($stayRanges) === 0 ? '1' : '0' }}">
                        @if (count($stayRanges) === 0)
                            <div class="alert alert-light border mb-0">
                                Aucune date de garde n'est ouverte pour le moment.
                                <a href="{{ route('contact') }}">Contactez-moi</a> pour convenir d'un séjour.
                            </div>
                        @else
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label" for="start_date">Arrivée</label>
                                    <input type="date" name="start_date" id="start_date"
                                           min="{{ today()->addDay()->toDateString() }}"
                                           class="form-control @error('start_date') is-invalid @enderror"
                                           value="{{ old('start_date') }}" required>
                                    @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label" for="end_date">Départ</label>
                                    <input type="date" name="end_date" id="end_date"
                                           min="{{ today()->addDay()->toDateString() }}"
                                           class="form-control @error('end_date') is-invalid @enderror"
                                           value="{{ old('end_date') }}" required>
                                    @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <p class="duration" id="stay-duration" aria-live="polite"></p>

                            <p class="mb-1 fw-bold">Dates ouvertes actuellement</p>
                            <p class="text-muted small mb-0">
                                Pour une seule journée, indiquez la même date à l'arrivée et au départ
                                (séjour de {{ $maxStayDays }} jours maximum en ligne).
                                Cliquez sur une période pour la reprendre.
                            </p>
                            <ul class="stay-ranges">
                                @foreach ($stayRanges as $range)
                                    <li>
                                        <button type="button" class="range-pick"
                                                data-from="{{ $range['from']->toDateString() }}"
                                                data-to="{{ $range['to']->toDateString() }}">{{ $range['label'] }}</button>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    {{-- 3. Le chien et le client --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="dog_name">Nom du chien</label>
                            <input type="text" name="dog_name" id="dog_name"
                                   class="form-control @error('dog_name') is-invalid @enderror"
                                   value="{{ old('dog_name') }}" maxlength="100" required>
                            @error('dog_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="client_name">Votre nom</label>
                            <input type="text" name="client_name" id="client_name" autocomplete="name"
                                   class="form-control @error('client_name') is-invalid @enderror"
                                   value="{{ old('client_name') }}" maxlength="100" required>
                            @error('client_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="client_email">Email</label>
                            <input type="email" name="client_email" id="client_email" autocomplete="email"
                                   class="form-control @error('client_email') is-invalid @enderror"
                                   value="{{ old('client_email') }}" maxlength="150" required>
                            <div class="form-text">Vous y recevrez ma réponse.</div>
                            @error('client_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="client_phone">Téléphone</label>
                            <input type="tel" name="client_phone" id="client_phone" autocomplete="tel"
                                   class="form-control @error('client_phone') is-invalid @enderror"
                                   value="{{ old('client_phone') }}" maxlength="20" required>
                            @error('client_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="message">Un détail utile ? (facultatif)</label>
                        <textarea name="message" id="message" rows="3" maxlength="1000"
                                  class="form-control @error('message') is-invalid @enderror"
                                  placeholder="Âge, caractère, traitement, habitudes…">{{ old('message') }}</textarea>
                        @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Champ piège : invisible, les robots le remplissent --}}
                    <div class="hp" aria-hidden="true">
                        <label for="champ_piege">Ne pas remplir</label>
                        <input type="text" name="champ_piege" id="champ_piege" tabindex="-1" autocomplete="off">
                    </div>

                    <button type="submit" class="btn btn-cobalt btn-lg" id="booking-submit">Envoyer la demande</button>

                    <p class="text-muted small mt-3 mb-0">
                        Ces informations servent uniquement à traiter votre demande.
                        <a href="{{ route('mentions-legales') }}">En savoir plus</a>
                    </p>
                </form>

                <script>
                    // Affiche le bon bloc selon le service : créneau horaire ou dates de séjour.
                    // Sans JavaScript, les deux blocs restent visibles et le serveur vérifie le bon.
                    (function () {
                        var select = document.getElementById('service');
                        var boxSlot = document.getElementById('box-slot');
                        var boxStay = document.getElementById('box-stay');
                        var submit = document.getElementById('booking-submit');
                        var start = document.getElementById('start_date');
                        var end = document.getElementById('end_date');
                        var duration = document.getElementById('stay-duration');

                        function setActive(box, active) {
                            box.hidden = !active;
                            var fields = box.querySelectorAll('input, select');
                            for (var i = 0; i < fields.length; i++) { fields[i].disabled = !active; }
                        }

                        function sync() {
                            var type = select.options[select.selectedIndex].getAttribute('data-type');
                            var stay = type === 'stay';
                            setActive(boxSlot, !stay);
                            setActive(boxStay, stay);
                            var current = stay ? boxStay : boxSlot;
                            submit.disabled = current.getAttribute('data-empty') === '1';
                        }

                        function showDuration() {
                            if (!start || !end || !duration) { return; }
                            if (start.value) { end.min = start.value; }
                            var a = Date.parse(start.value), b = Date.parse(end.value);
                            if (isNaN(a) || isNaN(b) || b < a) { duration.textContent = ''; return; }
                            var days = Math.round((b - a) / 86400000) + 1;
                            duration.textContent = 'Durée : ' + days + (days > 1 ? ' jours' : ' jour');
                        }

                        select.addEventListener('change', sync);
                        if (start && end) {
                            start.addEventListener('change', showDuration);
                            end.addEventListener('change', showDuration);
                            var picks = document.querySelectorAll('.range-pick');
                            for (var i = 0; i < picks.length; i++) {
                                picks[i].addEventListener('click', function () {
                                    start.value = this.getAttribute('data-from');
                                    end.value = this.getAttribute('data-to');
                                    showDuration();
                                });
                            }
                        }
                        sync();
                        showDuration();
                    })();
                </script>

            @endif
        </div>

        <div class="col-lg-5">
            <div class="panel-note">
                <h2>Ensuite</h2>
                <ol>
                    <li>Je reçois votre demande et je vérifie mon planning.</li>
                    <li>Je vous réponds par email ou par téléphone pour confirmer ou proposer d'autres dates.</li>
                    <li>Le rendez-vous est confirmé une fois ma réponse reçue.</li>
                </ol>
                <p class="mt-4 mb-1 fw-bold">Une question avant de réserver ?</p>
                <p class="mb-0">
                    <a href="tel:{{ preg_replace('/\s+/', '', config('patte.phone')) }}">{{ config('patte.phone') }}</a><br>
                    <a href="mailto:{{ config('patte.email') }}">{{ config('patte.email') }}</a>
                </p>
            </div>
        </div>
    </div>
</section>

@endsection
