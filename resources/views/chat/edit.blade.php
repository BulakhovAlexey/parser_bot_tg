@extends('layouts.main')

@section('content')
    <h1>Редактировать чат пользователя - {{ $chat->recipient }} (id - {{ $chat->id }})</h1>
    <form
        method="post"
        action="{{ route('chatUpdate', $chat->id) }}"
        class="flex flex-col gap-6 p-4">
        @csrf
        @method('PUT')
        <div class="form__group">
            <label for="chats" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Чат</label>
            <select name="chat_to_parse" id="chats"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                @foreach($availableChats as $availableChat)
                    <option
                        {{ $availableChat === $chat->chat_to_parse ? 'selected' : '' }} value="{{ "@" . $availableChat }}">{{ $availableChat }}</option>
                @endforeach
                <option value="@AlexeyBulakhov">AlexeyBulakhov(тест)</option>
            </select>
        </div>

        <div class="form__group">
            <span>Улица</span>
            <input name="street" class="login @error('street') hasError @enderror" type="text"
                   value="{{ $chat->street }}"
                   placeholder="Улица">
            @error('street')
            <span class="input-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form__group">
            <div class="flex items-center">
                <input name="confirmed" {{ $chat->confirmed ? 'checked' : '' }} id="checked-checkbox" type="checkbox"
                       value="1"
                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Подписка
                    подтверждена</label>
            </div>
        </div>
        <div class="form__buttons flex justify-center">
            <button type="submit" class="login">Обновить</button>
        </div>
    </form>
@endsection

