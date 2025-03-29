// staff/dashboard.blade.php
@extends('layouts.app')

@section('title', 'Staff Dashboard - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Staff Dashboard</h1>
                <p class="text-gray-600 mt-2">Manage bookings and client requests</p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-primary hover:underline">Back to User Dashboard</a>
        </div>
        
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 rounded-full p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-lg font-semibold text-gray-800">Manage Bookings</h2>
                        <p class="text-gray-600 mt-1">View and manage all client bookings</p>
                    </div>
                </div>
                <a href="{{ route('staff.bookings.index') }}" class="mt-4 inline-block text-sm text-green-600 hover:underline">View all bookings →</a>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-purple-100 rounded-full p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-lg font-semibold text-gray-800">Schedule Calendar</h2>
                        <p class="text-gray-600 mt-1">View upcoming events calendar</p>
                    </div>
                </div>
                <a href="#" class="mt-4 inline-block text-sm text-purple-600 hover:underline">Open calendar →</a>
            </div>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Recent Booking Requests</h2>
            </div>
            <div class="p-6">
                <div class="text-center text-gray-500 py-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="mt-4">There are no booking requests yet.</p>
                </div>
            </div>
        </div>
        <!-- Add this to the staff dashboard to include a link to requests -->

<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center">
        <div class="bg-amber-100 rounded-full p-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
        <div class="ml-4">
            <h2 class="text-lg font-semibold text-gray-800">Booking Requests</h2>
            <p class="text-gray-600 mt-1">View and manage customer booking requests</p>
        </div>
    </div>
    <a href="{{ route('staff.requests.index') }}" class="mt-4 inline-block text-sm text-amber-600 hover:underline">Manage booking requests →</a>
</div>
    </div>
</div>
@endsection