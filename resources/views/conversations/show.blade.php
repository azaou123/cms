@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            <h2 class="mb-0">
                @if($conversation->is_group)
                    <i class="bi bi-people-fill me-2"></i>{{ $conversation->name }}
                @elseif($otherUsers->isNotEmpty())
                    <i class="bi bi-person-fill me-2"></i>{{ $otherUsers->first()->name }}
                @else
                    <i class="bi bi-question-circle me-2"></i>Unknown User
                @endif
            </h2>
            @if($otherUsers->isNotEmpty() && !$conversation->is_group)
                <span class="badge bg-success ms-2">
                    <i class="bi bi-circle-fill small"></i> Online
                </span>
            @endif
        </div>
        <a href="{{ route('conversations.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    @if($conversation->is_group && $otherUsers->count() > 1)
                        <span class="text-muted small">
                            <i class="bi bi-people me-1"></i> 
                            {{ $otherUsers->count() }} participants
                        </span>
                    @endif
                </div>
                <div>
                    <button class="btn btn-sm btn-outline-secondary me-2">
                        <i class="bi bi-search"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="d-flex flex-column" style="height: 65vh;">
                <div class="flex-grow-1 overflow-auto p-4" id="message-container" 
                     style="background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);"
                     data-last-message-id="{{ $messages->max('id') ?? 0 }}">
                    
                    @if($messages->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-chat-square-text display-6 mb-3"></i>
                            <p class="h5">No messages yet</p>
                            <p class="small">Start the conversation by sending a message</p>
                        </div>
                    @else
                        <div class="text-center my-3">
                            <span class="badge bg-light text-dark fw-normal">
                                {{ $messages->first()->created_at->format('F j, Y') }}
                            </span>
                        </div>
                        
                        @foreach($messages as $message)
                            @if(!$loop->first && $message->created_at->format('Y-m-d') != $messages[$loop->index-1]->created_at->format('Y-m-d'))
                                <div class="text-center my-3">
                                    <span class="badge bg-light text-dark fw-normal">
                                        {{ $message->created_at->format('F j, Y') }}
                                    </span>
                                </div>
                            @endif
                            
                            <div class="d-flex {{ $message->user_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }} mb-3" 
                                data-message-id="{{ $message->id }}">
                                
                                @if($message->user_id !== auth()->id())
                                    @if($loop->first || $message->user_id !== $messages[$loop->index - 1]->user_id)
                                        <div class="me-2">
                                            <img src="{{ $message->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($message->user->name) }}" 
                                                alt="{{ $message->user->name }}" 
                                                class="rounded-circle" 
                                                style="width: 40px; height: 40px; object-fit: cover;">
                                        </div>
                                    @else
                                        <div class="me-2" style="width: 40px;"></div>
                                    @endif
                                @endif

                                <div class="message-bubble {{ $message->user_id === auth()->id() ? 'bg-primary text-white' : 'bg-white border' }} p-3 rounded-3 position-relative"
                                    style="max-width: 75%; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                    
                                    @if($message->user_id !== auth()->id() && ($loop->first || $message->user_id !== $messages[$loop->index-1]->user_id))
                                        <p class="small fw-bold mb-1">
                                            @if($conversation->is_group)
                                                {{ $message->user->name }}
                                            @endif
                                        </p>
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
                                    
                                    <div class="d-flex justify-content-end align-items-center mt-1">
                                        <p class="small {{ $message->user_id === auth()->id() ? 'text-white-50' : 'text-muted' }} mb-0 me-2">
                                            {{ $message->created_at->format('g:i a') }}
                                        </p>
                                        @if($message->user_id === auth()->id())
                                            <i class="bi bi-check2-all small {{ $message->read_at ? 'text-info' : 'text-white-50' }}"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                
                <div class="border-top bg-light p-3 sticky-bottom" style="position: relative;">
                    <form id="message-form" method="POST" action="{{ route('messages.store', $conversation) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group has-validation">
                            <textarea name="body" id="message-input" rows="1" class="form-control border-0 shadow-sm @error('body') is-invalid @enderror" 
                                      placeholder="Type a message..." style="resize: none; border-radius: 20px !important;"></textarea>
                            
                            <div class="input-group-append ms-2 d-flex align-items-center">
                                <div class="btn-group">
                                    <label for="attachments" class="btn btn-light rounded-circle" title="Attach files">
                                        <i class="bi bi-paperclip"></i>
                                        <input type="file" id="attachments" name="attachments[]" multiple class="d-none">
                                    </label>
                                    <button type="button" id="emoji-button" class="btn btn-light rounded-circle" title="Emoji">
                                        <i class="bi bi-emoji-smile"></i>
                                    </button>
                                </div>
                                <button type="submit" class="btn btn-primary rounded-circle ms-2" title="Send">
                                    <i class="bi bi-send-fill"></i>
                                </button>
                            </div>
                            
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div id="attachment-preview" class="mt-2 d-flex flex-wrap gap-2"></div>
                    </form>
                    
                    <div id="emoji-picker-container" style="position: absolute; bottom: 80px; right: 10px; display: none; width: 350px; height: 300px; z-index: 1050; background: #ffffff; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); overflow-y: auto;">
                        <div class="emoji-grid p-2" style="display: grid; grid-template-columns: repeat(8, 1fr); gap: 5px;">
                            <!-- Smileys -->
                            <button class="emoji-btn" data-emoji="😀">😀</button>
                            <button class="emoji-btn" data-emoji="😁">😁</button>
                            <button class="emoji-btn" data-emoji="😂">😂</button>
                            <button class="emoji-btn" data-emoji="🤣">🤣</button>
                            <button class="emoji-btn" data-emoji="😃">😃</button>
                            <button class="emoji-btn" data-emoji="😄">😄</button>
                            <button class="emoji-btn" data-emoji="😅">😅</button>
                            <button class="emoji-btn" data-emoji="😆">😆</button>
                            <button class="emoji-btn" data-emoji="😉">😉</button>
                            <button class="emoji-btn" data-emoji="😊">😊</button>
                            <button class="emoji-btn" data-emoji="😋">😋</button>
                            <button class="emoji-btn" data-emoji="😎">😎</button>
                            <button class="emoji-btn" data-emoji="😍">😍</button>
                            <button class="emoji-btn" data-emoji="😘">😘</button>
                            <button class="emoji-btn" data-emoji="🥰">🥰</button>
                            <button class="emoji-btn" data-emoji="😗">😗</button>
                            <button class="emoji-btn" data-emoji="😙">😙</button>
                            <button class="emoji-btn" data-emoji="😚">😚</button>

                            <!-- Emotions -->
                            <button class="emoji-btn" data-emoji="🙂">🙂</button>
                            <button class="emoji-btn" data-emoji="🤗">🤗</button>
                            <button class="emoji-btn" data-emoji="🤩">🤩</button>
                            <button class="emoji-btn" data-emoji="🥳">🥳</button>
                            <button class="emoji-btn" data-emoji="😢">😢</button>
                            <button class="emoji-btn" data-emoji="😭">😭</button>
                            <button class="emoji-btn" data-emoji="😤">😤</button>
                            <button class="emoji-btn" data-emoji="😡">😡</button>
                            <button class="emoji-btn" data-emoji="😱">😱</button>
                            <button class="emoji-btn" data-emoji="😰">😰</button>
                            <button class="emoji-btn" data-emoji="😨">😨</button>

                            <!-- Hands & Gestures -->
                            <button class="emoji-btn" data-emoji="👍">👍</button>
                            <button class="emoji-btn" data-emoji="👎">👎</button>
                            <button class="emoji-btn" data-emoji="👏">👏</button>
                            <button class="emoji-btn" data-emoji="🙌">🙌</button>
                            <button class="emoji-btn" data-emoji="👐">👐</button>
                            <button class="emoji-btn" data-emoji="🙏">🙏</button>
                            <button class="emoji-btn" data-emoji="✌️">✌️</button>
                            <button class="emoji-btn" data-emoji="🤞">🤞</button>
                            <button class="emoji-btn" data-emoji="🤟">🤟</button>
                            <button class="emoji-btn" data-emoji="👌">👌</button>
                            <button class="emoji-btn" data-emoji="👋">👋</button>

                            <!-- Symbols -->
                            <button class="emoji-btn" data-emoji="❤️">❤️</button>
                            <button class="emoji-btn" data-emoji="🧡">🧡</button>
                            <button class="emoji-btn" data-emoji="💛">💛</button>
                            <button class="emoji-btn" data-emoji="💚">💚</button>
                            <button class="emoji-btn" data-emoji="💙">💙</button>
                            <button class="emoji-btn" data-emoji="💜">💜</button>
                            <button class="emoji-btn" data-emoji="🖤">🖤</button>
                            <button class="emoji-btn" data-emoji="💔">💔</button>
                            <button class="emoji-btn" data-emoji="💯">💯</button>
                            <button class="emoji-btn" data-emoji="✨">✨</button>
                            <button class="emoji-btn" data-emoji="🔥">🔥</button>
                            <button class="emoji-btn" data-emoji="🌟">🌟</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.message-bubble {
    border-radius: 18px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    max-width: 75%;
    word-wrap: break-word;
    font-family: "Segoe UI Emoji", "Apple Color Emoji", "Noto Color Emoji", sans-serif;
}

.message-bubble:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 8px rgba(0,0,0,0.1) !important;
}

