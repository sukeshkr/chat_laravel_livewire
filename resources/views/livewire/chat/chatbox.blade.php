<div>
    @if ($selectedConversation)
    <div class="chat_box_header">

        <div class="return">
            <i class="bi bi-arrow-left"></i>

        </div>


        <div class="img_container">
            <img src="https://ui-avatars.com/api/?name={{$receiverInstance->name}}" alt="">
        </div>

        <div class="name">
            {{$receiverInstance->name}}
        </div>

        <div class="info">
            <div class="info_item">
                <i class="bi bi-telephone-fill"></i>
            </div>
            <div class="info_item">
                <i class="bi bi-image"></i>
            </div>
            <div class="info_item">
                <i class="bi bi-info-circle-fill"></i>
            </div>
        </div>

    </div>

        <div class="chat_box_body">
            @foreach ($messages as $conversationMsg)
            <div wire::key='{{$conversationMsg->id}}' class="msg_body {{auth()->id() == $conversationMsg->sender_id ? 'msg_body_me' : 'msg_body_receiver'}}" style="width:80%;max-width:80%;max-width:max-content;">
                {{$conversationMsg->message}}
                <div class="msg_body_footer">
                    <div class="date">
                        {{$conversationMsg->created_at->format('m: i a')}}
                    </div>
                    <div class="read">
                        @php
                            if($conversationMsg->user->id === auth()->id()) {
                                if($conversationMsg->read==0) {
                                    echo '<i class="bi bi-check2 status_tick"></i>';
                                }
                                else{
                                    echo '<i class="bi bi-check2-all text-primary"></i>';
                                }
                            }
                        @endphp

                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <script>
        $('.chat_box_body').on('scroll',function() {
            var top = $('.chat_box_body').scrollTop();
            if(top==0) {
                window.livewire.emit('loadmore');
            }
        });
        </script>
        <script>
            window.addEventListener('updateHeight',event=> {

                let old = event.detail.height;
                let newHeight = $('.chat_box_body')[0].scrollHeight;
                let height = $('.chat_box_body').scrollTop(newHeight-old);
                window.livewire.emit('updateHeight',{
                    height:height,

                });

            });
        </script>
        @else
        <div class="fs-4 text-center text-primary mt-5">No Conversations Selected</div>
        @endif

<script>
    window.addEventListener('rowChatToBottom',event=> {

        $('.chat_box_body').scrollTop($('.chat_box_body')[0].scrollHeight);
    });
</script>
<script>
    $(document).on('click','.return', function() {

        window.livewire.emit('resetComponent');

    });
</script>

<script>
    window.addEventListener('markMessageAsRead',event=> {
        var value = document.querySelectorAll('.status_tick');
        value.array.forEach(element, index => {

            element.classList.remove('bi bi-check2');
            element.classList.add('bi bi-check2-all','text-primary');
        });
    });
</script>

<script>
    window.addEventListener('notificationSound',event=> {

        new Audio("{{asset('mp3/notification.mp3')}}").play();
    });
    </script>

</div>
