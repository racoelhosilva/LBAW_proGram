@forelse ($user->following as $following)
    <p>{{$following->name}}</p>
@empty
    <p>This user does not follow any other user</p>
@endforelse