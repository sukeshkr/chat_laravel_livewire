<?php

namespace App\Http\Livewire\Chat;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Livewire\Component;

class SendMessage extends Component
{
    public $selectedConversation;
    public $receiverInstance;
    public $body;
    public $createMessage;
    protected $listeners = ['updateSendMessage','dispatchMessageSent','resetComponent'];

    public function resetComponent()
    {
        $this->selectedConversation = null;
        $this->receiverInstance = null;
    }

    public function updateSendMessage(Conversation $conversation,User $receiver )
    {
        $this->selectedConversation = $conversation;
        $this->receiverInstance = $receiver;

    }
    public function sendMessage()
    {
        if($this->body == null) {
            return null;
        }

        $this->createMessage = Message::create([

            'conversation_id' => $this->selectedConversation->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $this->receiverInstance->id,
            'message' => $this->body,
        ]);

        $this->selectedConversation->last_time_message = $this->createMessage->created_at;
        $this->selectedConversation->save();

        $this->emitTo('chat.chatbox','pushMessage',$this->createMessage->id);
        $this->emitTo('chat.chat-list','refresh');
        $this->reset('body');
        $this->emitSelf('dispatchMessageSent');
    }
    public function dispatchMessageSent()
    {
        broadcast(new MessageSent(auth()->user(),$this->createMessage,$this->selectedConversation,$this->receiverInstance));

    }
    public function render()
    {
        return view('livewire.chat.send-message');
    }
}
