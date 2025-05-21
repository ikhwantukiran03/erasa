@extends('layouts.app')

@section('title', $ticket->title)

@section('content')
<div class="bg-gradient-to-r from-primary/10 to-secondary/20 py-10">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div data-aos="fade-up">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('tickets.index') }}" class="text-gray-600 hover:text-gray-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <h1 class="text-3xl font-display font-bold text-primary">{{ $ticket->title }}</h1>
                </div>
                <div class="flex items-center space-x-4 mt-2">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $ticket->category }}
                    </span>
                    @if($ticket->status === 'open')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Open
                        </span>
                    @elseif($ticket->status === 'in_progress')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            In Progress
                        </span>
                    @elseif($ticket->status === 'solved')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            Solved
                        </span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                            Closed
                        </span>
                    @endif
                    <span class="text-sm text-gray-500">Created {{ $ticket->created_at->format('M d, Y H:i') }}</span>
                </div>
            </div>
            @if(Auth::user()->isStaff())
                <div class="mt-4 md:mt-0" data-aos="fade-up" data-aos-delay="100">
                    <form action="{{ route('tickets.update-status', $ticket) }}" method="POST" class="flex items-center space-x-4">
                        @csrf
                        <select name="status" onchange="this.form.submit()" 
                            class="rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20">
                            <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="solved" {{ $ticket->status === 'solved' ? 'selected' : '' }}>Solved</option>
                            <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="bg-gray-50 py-10">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Ticket Description -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 mb-6">
                <div class="p-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                                <span class="text-primary font-semibold">{{ substr($ticket->user->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900">{{ $ticket->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $ticket->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div class="mt-2 text-sm text-gray-700 prose prose-sm max-w-none">
                                {{ $ticket->description }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Replies -->
            <div class="space-y-6">
                @foreach($ticket->replies as $reply)
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                        <div class="p-6">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full {{ $reply->is_staff_reply ? 'bg-blue-100' : 'bg-primary/10' }} flex items-center justify-center">
                                        <span class="{{ $reply->is_staff_reply ? 'text-blue-600' : 'text-primary' }} font-semibold">
                                            {{ substr($reply->user->name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <p class="text-sm font-medium text-gray-900">{{ $reply->user->name }}</p>
                                            @if($reply->is_staff_reply)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    Staff
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-500">{{ $reply->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-700 prose prose-sm max-w-none">
                                        {{ $reply->message }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Reply Form -->
            @if($ticket->status !== 'closed')
                <div class="mt-6 bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                    <form action="{{ route('tickets.reply', $ticket) }}" method="POST" class="p-6">
                        @csrf
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700">Add a Reply</label>
                            <div class="mt-1">
                                <textarea name="message" id="message" rows="4" 
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 @error('message') border-red-300 @enderror" 
                                    placeholder="Type your reply here...">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
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
                <div class="mt-6 bg-gray-50 rounded-xl p-6 text-center">
                    <p class="text-gray-600">This ticket is closed and no longer accepting replies.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 