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
        <div>
            <button class="btn btn-outline-secondary me-2" id="conversation-info-btn" title="Conversation Info">
                <i class="bi bi-info-circle"></i>
            </button>
            <a href="{{ route('conversations.index') }}" class="btn btn-outline-secondary" title="Back to conversations">
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>

    <!-- Conversation Info Modal -->
    <div class="modal fade" id="conversationInfoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Conversation Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($conversation->is_group)
                        <div class="text-center mb-3">
                            <div class="avatar-group mb-2">
                                @foreach($otherUsers->take(4) as $user)
                                    <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" 
                                         class="avatar" alt="{{ $user->name }}"
                                         title="{{ $user->name }}">
                                @endforeach
                                @if($otherUsers->count() > 4)
                                    <div class="avatar more-count">+{{ $otherUsers->count() - 4 }}</div>
                                @endif
                            </div>
                            <h4>{{ $conversation->name }}</h4>
                            <p class="text-muted">{{ $otherUsers->count() }} participants</p>
                        </div>
                    @else
                        <div class="text-center mb-3">
                            <img src="{{ $otherUsers->first()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($otherUsers->first()->name) }}" 
                                 class="avatar-lg mb-2" alt="{{ $otherUsers->first()->name }}">
                            <h4>{{ $otherUsers->first()->name }}</h4>
                            <p class="text-muted">{{ $otherUsers->first()->email }}</p>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Media, Files and Links</h6>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-secondary flex-fill" id="view-media-btn">
                                <i class="bi bi-image me-1"></i> Media
                            </button>
                            <button class="btn btn-outline-secondary flex-fill" id="view-files-btn">
                                <i class="bi bi-file-earmark me-1"></i> Files
                            </button>
                            <button class="btn btn-outline-secondary flex-fill" id="view-links-btn">
                                <i class="bi bi-link-45deg me-1"></i> Links
                            </button>
                        </div>
                    </div>
                    
                    @if($conversation->is_group)
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">Group Members</h6>
                            <div class="list-group">
                                @foreach($otherUsers as $user)
                                    <div class="list-group-item list-group-item-action">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" 
                                                 class="avatar-sm me-2" alt="{{ $user->name }}">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">{{ $user->name }}</h6>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                            <span class="badge bg-success">
                                                <i class="bi bi-circle-fill small"></i> Online
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <div class="d-grid gap-2">
                        @if($conversation->is_group)
                            <button class="btn btn-outline-danger" id="leave-group-btn">
                                <i class="bi bi-box-arrow-right me-1"></i> Leave Group
                            </button>
                        @else
                            <button class="btn btn-outline-danger" id="delete-conversation-btn">
                                <i class="bi bi-trash me-1"></i> Delete Conversation
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Media Viewer Modal -->
    <div class="modal fade" id="mediaViewerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content bg-dark">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="media-viewer-image" src="" class="img-fluid" style="max-height: 70vh;" alt="Media">
                    <video id="media-viewer-video" controls class="img-fluid d-none" style="max-height: 70vh;"></video>
                </div>
                <div class="modal-footer border-0 justify-content-between">
                    <div class="text-white" id="media-viewer-info"></div>
                    <div>
                        <a href="#" id="media-download-btn" class="btn btn-outline-light me-2">
                            <i class="bi bi-download"></i> Download
                        </a>
                        <button class="btn btn-outline-light" id="media-viewer-prev">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <span class="text-white mx-2" id="media-viewer-counter">1/1</span>
                        <button class="btn btn-outline-light" id="media-viewer-next">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
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
                    
                    <div id="typing-indicator" class="typing-indicator d-none">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span id="typing-text">is typing</span>
                    </div>
                </div>
                <div>
                    <button class="btn btn-sm btn-outline-secondary me-2" id="search-messages-btn" title="Search messages">
                        <i class="bi bi-search"></i>
                    </button>
                    <div class="dropdown d-inline-block">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="more-options-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="more-options-dropdown">
                            <li><a class="dropdown-item" href="#" id="mark-as-read"><i class="bi bi-check2-all me-2"></i>Mark as read</a></li>
                            <li><a class="dropdown-item" href="#" id="view-media"><i class="bi bi-image me-2"></i>View media</a></li>
                            <li><a class="dropdown-item" href="#" id="view-files"><i class="bi bi-file-earmark me-2"></i>View files</a></li>
                            <li><hr class="dropdown-divider"></li>
                            @if($conversation->is_group)
                                <li><a class="dropdown-item text-danger" href="#" id="leave-group"><i class="bi bi-box-arrow-right me-2"></i>Leave group</a></li>
                            @else
                                <li><a class="dropdown-item text-danger" href="#" id="delete-conversation"><i class="bi bi-trash me-2"></i>Delete conversation</a></li>
                            @endif
                        </ul>
                    </div>
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
                                <div class="text-center my-1">
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
                                                style="width: 40px; height: 40px; object-fit: cover;"
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                title="{{ $message->user->name }}"
                                                onclick="showUserInfo({{ $message->user->id }})">
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
                                    
                                    <p class="mb-1 message-text">{{ $message->body }}</p>
                                    
                                    @if($message->attachments->count() > 0)
                                        <div class="mt-2">
                                            @foreach($message->attachments as $attachment)
                                                @php
                                                    $isImage = Str::startsWith($attachment->mime_type, 'image/');
                                                    $isVideo = Str::startsWith($attachment->mime_type, 'video/');
                                                    $fileUrl = Storage::url($attachment->file_path);
                                                @endphp
                                                <div class="mb-2">
                                                    @if($isImage)
                                                        <div class="gallery-item" data-src="{{ $fileUrl }}" data-caption="{{ $attachment->file_name }}">
                                                            <img src="{{ $fileUrl }}" 
                                                                 alt="{{ $attachment->file_name }}"
                                                                 class="img-thumbnail rounded"
                                                                 style="max-width: 100%; max-height: 300px; cursor: zoom-in;">
                                                        </div>
                                                    @elseif($isVideo)
                                                        <div class="ratio ratio-16x9">
                                                            <video controls style="background-color: #000;">
                                                                <source src="{{ $fileUrl }}" type="{{ $attachment->mime_type }}">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                        </div>
                                                    @else
                                                        <div class="d-flex align-items-center p-2 bg-light rounded">
                                                            <div class="me-3">
                                                                <i class="bi bi-file-earmark-text display-6 text-muted"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="fw-bold text-truncate" style="max-width: 200px;">{{ $attachment->file_name }}</div>
                                                                <div class="small text-muted">{{ $attachment->mime_type }} • {{ number_format($attachment->size / 1024, 1) }} KB</div>
                                                            </div>
                                                            <div>
                                                                <a href="{{ $fileUrl }}" download class="btn btn-sm btn-outline-secondary">
                                                                    <i class="bi bi-download"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif
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
                                    
                                    <div class="message-actions d-none position-absolute" 
                                         style="top: -10px; {{ $message->user_id === auth()->id() ? 'left: -10px' : 'right: -10px' }}">
                                        <div class="btn-group btn-group-sm shadow">
                                            <button class="btn btn-light" title="Reply">
                                                <i class="bi bi-reply"></i>
                                            </button>
                                            <button class="btn btn-light" title="Forward">
                                                <i class="bi bi-forward"></i>
                                            </button>
                                            <button class="btn btn-light" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
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
                                        <input type="file" id="attachments" name="attachments[]" multiple class="d-none" accept="image/*, video/*, .pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .txt">
                                    </label>
                                    <button type="button" id="emoji-button" class="btn btn-light rounded-circle" title="Emoji">
                                        <i class="bi bi-emoji-smile"></i>
                                    </button>
                                    <button type="button" id="voice-message-btn" class="btn btn-light rounded-circle" title="Record voice message">
                                        <i class="bi bi-mic"></i>
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
                    
                    <!-- Voice Message Recorder -->
                    <div id="voice-recorder-container" class="p-3 bg-white rounded shadow-sm mt-2" style="display: none;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <button class="btn btn-danger rounded-circle me-3" id="record-button">
                                    <i class="bi bi-mic-fill"></i>
                                </button>
                                <div id="recording-timer" class="text-muted">00:00</div>
                            </div>
                            <div id="recording-visualizer" style="height: 40px; width: 150px;"></div>
                            <div>
                                <button class="btn btn-outline-secondary me-2" id="cancel-recording">
                                    Cancel
                                </button>
                                <button class="btn btn-primary" id="send-recording" disabled>
                                    Send
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Emoji Picker -->
                    <div id="emoji-picker-container" style="position: absolute; bottom: 80px; right: 10px; display: none; width: 350px; height: 300px; z-index: 1050; background: #ffffff; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); overflow-y: auto;">
                        <div class="emoji-tabs">
                            <button class="emoji-tab active" data-category="smileys">😀</button>
                            <button class="emoji-tab" data-category="people">👋</button>
                            <button class="emoji-tab" data-category="animals">🐻</button>
                            <button class="emoji-tab" data-category="food">🍎</button>
                            <button class="emoji-tab" data-category="activities">⚽</button>
                            <button class="emoji-tab" data-category="objects">💡</button>
                            <button class="emoji-tab" data-category="symbols">❤️</button>
                            <button class="emoji-tab" data-category="flags">🏳️</button>
                        </div>
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

