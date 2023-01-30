<div>
    @if ($selectedConversation)
        <form wire:submit.prevent = "sendMessage" action="">
        <div class="chat_box_footer">
            <div class="custom_form_group">
                <input type="text" wire:model='body' class="control" placeholder="Wright Message" />
                <button type="submit" class="submit btn btn-success"> Send </button>
            </div>
        </div>
    </form>
    @endif

</div>
