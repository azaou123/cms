<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\MessageAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function store(Request $request, Conversation $conversation)
    {
        // Check if the user is part of this conversation
        if (!$conversation->users->contains(Auth::id())) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'body' => 'required_without:attachments|string',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max
        ]);

        $message = new Message([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'body' => $request->body ?? '',
        ]);

        $message->save();

        // Handle file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('message-attachments', 'public');
                
                MessageAttachment::create([
                    'message_id' => $message->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        // Update conversation timestamps for all users except the sender
        foreach ($conversation->users as $user) {
            if ($user->id !== Auth::id()) {
                $conversation->users()->updateExistingPivot($user->id, [
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json(['success' => true, 'message_id' => $message->id]);
    }

    public function latest(Request $request, Conversation $conversation)
    {
        // Check if the user is part of this conversation
        if (!$conversation->users->contains(Auth::id())) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $lastId = $request->query('last_id', 0);
        $messages = Message::where('conversation_id', $conversation->id)
            ->where('id', '>', $lastId)
            ->with(['user', 'attachments'])
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'messages' => $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'conversation_id' => $message->conversation_id,
                    'user_id' => $message->user_id,
                    'user' => [
                        'name' => $message->user->name,
                    ],
                    'body' => $message->body,
                    'created_at' => $message->created_at->toDateTimeString(),
                    'attachments' => $message->attachments->map(function ($attachment) {
                        return [
                            'file_name' => $attachment->file_name,
                            'file_url' => Storage::url($attachment->file_path),
                        ];
                    })->toArray(),
                ];
            })->toArray(),
        ]);
    }
}