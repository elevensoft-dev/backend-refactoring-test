@foreach($users as $user)
    @include('components.cardUser', ['user' => $user])
@endforeach
