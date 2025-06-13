<div>
     <div class="card direct-chat direct-chat-light" wire:poll.5s style="background-color: #ffde9e;border-radius: 12px;">
        <h3 class="card-title pt-3 pl-4 text-bold">Direct Messages</h3>
        
        <div class="card-body">
            <div class="direct-chat-message" style="max-height: 55vh;min-height: 55vh;">
                <div class="direct-chat-message px-3">
                    @if ($chat != null)
                        @foreach ($chat->history as $history)
                        @if(session('role') == 'superadmin' || session('role') == 'admin')
                            @if ($history->user_id == auth()->id())
                                <div class="direct-chat-msg right">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-right">You</span>
                                    </div>
                                    <img class="direct-chat-img" src="{{ asset('images/greta-face.png') }}">
                                    <div class="direct-chat-text">{{ $history->text }}</div>
                                </div>
                            @else
                                <div class="direct-chat-msg">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-left">{{$chat->name}}</span>
                                    </div>
                                    @if ($chat->user->student->profil != null)
                                        <img class="direct-chat-img img-circle" src="{{ asset('storage/file/profile/'.$chat->user->student->profil) }}" alt="User Avatar" style="width: 45px; height: 45px;">
                                    @else
                                        <img class="direct-chat-img img-circle" src="{{ asset('images/admin.png') }}" alt="User Avatar" style="width: 45px; height: 45px;">
                                        {{-- <img class="direct-chat-img img-circle" src="{{ asset('storage/file/profile/'.$chat->profil) }}" alt="User Avatar" style="width: 45px; height: 45px;"> --}}
                                    @endif
                                    <div class="direct-chat-text">
                                        {{ $history->text }}
                                    </div>
                                </div>
                            @endif
                        @else
                            @if ($history->user_id == auth()->id())
                                <div class="direct-chat-msg right">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-right">{{$chat->name}}</span>
                                    </div>
                                    @if ($chat->user->student->profil != null)
                                        <img class="direct-chat-img img-circle" src="{{ asset('storage/file/profile/'.$chat->user->student->profil) }}" alt="User Avatar" style="width: 45px; height: 45px;">
                                    @else
                                        <img class="direct-chat-img img-circle" src="{{ asset('images/admin.png') }}" alt="User Avatar" style="width: 45px; height: 45px;">
                                    @endif
                                    <div class="direct-chat-text">{{ $history->text }}</div>
                                </div>
                            @else
                                <div class="direct-chat-msg">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name float-left">Greta</span>
                                    </div>
                                    <img class="direct-chat-img" src="{{ asset('images/greta-face.png') }}">
                                    <div class="direct-chat-text">
                                        {{ $history->text }}
                                    </div>
                                </div>
                            @endif
                        @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer">
            <form wire:submit.prevent="sendMessage">
                <div class="input-group">
                    <input type="text" wire:model="message" placeholder="Type Message ..." class="form-control">
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info">Send</button>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>
