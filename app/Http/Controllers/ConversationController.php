<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversation;
use App\Events\NewMessage;

class ConversationController extends Controller
{
    public function store()
    {
        $conversation = Conversation::create([
            'message' => request('message'),
            'group_id' => request('group_id'),
            'user_id' => auth()->id(),
        ]);



        broadcast(new NewMessage($conversation->load('user')))->toOthers();

        return $conversation->load('user');
    }

    public function messages($id)
    {
        $messages = Conversation::where('group_id', $id)->with('user')->get();
        return $messages;
    }
}
