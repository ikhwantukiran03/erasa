@extends('layouts.app')

@section('title', $ticket->title)

@section('content')
<div class="bg-gradient-to-r from-primary/10 to-secondary/20 py-10">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div data-aos="fade-up">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('user.tickets.index') }}" class="text-primary hover:underline">
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
                    <form action="{{ route('user.tickets.update-status', $ticket) }}" method="POST" class="flex items-center space-x-4">
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
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 h-10 w-10 bg-primary bg-opacity-20 rounded-full flex items-center justify-center">
                                    <span class="text-primary font-semibold">{{ $ticket->user ? substr($ticket->user->name, 0, 1) : 'U' }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $ticket->user ? $ticket->user->name : 'User not found' }}</p>
                                    <p class="text-sm text-gray-500">{{ $ticket->user ? $ticket->user->email : 'Email not available' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900">{{ $ticket->user ? $ticket->user->name : 'User not found' }}</p>
                                <p class="text-sm text-gray-500">{{ $ticket->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div class="mt-2 text-sm text-gray-700 prose prose-sm max-w-none">
                                {{ $ticket->description }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Interface -->
            <div class="space-y-4">
                @foreach($ticket->replies as $reply)
                    <div class="flex {{ $reply->is_staff_reply ? 'justify-start' : 'justify-end' }}">
                        <div class="flex items-end space-x-2 max-w-[80%]">
                            @if($reply->is_staff_reply)
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-blue-600 font-semibold text-sm">
                                            {{ substr($reply->user->name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                            <div class="flex flex-col {{ $reply->is_staff_reply ? 'items-start' : 'items-end' }}">
                                <div class="flex items-center space-x-2 mb-1">
                                    <p class="text-xs font-medium {{ $reply->is_staff_reply ? 'text-blue-600' : 'text-primary' }}">
                                        {{ $reply->user ? $reply->user->name : 'User not found' }}
                                            @if($reply->is_staff_reply)
                                            <span class="text-xs text-gray-500">(Staff)</span>
                                            @endif
                                    </p>
                                    <span class="text-xs text-gray-500">{{ $reply->created_at->format('H:i') }}</span>
                                </div>
                                <div class="rounded-2xl px-4 py-2 {{ $reply->is_staff_reply ? 'bg-gray-100 text-gray-800' : 'bg-primary text-white' }}">
                                    <p class="text-sm">{{ $reply->message }}</p>
                                        </div>
                                    </div>
                            @if(!$reply->is_staff_reply)
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center">
                                        <span class="text-primary font-semibold text-sm">
                                            {{ substr($reply->user->name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Reply Form -->
            @if($ticket->status !== 'closed')
                <div class="mt-6 bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                    <form action="{{ route('user.tickets.reply', $ticket) }}" method="POST" class="p-4">
                        @csrf
                        <div class="flex items-end space-x-4">
                            <div class="flex-1">
                                <textarea name="message" id="message" rows="1" 
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 @error('message') border-red-300 @enderror" 
                                    placeholder="Type your reply here...">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-primary text-white hover:bg-opacity-90 transition-colors shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
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