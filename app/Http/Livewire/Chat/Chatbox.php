<?php

namespace App\Http\Livewire\Chat;

use App\Events\MessageRead;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Livewire\Component;

class Chatbox extends Component
{
    public $selectedConversation;
    public $receiverInstance;
    public $messagesCount;
    public $messages;
    public $paginateVar=10;
    public $height;

    //protected $listeners = ['echo:chat,MessageSent' => 'broadCastedMessageReceived','loadConversation','pushMessage','loadmore','updateHeight'];



    public function getListeners()
    {
        $auth_id = auth()->user()->id;

        return [
            "echo-private:chat.{$auth_id},MessageSent" => 'broadCastedMessageReceived',
            "echo-private:chat.{$auth_id},MessageRead" => 'broadCastedMessageRead',
            'loadmore','loadConversation','pushMessage','updateHeight','broadCastMessageRead','resetComponent',
        ];
    }

    public function resetComponent()
    {
        $this->selectedConversation = null;
        $this->receiverInstance = null;
    }


    public function broadCastedMessageRead($event)
    {
        if($this->selectedConversation) {

            if((int) $this->selectedConversation->id === (int) $event['conversation_id']) {

                $this->dispatchBrowserEvent('markMessageAsRead');
            }
        }
    }

    public function broadCastedMessageReceived($event)
    {
        $this->dispatchBrowserEvent('notificationSound');

        $this->emitTo('chat.chat-list','refresh');

        $broadCastedMessage = Message::find($event['message_id']);

        if($this->selectedConversation) {


            if((int) $this->selectedConversation->id === (int) $event['conversation_id']) {

                $broadCastedMessage->read = 1;
                $broadCastedMessage->save();
                $this->pushMessage($broadCastedMessage->id);

                $this->emitSelf('broadCastMessageRead');
            }
        }
    }

    public function broadCastMessageRead()
    {
        broadcast(new MessageRead($this->selectedConversation->id,$this->receiverInstance->id));

    }

    public function pushMessage($messsageID)
    {
        $newMessage = Message::find($messsageID);
        $this->messages->push($newMessage);

        $this->dispatchBrowserEvent('rowChatToBottom');



    }

    public function loadmore()
    {
        $this->paginateVar = $this->paginateVar+10;

        $this->messagesCount = Message::where('conversation_id',$this->selectedConversation->id)->count();

        $this->messages = Message::where('conversation_id',$this->selectedConversation->id)
                        ->skip($this->messagesCount-$this->paginateVar)
                        ->take($this->paginateVar)
                        ->get();

        $height = $this->height;
        $this->dispatchBrowserEvent('updateHeight',($height));

    }

    public function updateHeight($height)
    {
       $this->height = $height;

    }

    public function loadConversation(Conversation $conversation,User $receiver)
    {
        $this->selectedConversation = $conversation;
        $this->receiverInstance = $receiver;

        $this->messagesCount = Message::where('conversation_id',$this->selectedConversation->id)->count();

        $this->messages = Message::where('conversation_id',$this->selectedConversation->id)
                        ->skip($this->messagesCount-$this->paginateVar)
                        ->take($this->paginateVar)
                        ->get();

        $this->dispatchBrowserEvent('chatSelected');

        Message::where('conversation_id',$this->selectedConversation->id)
                ->where('receiver_id',auth()->user()->id)
                ->update(['read' => 1]);

        $this->emitSelf('broadCastMessageRead');

    }
    public function render()
    {
        return view('livewire.chat.chatbox');
    }
}
