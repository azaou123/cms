@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>
            @if($conversation->is_group)
                {{ $conversation->name }}
            @elseif($otherUsers->isNotEmpty()) 
                {{ $otherUsers->first()->name }}
            @else
                Unknown User
            @endif
        </h2>
        <a href="{{ route('conversations.index') }}" class="btn btn-secondary">Back to Conversations</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column" style="height: 60vh;">
                <div class="flex-grow-1 overflow-auto mb-3 p-3" id="message-container">
                    @foreach($messages as $message)
                        <div class="d-flex {{ $message->user_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }} mb-3">
                            <div class="message-bubble {{ $message->user_id === auth()->id() ? 'bg-primary text-white' : 'bg-light' }} p-3 rounded" style="max-width: 70%;">
                                @if($message->user_id !== auth()->id())
                                    <p class="small fw-bold mb-1">{{ $message->user->name }}</p>
                                @endif
                                <p class="mb-1">{{ $message->body }}</p>
                                
                                @if($message->attachments->count() > 0)
                                    <div class="mt-2">
                                        @foreach($message->attachments as $attachment)
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="bi bi-paperclip me-2"></i>
                                                <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="small {{ $message->user_id === auth()->id() ? 'text-white' : 'text-primary' }}">
                                                    {{ $attachment->file_name }}
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <p class="small {{ $message->user_id === auth()->id() ? 'text-light' : 'text-muted' }} mt-1 mb-0">
                                    {{ $message->created_at->format('M j, g:i a') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="border-top pt-3">
                    <form method="POST" action="{{ route('messages.store', $conversation) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <textarea name="body" rows="2" class="form-control @error('body') is-invalid @enderror" placeholder="Type a message..."></textarea>
                            <div class="input-group-append">
                                <label for="attachments" class="btn btn-outline-secondary">
                                    <i class="bi bi-paperclip"></i>
                                    <input type="file" id="attachments" name="attachments[]" multiple class="d-none">
                                </label>
                                <button type="submit" class="btn btn-dark">Send</button>
                            </div>
                        </div>
                        @error('body')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <div id="attachment-preview" class="mt-2"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.message-bubble {
    border-radius: 15px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Scroll to bottom of messages on page load
    const messageContainer = document.getElementById('message-container');
    messageContainer.scrollTop = messageContainer.scrollHeight;
    
    // Show file names when files are selected
    const attachmentInput = document.getElementById('attachments');
    const attachmentPreview = document.getElementById('attachment-preview');
    
    attachmentInput.addEventListener('change', function() {
        attachmentPreview.innerHTML = '';
        if (this.files.length > 0) {
            const fileList = document.createElement('div');
            fileList.classList.add('small', 'text-muted');
            fileList.innerHTML = '<strong>Selected files:</strong>';
            
            const list = document.createElement('ul');
            list.classList.add('mb-0', 'ps-3');
            
            for (let i = 0; i < this.files.length; i++) {
                const item = document.createElement('li');
                item.textContent = this.files[i].name;
                list.appendChild(item);
            }
            
            fileList.appendChild(list);
            attachmentPreview.appendChild(fileList);
        }
    });
});
</script>
@endsection