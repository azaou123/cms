<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Attachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ConversationController extends Controller
{
    public function index()
    {
        $conversations = Auth::user()->conversations()
            ->with(['users' => function($query) {
                $query->where('users.id', '!=', Auth::id());
            }, 'lastMessage'])
            ->get();

        return view('conversations.index', compact('conversations'));
    }

    public function startWithUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $userId = $request->user_id;
        $user = User::where('id', $userId)->first();

        // Check if a conversation already exists between these users
        $existingConversation = Auth::user()->conversations()
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            })
            ->whereHas('users', function ($query) {
                $query->where('users.id', Auth::id());
            })
            ->where('is_group', false)
            ->first();

        if ($existingConversation) {
            return redirect()->route('conversations.show', $existingConversation)
                ->with('success', 'Conversation already exists');
        }

        // Create a new conversation
        $conversation = Conversation::create([
            'is_group' => false,
            'name' => $user->name,
        ]);

        // Attach both users
        $conversation->users()->syncWithoutDetaching([$userId, Auth::id()]);

        return redirect()->route('conversations.show', $conversation)
            ->with('success', 'Conversation started successfully');
    }

    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('conversations.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'users' => 'required|array|min:1',
            'users.*' => 'exists:users,id',
            'is_group' => 'boolean',
        ]);

        $conversation = Conversation::create([
            'name' => $request->name,
            'is_group' => $request->boolean('is_group', false),
            'created_by' => Auth::id(),
        ]);

        // Add the creator and selected users
        $userIds = array_unique(array_merge($request->users, [Auth::id()]));
        $conversation->users()->attach($userIds);

        return redirect()->route('conversations.show', $conversation)
            ->with('success', 'Conversation created successfully');
    }

    public function show(Conversation $conversation)
    {
        // Check if the user is part of this conversation
        if (!$conversation->users->contains(Auth::id())) {
            abort(403, 'You are not authorized to view this conversation');
        }

        // Mark messages as read
        $conversation->messages()
            ->where('user_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Update last read timestamp
        $conversation->users()->updateExistingPivot(Auth::id(), [
            'last_read_at' => now(),
        ]);

        $messages = $conversation->messages()->with('user', 'attachments')->orderBy('created_at', 'asc')->get();
        $otherUsers = $conversation->users->where('id', '!=', Auth::id());

        return view('conversations.show', compact('conversation', 'messages', 'otherUsers'));
    }

    public function getLatestMessages(Conversation $conversation, Request $request)
    {
        // Check if the user is part of this conversation
        if (!$conversation->users->contains(Auth::id())) {
            abort(403, 'You are not authorized to view this conversation');
        }

        $lastMessageId = $request->get('last_id', 0);
        
        $messages = $conversation->messages()
            ->with(['user', 'attachments'])
            ->where('id', '>', $lastMessageId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'body' => $message->body,
                    'user_id' => $message->user_id,
                    'created_at' => $message->created_at->toISOString(),
                    'read_at' => $message->read_at ? $message->read_at->toISOString() : null,
                    'user' => [
                        'id' => $message->user->id,
                        'name' => $message->user->name,
                        'avatar_url' => $message->user->avatar_url,
                    ],
                    'attachments' => $message->attachments->map(function ($attachment) {
                        return [
                            'id' => $attachment->id,
                            'file_name' => $attachment->file_name,
                            'file_path' => $attachment->file_path,
                            'file_url' => Storage::url($attachment->file_path),
                            'mime_type' => $attachment->mime_type,
                            'size' => $attachment->size,
                        ];
                    }),
                ];
            });

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    public function markAsRead(Conversation $conversation)
    {
        // Check if the user is part of this conversation
        if (!$conversation->users->contains(Auth::id())) {
            abort(403, 'You are not authorized to access this conversation');
        }

        // Mark messages as read
        $conversation->messages()
            ->where('user_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function storeMessage(Request $request, Conversation $conversation)
    {
        // Check if the user is part of this conversation
        if (!$conversation->users->contains(Auth::id())) {
            abort(403, 'You are not authorized to send messages in this conversation');
        }

        $request->validate([
            'body' => 'required|string|max:5000',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,mp4,avi,mov|max:10240',
        ]);

        $message = new Message();
        $message->body = $request->input('body');
        $message->user_id = Auth::id();
        $message->conversation_id = $conversation->id;
        $message->save();

        // Handle file attachments if any
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                $attachment = new Attachment([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
                $message->attachments()->save($attachment);
            }
        }

        // Load the message with relationships for the response
        $message->load(['user', 'attachments']);

        // Update conversation's updated_at timestamp
        $conversation->touch();

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'body' => $message->body,
                'user_id' => $message->user_id,
                'created_at' => $message->created_at->toISOString(),
                'read_at' => $message->read_at ? $message->read_at->toISOString() : null,
                'user' => [
                    'id' => $message->user->id,
                    'name' => $message->user->name,
                    'avatar_url' => $message->user->avatar_url,
                ],
                'attachments' => $message->attachments->map(function ($attachment) {
                    return [
                        'id' => $attachment->id,
                        'file_name' => $attachment->file_name,
                        'file_path' => $attachment->file_path,
                        'file_url' => Storage::url($attachment->file_path),
                        'mime_type' => $attachment->mime_type,
                        'size' => $attachment->size,
                    ];
                }),
            ],
        ]);
    }

    public function storeVoiceMessage(Request $request, Conversation $conversation)
    {
        // Check if the user is part of this conversation
        if (!$conversation->users->contains(Auth::id())) {
            abort(403, 'You are not authorized to send messages in this conversation');
        }

        $request->validate([
            'audio' => 'required|file|mimes:wav,mp3,m4a|max:5120', // 5MB max for audio
        ]);

        $message = new Message();
        $message->body = '🎤 Voice message';
        $message->user_id = Auth::id();
        $message->conversation_id = $conversation->id;
        $message->save();

        // Handle audio file
        if ($request->hasFile('audio')) {
            $file = $request->file('audio');
            $path = $file->store('voice_messages', 'public');
            
            $attachment = new Attachment([
                'file_name' => 'voice-message-' . time() . '.wav',
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
            $message->attachments()->save($attachment);
        }

        // Update conversation's updated_at timestamp
        $conversation->touch();

        return response()->json([
            'success' => true,
            'message' => 'Voice message sent successfully',
        ]);
    }
}
