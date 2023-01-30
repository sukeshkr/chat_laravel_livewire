<?php

namespace App\Http\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Livewire\Component;

class CreateChat extends Component
{
    public $users;
    public $message = 'helo how aru you';

    public function checkConversation($receiverID)
    {
        $checkedConversation = Conversation::where('receiver_id',auth()->user()->id)
                                ->where('sender_id',$receiverID)
                                ->orWhere('receiver_id',$receiverID)
                                ->where('sender_id',auth()->user()->id)
                                ->get();
        if(count($checkedConversation)==0) {

            $createdConversation = Conversation::create([
                'receiver_id' => $receiverID,
                'sender_id' =>  auth()->user()->id,
            ]);

            $createdMessage = Message::create([
                'conversation_id' => $createdConversation->id,
                'sender_id' =>  auth()->user()->id,
                'receiver_id' =>  $receiverID,
                'message' => $this->message,
            ]);

            $createdConversation->last_time_message = $createdMessage->created_at;
            $createdConversation->save();

            dd('saved');
        }
        elseif(count($checkedConversation)>=1) {
            dd('conversation exist');

        }

    }

    public function render()
    {
        $this->users = User::where('id','!=',auth()->user()->id)->get();
        return view('livewire.chat.create-chat');
    }
}
