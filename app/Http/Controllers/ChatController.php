<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Rules\IsStreetFromChats;
use App\Telegram\Webhook\Webhook;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::all();
        return view('chat.index', ['chats' => $chats]);
    }

    public function edit($id)
    {
        $availableChats = Webhook::CHATS;
        $chat = Chat::query()->where('id', $id)->first();
        return view('chat.edit', [
            'chat' => $chat,
            'availableChats' => $availableChats
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'chat_to_parse' => ['required'],
            'street' => ['required', 'string', new IsStreetFromChats],
        ]);

        $chat = Chat::find($id);

        $newData = [
            'chat_to_parse' => $data['chat_to_parse'],
            'street' => $data['street'],
            'confirmed' => $request->has('confirmed') ? 1 : 0,
        ];
        

        $chat->update($newData);
        $chat->save();
        return redirect()->route('chatIndex');
    }
}
