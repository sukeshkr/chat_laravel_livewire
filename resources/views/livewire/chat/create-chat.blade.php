<div>


    <ul class="list-group w-75 max-auto mt-3 container-fluid">
        @foreach ($users as $user)
    <li class="list-group-item list-group-item-action" wire:click="checkConversation({{$user->id}})">{{$user->name}}</li>
        @endforeach
    </ul>


</div>
