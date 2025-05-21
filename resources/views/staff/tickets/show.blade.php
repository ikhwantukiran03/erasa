@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('staff.tickets.index') }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Tickets
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Ticket Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Ticket #{{ $ticket->id }}</h1>
                    <p class="mt-1 text-sm text-gray-600">Created by {{ $ticket->user->name }} on {{ $ticket->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="px-3 py-1 text-sm font-semibold rounded-full 
                        @if($ticket->status === 'open') bg-yellow-100 text-yellow-800
                        @elseif($ticket->status === 'in_progress') bg-blue-100 text-blue-800
                        @else bg-green-100 text-green-800
                        @endif">
                        {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                    </span>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ ucfirst($ticket->category) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Ticket Content -->
        <div class="px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">{{ $ticket->title }}</h2>
            <div class="prose max-w-none">
                {{ $ticket->description }}
            </div>
        </div>

        <!-- Status Update Form -->
        <div class="px-6 py-4 border-t border-gray-200">
            <form action="{{ route('staff.tickets.update-status', $ticket) }}" method="POST" class="flex items-center space-x-4">
                @csrf
                <label for="status" class="text-sm font-medium text-gray-700">Update Status:</label>
                <select name="status" id="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Status
                </button>
            </form>
        </div>

        <!-- Replies Section -->
        <div class="px-6 py-4 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Replies</h3>
            
            <div class="space-y-6">
                @forelse($ticket->replies as $reply)
                    <div class="flex space-x-4">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-500 font-medium">{{ substr($reply->user->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $reply->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $reply->created_at->format('M d, Y H:i') }}</p>
                                </div>
                                @if($reply->is_staff_reply)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">Staff</span>
                                @endif
                            </div>
                            <div class="mt-2 text-sm text-gray-700">
                                {{ $reply->message }}
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No replies yet.</p>
                @endforelse
            </div>

            <!-- Reply Form -->
            @if($ticket->status !== 'closed')
                <form action="{{ route('staff.tickets.reply', $ticket) }}" method="POST" class="mt-6">
                    @csrf
                    <div class="mb-4">
                        <label for="message" class="block text-sm font-medium text-gray-700">Add Reply</label>
                        <textarea name="message" id="message" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Send Reply
                        </button>
                    </div>
                </form>
            @else
                <div class="mt-6 p-4 bg-gray-50 rounded-md text-center">
                    <p class="text-gray-500">This ticket is closed and cannot receive new replies.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 