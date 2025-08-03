<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user=User::where('id',$userId)->first();


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

    public function pollMessages($conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);
        $messages = $conversation->messages()->with('user')->latest()->get();
        return response()->json($messages);
    }

    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('conversations.create', compact('users'));
    }

    public function store(Request $request, Conversation $conversation)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,docx,txt|max:10240',
        ]);

        $message = new Message();
        $message->body = $request->input('body');
        $message->user_id = auth()->id();
        $message->conversation_id = $conversation->id;
        $message->save();

        // Handle file attachments if any
        if ($request->has('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments');
                $attachment = new Attachment([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                ]);
                $message->attachments()->save($attachment);
            }
        }

        // Return the newly created message in JSON format
        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
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
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Update last read timestamp
        $conversation->users()->updateExistingPivot(Auth::id(), [
            'last_read_at' => now(),
            'unread' => false,
        ]);

        $messages = $conversation->messages()->with('user', 'attachments')->orderBy('created_at', 'asc')->get();
        $otherUsers = $conversation->users->where('id', '!=', Auth::id());

        return view('conversations.show', compact('conversation', 'messages', 'otherUsers'));
    }
}
