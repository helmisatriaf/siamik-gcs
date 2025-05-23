@extends('layouts.admin.master')
@section('content')

<div class="container">

    <form method="POST" action="{{route('actionUpdateChatBot')}}">
    @csrf
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-3">
                <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="{{url('/cc')}}">Chat</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-dorange">
                <div class="card-header">
                    <h3 class="card-title">Edit Chat Bot</h3>
                </div>
                <div class="card-body" style="max-height: 700px; overflow-y: auto;">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width:15%">Topic</th>
                                <th style="width:40%">Title</th>
                                <th style="width:45%">Answer</th>
                            </tr>
                        </thead>
                        <tbody id="scheduleTableBody">
                            <tr>
                                <td>
                                    <select name="page_id" class="form-control" id="page_id">
                                        @foreach ($topics as $topic)
                                            <option value="{{$topic->id}}" {{$topic->id == $data->page_id ? 'selected' : ''}}>{{$topic->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <textarea name="title" id="froala-editor" cols="45" rows="5">{!! $data->title !!}</textarea>
                                </td>
                                <td>
                                    <textarea name="answer" id="froala-editor" cols="45" rows="5">{!! $data->description !!}</textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="number" name="chat_bot_id" class="d-none" value="{{$data->id}}">
                </div>
                <input role="button" type="submit" class="btn btn-success mx-3 mb-2">
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    new FroalaEditor('textarea#froala-editor', {
        toolbarButtons: [],
        quickInsertEnabled: false, // Menonaktifkan quick insert
        toolbarInline: false, // Pastikan toolbar tetap ada
        pastePlain: true, // Mencegah pemformatan saat paste
        pluginsEnabled: [] // Menonaktifkan semua plugin tambahan
    });
</script>

@endsection