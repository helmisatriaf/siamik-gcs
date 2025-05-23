@extends('layouts.admin.master')
@section('content')

<livewire:chat-detail :id="session('id_user')"/>
@endsection