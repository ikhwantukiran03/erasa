@extends('layouts.app')

@section('title', 'Feedback Details - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-display font-bold text-dark">Feedback Details</h1>
                    <p class="text-gray-600 mt-2">Review and manage customer feedback</p>
                </div>
                <a href="{{ route('staff.feedback.index') }}" class="text-primary hover:underline">← Back to Feedback List</a>
            </div>

            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Customer Information -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Customer Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Customer Name</p>
                                <p class="text-lg font-medium text-gray-900">{{ $feedback->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="text-lg font-medium text-gray-900">{{ $feedback->user->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">WhatsApp</p>
                                <p class="text-lg font-medium text-gray-900">{{ $feedback->user->whatsapp }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Booking Date</p>
                                <p class="text-lg font-medium text-gray-900">{{ $feedback->booking->booking_date->format('F d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Feedback Details -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Feedback Details</h2>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="mb-4">
                                <p class="text-sm text-gray-500 mb-2">Rating</p>
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-8 h-8 {{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-2">Comment</p>
                                <p class="text-gray-900">{{ $feedback->comment }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status Management -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Status Management</h2>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="mb-4">
                                <p class="text-sm text-gray-500 mb-2">Current Status</p>
                                @if($feedback->status === 'pending')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @elseif($feedback->status === 'published')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Published
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Rejected
                                    </span>
                                @endif
                            </div>

                            @if($feedback->status === 'pending')
                                <form action="{{ route('staff.feedback.update', $feedback) }}" method="POST" class="space-y-4">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                                        <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                            <option value="published">Publish Feedback</option>
                                            <option value="rejected">Reject Feedback</option>
                                        </select>
                                    </div>


                    <!-- Additional Information -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Additional Information</h2>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Submitted On</p>
                                    <p class="text-gray-900">{{ $feedback->created_at->format('F d, Y H:i') }}</p>
                                </div>
                                @if($feedback->published_at)
                                    <div>
                                        <p class="text-sm text-gray-500">Published On</p>
                                        <p class="text-gray-900">{{ $feedback->published_at->format('F d, Y H:i') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 