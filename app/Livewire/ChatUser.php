<?php

namespace App\Livewire;

use App\Models\Chat;
use App\Models\Chat_history;
use App\Models\Student;
use App\Models\Relationship;
use App\Models\Pages;
use Illuminate\Support\Facades\Auth;

use Livewire\Component;

class ChatUser extends Component
{

    public $chat;
    public $message;
    public $chatId;
    public $questions;

    protected $listeners = ['refreshChat' => 'loadChat'];

    public function mount($id)
    {
        $this->chatId = $id;
        $this->loadChat();
    }

    public function loadChat()
    {
        $this->questions = Pages::with(['chatbots'])->get();
        $this->chat = Chat::with(['user', 'history'])->where('user_id', $this->chatId)->first();
       if ($this->chat) {
            if ($this->chat->user->role_id == 4) {
                $this->chat->name = ucwords(strtolower(Student::where('user_id', $this->chat->user_id)->value('name')));
                $this->chat->profil = ucwords(strtolower(Student::where('user_id', $this->chat->user_id)->value('profil')));
            } elseif ($this->chat->user->role_id == 5) {
                $this->chat->name = ucwords(strtolower(Relationship::where('user_id', $this->chat->user_id)->value('name')));
                $this->chat->profil = null;
            }
        }
    }

    public function sendMessage()
    {
        if (!$this->message) return;

        $check = Chat::where('user_id', session('id_user'))->exists();
        
        if($check){
            $chat = Chat::where('user_id', session('id_user'))->value('id');

            Chat_history::create([
                'chat_id' => $chat,
                'user_id' => session('id_user'),
                'text' => $this->message,
            ]);
            return redirect()->back();
        }
        else{
            $chat = Chat::create([
                    'user_id' => session('id_user'),
                ]);

            Chat_history::create([
                'chat_id' => $chat->id,
                'user_id' => session('id_user'),
                'text' => $this->message,
            ]);
            return redirect()->back();
        }
        $this->message = '';

        $this->emit('refreshChat');
    }

    public function render()
    {
        return view('livewire.chat-user')->with(['chat' => $this->chat, 'questions' => $this->questions]);
    }
}
