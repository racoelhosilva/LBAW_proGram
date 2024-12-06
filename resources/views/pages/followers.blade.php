@forelse ($user->followers as $follower)
    <p>{{$follower->name}}</p>
@empty
    <p>This user has no followers</p>
@endforelse