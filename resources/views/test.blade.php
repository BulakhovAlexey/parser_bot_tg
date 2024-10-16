@extends('layouts.main')

@section('content')
    <h1 class="text-2xl py-3 text-left"> Chats </h1>
    <div class="table w-full">
        <div class="table__row">
            <div class="table__cell">ID</div>
            <div class="table__cell">recipient</div>
            <div class="table__cell">chat_to_parse</div>
            <div class="table__cell">street</div>
            <div class="table__cell">confirmed</div>
            <div class="table__cell"></div>
        </div>
        @foreach($chats as $chat)
            <div class="table__row">
                <div class="table__cell">{{ $chat->id }}</div>
                <div class="table__cell">{{ $chat->recipient }}</div>
                <div class="table__cell">{{ $chat->chat_to_parse }}</div>
                <div class="table__cell">{{ $chat->street }}</div>
                <div class="table__cell">{{ $chat->confirmed }}</div>
                <a href="" class="table__cell p-2 border-2 uppercase">edit</a>
            </div>
        @endforeach
    </div>

@endsection

