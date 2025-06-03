@extends('layouts.app')

@section('title', 'Chat with Users')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="flex gap-6">
                <!-- Users List -->
                <div class="w-1/3 bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Chats</h2>
                        <div class="relative">
                            <input type="text" 
                                   id="searchUsers" 
                                   class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20" 
                                   placeholder="Search users...">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-200" id="usersList">
                        @foreach($users as $user)
                            <a href="{{ route('staff.chat.show', $user) }}" 
                               class="block p-4 hover:bg-gray-50 transition-colors {{ request()->route('user') && request()->route('user')->id === $user->id ? 'bg-gray-50' : '' }} user-item"
                               data-name="{{ strtolower($user->name) }}">
                                <div class="flex items-center space-x-4">
                                    <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-blue-600 font-semibold text-xl">
                                            {{ substr($user->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-500 truncate">
                                            {{ $user->last_message ? Str::limit($user->last_message->message, 30) : 'No messages yet' }}
                                        </p>
                                    </div>
                                    @if($user->unread_count > 0)
                                        <div class="flex-shrink-0">
                                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-primary text-white text-xs font-medium">
                                                {{ $user->unread_count }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Chat Area -->
                <div class="w-2/3 bg-white rounded-xl shadow-sm border border-gray-200">
                    @if(request()->route('user'))
                        <!-- Chat Header -->
                        <div class="p-4 border-b border-gray-200">
                            <div class="flex items-center space-x-4">
                                <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-600 font-semibold text-xl">
                                        {{ substr(request()->route('user')->name, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-900">Chat with {{ request()->route('user')->name }}</h2>
                                    <p class="text-sm text-gray-500">Online</p>
                                </div>
                            </div>
                        </div>

                        <!-- Chat Messages -->
                        <div class="p-4 h-[600px] overflow-y-auto" id="chat-messages">
                            @foreach($messages as $message)
                                <div class="flex {{ $message->is_staff_reply ? 'justify-end' : 'justify-start' }} mb-4">
                                    <div class="flex items-end space-x-2 max-w-[80%]">
                                        @if(!$message->is_staff_reply)
                                            <div class="flex-shrink-0">
                                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <span class="text-blue-600 font-semibold text-sm">
                                                        {{ substr($message->user->name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="flex flex-col {{ $message->is_staff_reply ? 'items-end' : 'items-start' }}">
                                            <div class="flex items-center space-x-2 mb-1">
                                                <p class="text-xs font-medium {{ $message->is_staff_reply ? 'text-primary' : 'text-blue-600' }}">
                                                    {{ $message->is_staff_reply ? auth()->user()->name : $message->user->name }}
                                                    @if($message->is_staff_reply)
                                                        <span class="text-xs text-gray-500">(Staff)</span>
                                                    @endif
                                                </p>
                                                <span class="text-xs text-gray-500">{{ $message->created_at->format('H:i') }}</span>
                                            </div>
                                            <div class="rounded-2xl px-4 py-2 {{ $message->is_staff_reply ? 'bg-primary text-white' : 'bg-gray-100 text-gray-800' }}">
                                                <p class="text-sm">{{ $message->message }}</p>
                                            </div>
                                        </div>
                                        @if($message->is_staff_reply)
                                            <div class="flex-shrink-0">
                                                <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center">
                                                    <span class="text-primary font-semibold text-sm">
                                                        {{ substr(auth()->user()->name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Message Input -->
                        <div class="p-4 border-t border-gray-200">
                            <form action="{{ route('staff.chat.send', request()->route('user')) }}" method="POST" class="flex items-end space-x-4" id="chat-form">
                                @csrf
                                <div class="flex-1">
                                    <textarea name="message" id="message" rows="1" 
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 @error('message') border-red-300 @enderror" 
                                        placeholder="Type your message here...">{{ old('message') }}</textarea>
                                    @error('message')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit" class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-primary text-white hover:bg-opacity-90 transition-colors shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex items-center justify-center h-full">
                            <p class="text-gray-500">Select a user to start chatting</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Search functionality
    const searchInput = document.getElementById('searchUsers');
    const usersList = document.getElementById('usersList');
    const userItems = document.querySelectorAll('.user-item');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        userItems.forEach(item => {
            const userName = item.dataset.name;
            if (userName.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Auto-scroll to bottom of chat
    const chatMessages = document.getElementById('chat-messages');
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Auto-resize textarea
    const textarea = document.getElementById('message');
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    }

    // Real-time updates using Laravel Echo
    @if(request()->route('user'))
    Echo.private('chat.{{ request()->route('user')->id }}')
        .listen('NewMessage', (e) => {
            // Add new message to chat
            const message = e.message;
            const html = `
                <div class="flex justify-start mb-4">
                    <div class="flex items-end space-x-2 max-w-[80%]">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-blue-600 font-semibold text-sm">
                                    ${message.user.name.charAt(0)}
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-col items-start">
                            <div class="flex items-center space-x-2 mb-1">
                                <p class="text-xs font-medium text-blue-600">
                                    ${message.user.name}
                                </p>
                                <span class="text-xs text-gray-500">${message.created_at}</span>
                            </div>
                            <div class="rounded-2xl px-4 py-2 bg-gray-100 text-gray-800">
                                <p class="text-sm">${message.message}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            chatMessages.insertAdjacentHTML('beforeend', html);
            chatMessages.scrollTop = chatMessages.scrollHeight;

            // Show notification
            if (Notification.permission === "granted") {
                new Notification("New Message", {
                    body: `${message.user.name}: ${message.message}`,
                    icon: "/path/to/your/icon.png"
                });
            }
        });
    @endif

    // Request notification permission
    if (Notification.permission !== "granted" && Notification.permission !== "denied") {
        Notification.requestPermission();
    }
</script>
@endpush
@endsection 