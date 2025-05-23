<div>
    <div class="card card-light direct-chat direct-chat-light">
        <div class="card-header">
            <h3 class="card-title text-bold">Chat BOT</h3>
        </div>
        <div class="card-body">
            <div class="direct-chat-messages" style="max-height: 55vh; min-height: 55vh;">
                <div class="direct-chat-msg">
                    <div class="direct-chat-infos clearfix">
                        <span class="direct-chat-name float-left">Greta</span>
                    </div>
                    <img class="direct-chat-img" src="{{asset('images/greta-face.png')}}">
                    <div class="direct-chat-text">
                        Hello! How can we help you today? <br>
                        Click on the topic below that is your problem
                    </div>
                    
                    <!-- Topic List -->
                    <div class="direct-chat-text" id="topic-chat">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
                            @foreach ($questions as $question)
                                <li class="nav-item border-bottom border-light p-0">
                                    <a href="#" class="nav-link topic-item" data-id="{{$question->id}}">
                                        <p>{{$question->name}}</p>
                                    </a>
                                </li>
                                @endforeach
                                <li class="nav-item border-bottom border-light p-0">
                                    <a href="#" class="nav-link topic-item" data-id="other">
                                        <p>Other Question</p>
                                    </a>
                                </li>
                        </ul>
                    </div>
                    
                    <!-- Sub-Topic List (Hidden by Default) -->
                    <div class="direct-chat-text" id="sub-topic-chat" style="display: none;">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
                        </ul>
                    </div>
                    
                    <!-- Answer Description (Hidden by Default) -->
                    <div class="direct-chat-text" id="answer-sub-chat" style="display: none;"></div>
                </div>
    
                <div class="direct-chat-msg other-msg" id="other" style="display: none;">
                    <div class="direct-chat-infos clearfix">
                        <span class="direct-chat-name float-left">Greta</span>
                    </div>
                    <img class="direct-chat-img" src="{{asset('images/greta-face.png')}}">
                    <div class="direct-chat-text">
                        <div class="card bg-light d-flex flex-fill">
                            <div class="card-body p-2">
                            <div class="row">
                                <div class="col-7">
                                <h2 class="lead">Admin Great Crystal School</h2>
                                {{-- <p class="text-muted"><b>About: </b> Web Designer / UX / Graphic Artist / Coffee Lover </p> --}}
                                <ul class="ml-4 mb-3 fa-ul text-muted">
                                    <li class=""><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Address: Jl. Darmo Permai</li>
                                </ul>
                                <a class="btn btn-sm btn-success" href="/chat-admin" target="_blank">
                                    <i class="fas fa-brands fa-whatsapp"></i> Chat Admin   
                                </a>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    </div>
    
    @livewire('chat-message-box', ['chatId' => $chat->id], key($chat->id))
    
    <script>
        $(document).ready(function () {
            // Handle Topic Click
            $('.topic-item').click(function (e) {
                e.preventDefault();
                let topicId = $(this).data('id');
                
                if (topicId == 'other')
                {
                    $('#sub-topic-chat').hide(); // Show sub-topic section
                    $('#answer-sub-chat').hide(); // Hide answer section   
                    $('#other').show(); // Hide answer section   
                    
                }
                else
                {
                    $.ajax({
                        url: '/get-subtopics/' + topicId, // Backend endpoint
                        method: 'GET',
                        success: function (response) {
                            let subTopicHtml = '';
                            response.subtopics.forEach(sub => {
                                subTopicHtml += `<li class="nav-item border-bottom border-light p-0">
                                    <a href="#" class="nav-link sub-topic-item" data-id="${sub.id}">
                                        <p>${sub.title}</p>
                                    </a>
                                </li>`;
                            });
                            
                            $('#sub-topic-chat ul').html(subTopicHtml);
                            $('#sub-topic-chat').show(); // Show sub-topic section
                            $('#answer-sub-chat').hide(); // Hide answer section
                            $('#other').hide();
                        }
                    });
                }
            });
            
            // Handle Sub-Topic Click
            $(document).on('click', '.sub-topic-item', function (e) {
                e.preventDefault();
                let subTopicId = $(this).data('id');
                
                // Fetch description dynamically
                $.ajax({
                    url: '/get-answer/' + subTopicId, // Backend endpoint
                    method: 'GET',
                    success: function (response) {
                        $('#answer-sub-chat').html(response.description);
                        $('#answer-sub-chat').show();
                        $('#other').hide();
                    }
                });
            });
        });
    </script>
</div>
