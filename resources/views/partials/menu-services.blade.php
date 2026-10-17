<ul class="menu">
    @foreach (config('patte.services') as $service)
        <li class="menu-item">
            <div>
                <h3 class="menu-name"><span aria-hidden="true">{{ $service['icon'] }}</span> {{ $service['name'] }}</h3>
                <p class="menu-text">{{ $service['text'] }}</p>
            </div>
            <div class="menu-side">
                <span class="menu-price">{{ $service['price'] }}</span>
                <span class="menu-per">{{ $service['per'] }}</span>
                <a class="menu-link" href="{{ route('rendez-vous', ['service' => $service['name']]) }}">Réserver ce service</a>
            </div>
        </li>
    @endforeach
</ul>
