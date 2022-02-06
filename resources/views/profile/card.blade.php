<div class="card {{$chosenProfile ==  $profile['id'] ? 'chosen-profile' : ''}}" id="profileId#{{$profile['id']}}" style="width: 25rem; margin: 20px;">
    <div class="card-header">
        @if($profile['id'] === 1)
            <div class="d-flex">
                <input type="text" class="form-control" disabled="" value="{{$profile['name'] ?? 'undefined'}}">
                <h2 style="color: black">ID:{{$profile['id'] ?? '1'}}</h2>
            </div>
        @else
            <div class="d-flex">
                <input type="text" class="form-control" value="{{$profile['name'] ?? 'undefined'}}">
                <h2 style="color: black">ID:{{$profile['id'] ?? '1'}}</h2>
            </div>
        @endif
    </div>
    <ul class="list-group list-group-flush">
        @if($profile['id'] !== 1)
            <li class="list-group-item"><a class="btn btn-success profile-btn profile-save" data-id="{{$profile['id']}}" href="{{route('editProfile')}}">Сохранить профиль</a></li>
            <li class="list-group-item"><a class="btn btn-warning profile-btn profile-load" data-id="{{$profile['id']}}" href="{{route('loadProfile')}}">Загрузить профиль</a></li>
            <li class="list-group-item"><a class="btn btn-danger profile-btn profile-delete" data-id="{{$profile['id']}}" href="{{route('deleteProfile')}}">Удалить профиль</a></li>
        @else
            <li class="list-group-item"><a class="btn btn-warning profile-btn profile-load" data-id="{{$profile['id']}}" href="{{route('loadProfile')}}">Загрузить профиль</a></li>
        @endif
    </ul>
</div>
