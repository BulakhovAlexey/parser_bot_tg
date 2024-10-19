Вот список каналов:

@foreach($chats as $chat => $description)
    <b>{{ '@' . $chat }}</b> | {{$description}}
    =================================================
@endforeach

