<div class="d-flex">
<div class="card" style="width: 18rem; margin: 20px;">
    <div class="card-header">
        Введите имя профиля
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item"><input type="text" id="profileName" class="form-control"></li>
        <li class="list-group-item"><a class="btn btn-success profile-btn profile-add" href="{{route('addProfile')}}">Добавить профиль</a></li>
        <li class="list-group-item"><span>В профиль будут добавлены пары открытые в этой вкладке</span></li>
    </ul>
</div>

<div id="carouselExampleControls" class="carousel slide profiles" data-ride="carousel" data-interval="false">
    <div class="carousel-inner">

        @foreach(array_chunk($profiles, 3) as $key => $profile_chunk)

            @if($key === 0)
                <div class="carousel-item active">
                    <div class="d-flex">
                    @foreach($profile_chunk as $chunk_key => $profile)

                        @include('profile.card', ['profile' => $profile])

                    @endforeach
                    </div>
                </div>
            @else
                <div class="carousel-item">
                    <div class="d-flex">
                    @foreach($profile_chunk as $chunk_key => $profile)

                        @include('profile.card', ['profile' => $profile])

                    @endforeach
                    </div>
                </div>
            @endif

        @endforeach
    </div>
    <a class="carousel-control-prev carousel-control" href="#carouselExampleControls" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next carousel-control" href="#carouselExampleControls" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
</div>
