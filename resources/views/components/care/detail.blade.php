@extends('layouts.admin.master')
@section('content')


<div class="row">
  <div class="col">
    <nav aria-label="breadcrumb" class="bg-white rounded-3 p-3 mb-4">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item"><a href="{{ url('/cc') }}">Chat</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail</li>
      </ol>
    </nav>
  </div>
</div>

<livewire:chat-detail :id="$chat->user_id" />
@endsection