#message-container::-webkit-scrollbar {
    width: 6px;
}

#message-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#message-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

#message-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.new-message {
    animation: fadeIn 0.3s ease-out;
}

.attachment-preview-item {
    background: rgba(0, 0, 0, 0.05);
    border-radius: 8px;
    padding: 5px 10px;
    font-size: 0.8rem;
}

.emoji-btn {
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 8px;
    font-size: 24px;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.emoji-btn:hover {
    background-color: #f8f8f8;
}

.emoji-btn:focus {
    outline: none;
    background-color: #f0f0f0;
}

#emoji-picker-container {
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    overflow: hidden;
}

@media (max-width: 576px) {
    #emoji-picker-container {
        width: 100%;
        max-width: 100%;
        right: 0;
        bottom: 70px;
        height: 300px;
    }
}

.typing-indicator {
    display: inline-block;
    padding: 8px 12px;
    background: #f1f1f1;
    border-radius: 15px;
    font-size: 0.8rem;
    color: #666;
}

.typing-indicator span {
    display: inline-block;
    width: 6px;
    height: 6px;
    background: #999;
    border-radius: 50%;
    margin: 0 2px;
    animation: bounce 1.4s infinite ease-in-out;
}

.typing-indicator span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-indicator span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes bounce {
    0%, 60%, 100% { transform: translateY(0); }
    30% { transform: translateY(-4px); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageContainer = document.getElementById('message-container');
    const form = document.getElementById('message-form');
    const textarea = document.getElementById('message-input');
    const attachmentInput = document.getElementById('attachments');
    const attachmentPreview = document.getElementById('attachment-preview');
    const emojiButton = document.getElementById('emoji-button');
    const emojiPickerContainer = document.getElementById('emoji-picker-container');

    // Auto-resize textarea
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Scroll to bottom on page load
    scrollToBottom();

    // File preview
    attachmentInput.addEventListener('change', function() {
        attachmentPreview.innerHTML = '';
        if (this.files.length > 0) {
            Array.from(this.files).forEach((file, index) => {
                const previewItem = document.createElement('div');
                previewItem.className = 'd-flex align-items-center attachment-preview-item';
                
                let iconClass = 'bi-file-earmark';
                if (file.type.startsWith('image/')) iconClass = 'bi-image';
                else if (file.type.startsWith('video/')) iconClass = 'bi-film';
                else if (file.type.startsWith('audio/')) iconClass = 'bi-music-note-beamed';
                
                previewItem.innerHTML = `
                    <i class="bi ${iconClass} me-2"></i>
                    <span class="me-3" style="max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${file.name}</span>
                    <button type="button" class="btn-close btn-close-white" aria-label="Remove" data-index="${index}"></button>
                `;
                
                attachmentPreview.appendChild(previewItem);
            });

            attachmentPreview.querySelectorAll('button').forEach(button => {
                button.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    const files = Array.from(attachmentInput.files);
                    files.splice(index, 1);
                    
                    const dataTransfer = new DataTransfer();
                    files.forEach(file => dataTransfer.items.add(file));
                    attachmentInput.files = dataTransfer.files;
                    
                    attachmentInput.dispatchEvent(new Event('change'));
                });
            });
        }
    });

    // Toggle emoji picker
    emojiButton.addEventListener('click', function(e) {
        e.stopPropagation();
        emojiPickerContainer.style.display = emojiPickerContainer.style.display === 'block' ? 'none' : 'block';
    });

    // Close emoji picker on outside click
    document.addEventListener('click', function(event) {
        if (!emojiPickerContainer.contains(event.target) && !emojiButton.contains(event.target)) {
            emojiPickerContainer.style.display = 'none';
        }
    });

    // Insert emoji
    document.querySelectorAll('.emoji-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const emoji = this.getAttribute('data-emoji');
            const cursorPos = textarea.selectionStart;
            const textBefore = textarea.value.substring(0, cursorPos);
            const textAfter = textarea.value.substring(cursorPos);
            textarea.value = textBefore + emoji + textAfter;
            textarea.selectionStart = textarea.selectionEnd = cursorPos + emoji.length;
            textarea.focus();
            textarea.dispatchEvent(new Event('input')); // Trigger auto-resize
            emojiPickerContainer.style.display = 'none';
        });
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                textarea.value = '';
                textarea.style.height = 'auto';
                attachmentPreview.innerHTML = '';
                attachmentInput.value = '';
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="bi bi-send-fill"></i>';
                fetchNewMessages();
            } else {
                showAlert('Error: ' + (data.message || 'Failed to send message'), 'danger');
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="bi bi-send-fill"></i>';
            }
        })
        .catch(error => {
            showAlert('Error: ' + error.message, 'danger');
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="bi bi-send-fill"></i>';
        });
    });

    function showAlert(message, type) {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
        alert.style.zIndex = 1060;
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.body.appendChild(alert);
        setTimeout(() => {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 150);
        }, 5000);
    }

    function fetchNewMessages() {
        const lastMessageId = messageContainer.dataset.lastMessageId || 0;
        fetch('{{ route('messages.latest', $conversation) }}?last_id=' + lastMessageId, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.messages && data.messages.length > 0) {
                data.messages.forEach(message => {
                    if (!document.querySelector(`[data-message-id="${message.id}"]`)) {
                        const isCurrentUser = message.user_id === {{ auth()->id() }};
                        const messageHtml = `
                            <div class="d-flex ${isCurrentUser ? 'justify-content-end' : 'justify-content-start'} mb-3 new-message" 
                                 data-message-id="${message.id}">
                                <div class="message-bubble ${isCurrentUser ? 'bg-primary text-white' : 'bg-white border'} p-3 rounded-3"
                                     style="max-width: 75%; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                    ${!isCurrentUser && (message.show_sender || {{ $conversation->is_group ? 'true' : 'false' }}) ? 
                                        `<p class="small fw-bold mb-1">${message.user.name}</p>` : ''}
                                    <p class="mb-1">${message.body}</p>
                                    ${message.attachments.length > 0 ? `
                                        <div class="mt-2">
                                            ${message.attachments.map(attachment => `
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="bi bi-paperclip me-2"></i>
                                                    <a href="${attachment.file_url}" target="_blank" class="small ${isCurrentUser ? 'text-white' : 'text-primary'}">
                                                        ${attachment.file_name}
                                                    </a>
                                                </div>
                                            `).join('')}
                                        </div>
                                    ` : ''}
                                    <div class="d-flex justify-content-end align-items-center mt-1">
                                        <p class="small ${isCurrentUser ? 'text-white-50' : 'text-muted'} mb-0 me-2">
                                            ${new Date(message.created_at).toLocaleTimeString([], {hour: 'numeric', minute: '2-digit'})}
                                        </p>
                                        ${isCurrentUser ? 
                                            `<i class="bi bi-check2-all small ${message.read_at ? 'text-info' : 'text-white-50'}"></i>` : ''}
                                    </div>
                                </div>
                            </div>
                        `;
                        messageContainer.insertAdjacentHTML('beforeend', messageHtml);
                        messageContainer.dataset.lastMessageId = Math.max(messageContainer.dataset.lastMessageId, message.id);
                        if (isNearBottom()) {
                            scrollToBottom();
                        }
                    }
                });
            }
        })
        .catch(error => console.error('Error fetching messages:', error));
    }

    function isNearBottom() {
        return messageContainer.scrollTop + messageContainer.clientHeight > messageContainer.scrollHeight - 100;
    }

    function scrollToBottom() {
        messageContainer.scrollTo({
            top: messageContainer.scrollHeight,
            behavior: 'smooth'
        });
    }

    setInterval(fetchNewMessages, 3000);
    fetchNewMessages();

    const scrollToBottomBtn = document.createElement('button');
    scrollToBottomBtn.className = 'btn btn-primary btn-sm rounded-circle position-fixed';
    scrollToBottomBtn.style.right = '20px';
    scrollToBottomBtn.style.bottom = '80px';
    scrollToBottomBtn.style.width = '40px';
    scrollToBottomBtn.style.height = '40px';
    scrollToBottomBtn.style.display = 'none';
    scrollToBottomBtn.style.zIndex = '1000';
    scrollToBottomBtn.innerHTML = '<i class="bi bi-arrow-down"></i>';
    scrollToBottomBtn.title = 'Scroll to bottom';
    scrollToBottomBtn.addEventListener('click', scrollToBottom);
    document.body.appendChild(scrollToBottomBtn);

    messageContainer.addEventListener('scroll', function() {
        scrollToBottomBtn.style.display = isNearBottom() ? 'none' : 'block';
    });
});
</script>
@endsection