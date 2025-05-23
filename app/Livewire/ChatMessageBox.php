<?php

namespace App\Livewire;
use Livewire\Component;
use App\Models\Chat;

class ChatMessageBox extends Component
{
    public $chatId;
    public $chat;
    public $message;

    public function mount($chatId)
    {
        $this->chatId = $chatId;
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
        $this->chat = Chat::with(['history', 'user'])->find($this->chatId);
        return view('livewire.chat-message-box');
    }
}