.message-bubble:hover .message-actions {
    display: block !important;
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

.emoji-tabs {
    display: flex;
    border-bottom: 1px solid #e0e0e0;
    padding: 5px;
}

.emoji-tab {
    flex: 1;
    border: none;
    background: none;
    padding: 8px;
    font-size: 18px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.emoji-tab:hover {
    background-color: #f0f0f0;
}

.emoji-tab.active {
    background-color: #e0e0e0;
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

.avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid white;
    margin-left: -10px;
    transition: transform 0.2s;
}

.avatar:hover {
    transform: scale(1.1);
    z-index: 1;
}

.avatar-sm {
    width: 30px;
    height: 30px;
}

.avatar-lg {
    width: 80px;
    height: 80px;
}

.avatar-group {
    display: flex;
    justify-content: center;
}

.more-count {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #e9ecef;
    color: #6c757d;
    font-weight: bold;
}

.gallery-item {
    cursor: pointer;
    transition: transform 0.2s;
}

.gallery-item:hover {
    transform: scale(1.02);
}

#recording-visualizer {
    background: linear-gradient(to right, #f8f9fa, #e9ecef);
    border-radius: 20px;
    overflow: hidden;
    position: relative;
}

#recording-visualizer::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 0;
    background: linear-gradient(to right, #0d6efd, #0b5ed7);
    animation: recordingAnimation 1s infinite alternate;
}

@keyframes recordingAnimation {
    from { width: 0; }
    to { width: 100%; }
}

@media (max-width: 576px) {
    #emoji-picker-container {
        width: 100%;
        max-width: 100%;
        right: 0;
        bottom: 70px;
        height: 300px;
    }
    
    .message-bubble {
        max-width: 85%;
    }
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
    const voiceMessageBtn = document.getElementById('voice-message-btn');
    const voiceRecorderContainer = document.getElementById('voice-recorder-container');
    const recordButton = document.getElementById('record-button');
    const cancelRecordingBtn = document.getElementById('cancel-recording');
    const sendRecordingBtn = document.getElementById('send-recording');
    const recordingTimer = document.getElementById('recording-timer');
    const typingIndicator = document.getElementById('typing-indicator');
    const conversationInfoBtn = document.getElementById('conversation-info-btn');
    const conversationInfoModal = new bootstrap.Modal(document.getElementById('conversationInfoModal'));
    const mediaViewerModal = new bootstrap.Modal(document.getElementById('mediaViewerModal'));
    const mediaViewerImage = document.getElementById('media-viewer-image');
    const mediaViewerVideo = document.getElementById('media-viewer-video');
    const mediaViewerInfo = document.getElementById('media-viewer-info');
    const mediaDownloadBtn = document.getElementById('media-download-btn');
    const mediaViewerPrev = document.getElementById('media-viewer-prev');
    const mediaViewerNext = document.getElementById('media-viewer-next');
    const mediaViewerCounter = document.getElementById('media-viewer-counter');
    
    let mediaItems = [];
    let currentMediaIndex = 0;
    let isRecording = false;
    let mediaRecorder;
    let audioChunks = [];
    let timerInterval;
    let seconds = 0;
    let typingTimer;
    let isTyping = false;
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Auto-resize textarea
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
        
        // Typing indicator
        if (!isTyping) {
            isTyping = true;
            Echo.private(`conversation.{{ $conversation->id }}`)
                .whisper('typing', {
                    user_id: {{ auth()->id() }},
                    name: '{{ auth()->user()->name }}'
                });
        }
        
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            isTyping = false;
        }, 2000);
    });
    
    // Listen for typing events
    Echo.private(`conversation.{{ $conversation->id }}`)
        .listenForWhisper('typing', (e) => {
            if (e.user_id !== {{ auth()->id() }}) {
                typingIndicator.classList.remove('d-none');
                document.getElementById('typing-text').textContent = `${e.name} is typing`;
                
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    typingIndicator.classList.add('d-none');
                }, 2000);
            }
        });
    
    // Scroll to bottom on page load
    scrollToBottom();
    
    // Initialize media items for gallery
    document.querySelectorAll('.gallery-item').forEach(item => {
        mediaItems.push({
            src: item.dataset.src,
            caption: item.dataset.caption,
            type: 'image'
        });
    });
    
    // Open media viewer when clicking on gallery items
    document.querySelectorAll('.gallery-item').forEach((item, index) => {
        item.addEventListener('click', () => {
            const mediaSrc = item.dataset.src;
            const mediaType = mediaSrc.match(/\.(mp4|webm|ogg)$/i) ? 'video' : 'image';
            
            currentMediaIndex = index;
            updateMediaViewer();
            mediaViewerModal.show();
        });
    });
    
    // Media viewer navigation
    mediaViewerPrev.addEventListener('click', () => {
        if (currentMediaIndex > 0) {
            currentMediaIndex--;
            updateMediaViewer();
        }
    });
    
    mediaViewerNext.addEventListener('click', () => {
        if (currentMediaIndex < mediaItems.length - 1) {
            currentMediaIndex++;
            updateMediaViewer();
        }
    });
    
    function updateMediaViewer() {
        const media = mediaItems[currentMediaIndex];
        
        if (media.type === 'image') {
            mediaViewerImage.src = media.src;
            mediaViewerImage.classList.remove('d-none');
            mediaViewerVideo.classList.add('d-none');
            mediaViewerVideo.pause();
        } else {
            mediaViewerVideo.src = media.src;
            mediaViewerVideo.classList.remove('d-none');
            mediaViewerImage.classList.add('d-none');
        }
        
        mediaViewerInfo.textContent = media.caption;
        mediaDownloadBtn.href = media.src;
        mediaViewerCounter.textContent = `${currentMediaIndex + 1}/${mediaItems.length}`;
    }
    
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
    
    // Emoji tab switching
    document.querySelectorAll('.emoji-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.emoji-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            // In a real app, you would filter emojis by category here
        });
    });
    
    // Close emoji picker on outside click
    document.addEventListener('click', function(event) {
        if (!emojiPickerContainer.contains(event.target) {
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
    
    // Voice message recording
    voiceMessageBtn.addEventListener('click', function() {
        voiceRecorderContainer.style.display = voiceRecorderContainer.style.display === 'block' ? 'none' : 'block';
        if (voiceRecorderContainer.style.display === 'block') {
            initVoiceRecorder();
        }
    });
    
    function initVoiceRecorder() {
        navigator.mediaDevices.getUserMedia({ audio: true })
            .then(stream => {
                mediaRecorder = new MediaRecorder(stream);
                
                mediaRecorder.ondataavailable = function(e) {
                    audioChunks.push(e.data);
                };
                
                mediaRecorder.onstop = function() {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                    const audioUrl = URL.createObjectURL(audioBlob);
                    
                    // Create a download link for testing
                    const a = document.createElement('a');
                    a.href = audioUrl;
                    a.download = 'recording.wav';
                    a.click();
                    
                    // In a real app, you would upload this to your server
                    console.log('Recording ready to upload:', audioBlob);
                };
                
                recordButton.addEventListener('click', function() {
                    if (!isRecording) {
                        // Start recording
                        audioChunks = [];
                        mediaRecorder.start();
                        isRecording = true;
                        recordButton.innerHTML = '<i class="bi bi-stop-fill"></i>';
                        recordButton.classList.add('btn-danger');
                        recordButton.classList.remove('btn-outline-danger');
                        sendRecordingBtn.disabled = true;
                        
                        // Start timer
                        seconds = 0;
                        timerInterval = setInterval(() => {
                            seconds++;
                            const mins = Math.floor(seconds / 60);
                            const secs = seconds % 60;
                            recordingTimer.textContent = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                            
                            // Enable send button after 1 second
                            if (seconds >= 1) {
                                sendRecordingBtn.disabled = false;
                            }
                        }, 1000);
                    } else {
                        // Stop recording
                        mediaRecorder.stop();
                        isRecording = false;
                        recordButton.innerHTML = '<i class="bi bi-mic-fill"></i>';
                        recordButton.classList.remove('btn-danger');
                        recordButton.classList.add('btn-outline-danger');
                        clearInterval(timerInterval);
                    }
                });
                
                cancelRecordingBtn.addEventListener('click', function() {
                    if (isRecording) {
                        mediaRecorder.stop();
                        isRecording = false;
                        clearInterval(timerInterval);
                    }
                    voiceRecorderContainer.style.display = 'none';
                    recordingTimer.textContent = '00:00';
                    audioChunks = [];
                });
                
                sendRecordingBtn.addEventListener('click', function() {
                    if (audioChunks.length > 0) {
                        // Create FormData and send to server
                        const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                        const formData = new FormData();
                        formData.append('audio', audioBlob, 'voice-message.wav');
                        formData.append('_token', '{{ csrf_token() }}');
                        
                        fetch('{{ route('messages.store', $conversation) }}', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                fetchNewMessages();
                            }
                        })
                        .catch(error => {
                            console.error('Error sending voice message:', error);
                        });
                    }
                    
                    voiceRecorderContainer.style.display = 'none';
                    recordingTimer.textContent = '00:00';
                    audioChunks = [];
                });
            })
            .catch(error => {
                console.error('Error accessing microphone:', error);
                showAlert('Microphone access denied. Please enable microphone permissions.', 'danger');
                voiceRecorderContainer.style.display = 'none';
            });
    }
    
    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        
        // Add typing indicator to show the message is being sent
        const typingElement = document.createElement('div');
        typingElement.className = 'd-flex justify-content-end mb-3';
        typingElement.innerHTML = `
            <div class="message-bubble bg-light border p-3 rounded-3" style="max-width: 75%;">
                <div class="typing-indicator">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span>Sending...</span>
                </div>
            </div>
        `;
        messageContainer.appendChild(typingElement);
        scrollToBottom();
        
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
                
                // Remove typing indicator
                messageContainer.removeChild(typingElement);
                
                fetchNewMessages();
            } else {
                showAlert('Error: ' + (data.message || 'Failed to send message'), 'danger');
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="bi bi-send-fill"></i>';
                
                // Remove typing indicator
                messageContainer.removeChild(typingElement);
            }
        })
        .catch(error => {
            showAlert('Error: ' + error.message, 'danger');
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="bi bi-send-fill"></i>';
            
            // Remove typing indicator
            messageContainer.removeChild(typingElement);
        });
    });
    
    // Show conversation info modal
    conversationInfoBtn.addEventListener('click', function() {
        conversationInfoModal.show();
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
                                ${!isCurrentUser ? `
                                    <div class="me-2">
                                        <img src="${message.user.avatar_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(message.user.name)}" 
                                            alt="${message.user.name}" 
                                            class="rounded-circle" 
                                            style="width: 40px; height: 40px; object-fit: cover;"
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            title="${message.user.name}">
                                    </div>
                                ` : ''}
                                <div class="message-bubble ${isCurrentUser ? 'bg-primary text-white' : 'bg-white border'} p-3 rounded-3"
                                     style="max-width: 75%; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                    ${!isCurrentUser && (message.show_sender || {{ $conversation->is_group ? 'true' : 'false' }}) ? 
                                        `<p class="small fw-bold mb-1">${message.user.name}</p>` : ''}
                                    <p class="mb-1 message-text">${message.body}</p>
                                    ${message.attachments.length > 0 ? `
                                        <div class="mt-2">
                                            ${message.attachments.map(attachment => {
                                                const isImage = attachment.mime_type.startsWith('image/');
                                                const isVideo = attachment.mime_type.startsWith('video/');
                                                
                                                if (isImage) {
                                                    return `
                                                        <div class="gallery-item" data-src="${attachment.file_url}" data-caption="${attachment.file_name}">
                                                            <img src="${attachment.file_url}" 
                                                                 alt="${attachment.file_name}"
                                                                 class="img-thumbnail rounded"
                                                                 style="max-width: 100%; max-height: 300px; cursor: zoom-in;">
                                                        </div>
                                                    `;
                                                } else if (isVideo) {
                                                    return `
                                                        <div class="ratio ratio-16x9">
                                                            <video controls style="background-color: #000;">
                                                                <source src="${attachment.file_url}" type="${attachment.mime_type}">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                        </div>
                                                    `;
                                                } else {
                                                    return `
                                                        <div class="d-flex align-items-center p-2 bg-light rounded">
                                                            <div class="me-3">
                                                                <i class="bi bi-file-earmark-text display-6 text-muted"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="fw-bold text-truncate" style="max-width: 200px;">${attachment.file_name}</div>
                                                                <div class="small text-muted">${attachment.mime_type} • ${(attachment.size ? formatFileSize(attachment.size) : '')}</div>
                                                            </div>
                                                            <div>
                                                                <a href="${attachment.file_url}" download class="btn btn-sm btn-outline-secondary">
                                                                    <i class="bi bi-download"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    `;
                                                }
                                            }).join('')}
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
                        
                        // Reinitialize gallery items for new messages
                        const newGalleryItems = messageContainer.querySelectorAll('.gallery-item');
                        newGalleryItems.forEach((item, index) => {
                            item.addEventListener('click', () => {
                                const mediaSrc = item.dataset.src;
                                const mediaType = mediaSrc.match(/\.(mp4|webm|ogg)$/i) ? 'video' : 'image';
                                
                                // Find the index in the mediaItems array
                                currentMediaIndex = Array.from(messageContainer.querySelectorAll('.gallery-item')).indexOf(item);
                                updateMediaViewer();
                                mediaViewerModal.show();
                            });
                        });
                        
                        if (isNearBottom()) {
                            scrollToBottom();
                        }
                    }
                });
            }
        })
        .catch(error => console.error('Error fetching messages:', error));
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
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
    
    // Poll for new messages every 3 seconds
    setInterval(fetchNewMessages, 3000);
    
    // Create scroll-to-bottom button
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
    
    // Show/hide scroll-to-bottom button based on scroll position
    messageContainer.addEventListener('scroll', function() {
        scrollToBottomBtn.style.display = isNearBottom() ? 'none' : 'block';
    });
    
    // Mark messages as read when scrolling to bottom
    messageContainer.addEventListener('scroll', function() {
        if (isNearBottom()) {
            markMessagesAsRead();
        }
    });
    
    function markMessagesAsRead() {
        const unreadMessages = document.querySelectorAll('.bi-check2-all.text-white-50');
        if (unreadMessages.length > 0) {
            fetch('{{ route('messages.markAsRead', $conversation) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    unreadMessages.forEach(icon => {
                        icon.classList.remove('text-white-50');
                        icon.classList.add('text-info');
                    });
                }
            });
        }
    }
    
    // Initialize with messages marked as read if at bottom
    if (isNearBottom()) {
        markMessagesAsRead();
    }
    
    // Link detection and formatting
    function linkifyText(text) {
        const urlRegex = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
        return text.replace(urlRegex, url => {
            return `<a href="${url}" target="_blank" class="text-decoration-none">${url}</a>`;
        });
    }
    
    // Apply link detection to existing messages
    document.querySelectorAll('.message-text').forEach(element => {
        element.innerHTML = linkifyText(element.textContent);
    });
    
    // Apply link detection to new messages in fetchNewMessages
    // (see the messageHtml construction where we set the message body)
});
</script>
@endsection