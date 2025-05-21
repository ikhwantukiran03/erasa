@extends('layouts.app')

@section('title', 'Ticket Details - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gradient-to-r from-primary/10 to-secondary/20 py-10">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div data-aos="fade-up">
                <h1 class="text-3xl font-display font-bold text-primary mb-1">Ticket Details</h1>
                <p class="text-gray-600">View and manage your support ticket</p>
            </div>
            <div class="mt-4 md:mt-0" data-aos="fade-up" data-aos-delay="100">
                <a href="{{ route('user.tickets.index') }}" class="inline-flex items-center text-primary hover:underline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Tickets
                </a>
            </div>
        </div>
    </div>
</div>

<div class="bg-gray-50 py-10">
    <div class="container mx-auto px-4">
        <!-- Ticket Details -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8 border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h2 class="text-xl font-semibold text-gray-800">Ticket #{{ $ticket->id }}</h2>
                    </div>
                    <div>
                        @if($ticket->status === 'open')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                Open
                            </span>
                        @elseif($ticket->status === 'in_progress')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                In Progress
                            </span>
                        @elseif($ticket->status === 'closed')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                Closed
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $ticket->title }}</h3>
                    <div class="flex items-center text-sm text-gray-500 mb-4">
                        <span class="mr-4">Category: {{ ucfirst($ticket->category) }}</span>
                        <span>Created: {{ $ticket->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="prose max-w-none">
                        {{ $ticket->description }}
                    </div>
                </div>

                <!-- Ticket Replies -->
                <div class="space-y-6">
                    <h4 class="text-lg font-semibold text-gray-800">Replies</h4>
                    
                    @forelse($ticket->replies as $reply)
                        <div class="bg-gray-50 rounded-lg p-4 {{ $reply->user_id === auth()->id() ? 'ml-8' : 'mr-8' }}">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <span class="font-medium text-gray-800">
                                        {{ $reply->user->name }}
                                        @if($reply->user_id === auth()->id())
                                            (You)
                                        @endif
                                    </span>
                                    <span class="mx-2 text-gray-400">•</span>
                                    <span class="text-sm text-gray-500">{{ $reply->created_at->format('M d, Y H:i') }}</span>
                                </div>
                            </div>
                            <div class="prose max-w-none">
                                {{ $reply->message }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <p class="text-gray-600">No replies yet.</p>
                        </div>
                    @endforelse

                    <!-- Reply Form -->
                    @if($ticket->status !== 'closed')
                        <div class="mt-8">
                            <form action="{{ route('user.tickets.reply', $ticket) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Your Reply</label>
                                    <textarea
                                        name="message"
                                        id="message"
                                        rows="4"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                        placeholder="Type your reply here..."
                                        required
                                    ></textarea>
                                    @error('message')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="inline-flex items-center bg-primary text-white px-5 py-2.5 rounded-lg hover:bg-opacity-90 transition-colors shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                        Send Reply
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <p class="text-gray-600">This ticket is closed. You cannot reply to it anymore.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 