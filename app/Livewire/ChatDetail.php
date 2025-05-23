<?php

namespace App\Livewire;
use Livewire\Component;
use App\Models\Chat;
use App\Models\Student;
use App\Models\Relationship;
use App\Models\Pages;
use Illuminate\Support\Facades\Auth;

class ChatDetail extends Component
{
    public $chat;
    public $message;
    public $questions;

    protected $listeners = ['refreshChat' => '$refresh'];

    public function mount($id)
    {
        $this->loadChat($id);
    }

    public function loadChat($id)
    {
        $this->questions = Pages::with(['chatbots'])->get();
        $this->chat = Chat::with(['user', 'history'])->where('user_id', $id)->first();

        if($this->chat == null){
            
        }else{
            if ($this->chat->user->role_id == 4) {
                $this->chat->name = ucwords(strtolower(Student::where('user_id', $this->chat->user_id)->value('name')));
                $this->chat->profil = ucwords(strtolower(Student::where('user_id', $this->chat->user_id)->value('profil')));
            } elseif ($this->chat->user->role_id == 5) {
                $this->chat->name = ucwords(strtolower(Relationship::where('user_id', $this->chat->user_id)->value('name')));
                $this->chat->profil = NULL;
            }
        }

    }

    public function sendMessage()
    {
        if ($this->message) {
            $this->chat->history()->create([
                'user_id' => auth()->id(),
                'text' => $this->message,
            ]);
    
            $this->message = '';
    
            // Dispatch event untuk memperbarui chat secara live
            $this->dispatch('refreshChat');
        }
    }

    public function render()
    {
        return view('livewire.chat-detail');
    }
}
