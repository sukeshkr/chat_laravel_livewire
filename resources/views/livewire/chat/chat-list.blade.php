<div>
    <div class="chat_list_header">
        <div class="title">
            Chat
        </div>
        <div class="img_container">
            <img src="https://ui-avatars.com/api/?background=0D8ABC&color=fff&name={{auth()->user()->name}}" alt="">
        </div>
    </div>
    <div class="chat_list_body">

        @if (count($conversations) > 0)

        @foreach ($conversations as $conversation)

        <div class="chat_list_item" wire:key='{{$conversation->id}}' wire:click="$emit('chatUserSelected',{{$conversation}},{{$this->getChatUserInstanse($conversation,$name='id')}})">
            <div class="chat_list_img_container">
                <img src="https://ui-avatars.com/api/?name={{$this->getChatUserInstanse($conversation,$name='name')}}" alt="">
            </div>
            <div class="chat_list_info">
                <div class="top_row">
                    <div class="list_username">{{$this->getChatUserInstanse($conversation,$name='name')}}</div>
                    <span class="date">{{$conversation->messages->last()?->created_at->shortAbsoluteDiffForHumans()}}</span>
                </div>
                <div class="bottom_row">
                    <div class="message_body text-truncate">
                        @if ($conversation->messages->last())

                            {{$conversation->messages->last()->message}}

                        @endif
                    </div>

                    @php
                        if(count($conversation->messages->where('read',0)->where('receiver_id',auth()->user()->id))) {
                            echo '<div class="unread_count badge rounded-pill text-light bg-success">
                                '.count($conversation->messages->where('read',0)->where('receiver_id',auth()->user()->id)).'</div> ';
                        }
                    @endphp

                </div>
            </div>
        </div>

        @endforeach

        @else
        <hr><br><h2>Lets Start the conversations</h2>
        @endif
    </div>

</div>
