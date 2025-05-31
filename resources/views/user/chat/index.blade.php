@extends('layouts.app')

@section('title', 'Chat with Staff')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Chat Header -->
            <div class="bg-white rounded-t-xl shadow-sm border border-gray-200 p-4">
                <div class="flex items-center space-x-4">
                    <div class="h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center">
                        <span class="text-primary font-semibold text-xl">S</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Chat with Support Staff</h2>
                        <p class="text-sm text-gray-500">Online</p>
                    </div>
                </div>
            </div>

            <!-- Chat Messages -->
            <div class="bg-white border-x border-gray-200 p-4 h-[600px] overflow-y-auto" id="chat-messages">
                @foreach($messages as $message)
                    <div class="flex {{ $message->is_staff_reply ? 'justify-start' : 'justify-end' }} mb-4">
                        <div class="flex items-end space-x-2 max-w-[80%]">
                            @if($message->is_staff_reply)
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center">
                                        <span class="text-primary font-semibold text-sm">S</span>
                                    </div>
                                </div>
                            @endif
                            <div class="flex flex-col {{ $message->is_staff_reply ? 'items-start' : 'items-end' }}">
                                <div class="flex items-center space-x-2 mb-1">
                                    <p class="text-xs font-medium {{ $message->is_staff_reply ? 'text-primary' : 'text-blue-600' }}">
                                        {{ $message->is_staff_reply ? 'Support Staff' : auth()->user()->name }}
                                        @if($message->is_staff_reply)
                                            <span class="text-xs text-gray-500">(Staff)</span>
                                        @endif
                                    </p>
                                    <span class="text-xs text-gray-500">{{ $message->created_at->format('H:i') }}</span>
                                </div>
                                <div class="rounded-2xl px-4 py-2 {{ $message->is_staff_reply ? 'bg-gray-100 text-gray-800' : 'bg-primary text-white' }}">
                                    <p class="text-sm">{{ $message->message }}</p>
                                </div>
                            </div>
                            @if(!$message->is_staff_reply)
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-blue-600 font-semibold text-sm">
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
            <div class="bg-white rounded-b-xl shadow-sm border border-gray-200 p-4">
                <form action="{{ route('user.chat.send') }}" method="POST" class="flex items-end space-x-4" id="chat-form">
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
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-scroll to bottom of chat
    const chatMessages = document.getElementById('chat-messages');
    chatMessages.scrollTop = chatMessages.scrollHeight;

    // Auto-resize textarea
    const textarea = document.getElementById('message');
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Real-time updates using Laravel Echo
    Echo.private('chat.{{ auth()->id() }}')
        .listen('NewMessage', (e) => {
            // Add new message to chat
            const message = e.message;
            const html = `
                <div class="flex justify-start mb-4">
                    <div class="flex items-end space-x-2 max-w-[80%]">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center">
                                <span class="text-primary font-semibold text-sm">S</span>
                            </div>
                        </div>
                        <div class="flex flex-col items-start">
                            <div class="flex items-center space-x-2 mb-1">
                                <p class="text-xs font-medium text-primary">
                                    Support Staff
                                    <span class="text-xs text-gray-500">(Staff)</span>
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
                new Notification("New Message from Staff", {
                    body: message.message,
                    icon: "/path/to/your/icon.png"
                });
            }
        });

    // Request notification permission
    if (Notification.permission !== "granted" && Notification.permission !== "denied") {
        Notification.requestPermission();
    }
</script>
@endpush
@endsection 