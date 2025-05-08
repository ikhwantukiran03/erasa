@extends('layouts.app')

@section('title', 'Booking Calendar - Enak Rasa Wedding Hall')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-display font-bold text-primary mb-2">Booking Calendar</h1>
        <p class="text-gray-600">Check available dates for your wedding at Enak Rasa Wedding Hall</p>
    </div>
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <div class="flex items-center mb-4 md:mb-0">
            <button id="prev-month" class="bg-secondary px-4 py-2 rounded hover:bg-primary hover:text-white transition">&lt; Previous</button>
            <div id="current-month" class="text-xl font-semibold mx-4 min-w-[200px] text-center">May 2025</div>
            <button id="next-month" class="bg-secondary px-4 py-2 rounded hover:bg-primary hover:text-white transition">Next &gt;</button>
        </div>
        
        <div class="flex items-center">
            <label for="venue-select" class="mr-2">Venue:</label>
            <select id="venue-select" class="px-3 py-2 border border-gray-300 rounded bg-white">
                <option value="">All Venues</option>
                <!-- Venues will be populated dynamically -->
            </select>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="grid grid-cols-7 bg-primary text-white font-semibold">
            <div class="p-4 text-center">Sunday</div>
            <div class="p-4 text-center">Monday</div>
            <div class="p-4 text-center">Tuesday</div>
            <div class="p-4 text-center">Wednesday</div>
            <div class="p-4 text-center">Thursday</div>
            <div class="p-4 text-center">Friday</div>
            <div class="p-4 text-center">Saturday</div>
        </div>
        <div id="calendar-body" class="grid grid-cols-7">
            <!-- Calendar days will be generated dynamically -->
        </div>
    </div>
    
    <div class="mt-12">
        <h2 class="text-2xl font-display font-bold text-primary mb-6">Upcoming Bookings</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-secondary">
                        <th class="py-3 px-4 text-left font-semibold">Date</th>
                        <th class="py-3 px-4 text-left font-semibold">Session</th>
                        <th class="py-3 px-4 text-left font-semibold">Venue</th>
                    </tr>
                </thead>
                <tbody id="upcoming-bookings-body">
                    <!-- Upcoming bookings will be populated dynamically -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/calendar.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/calendar.js') }}"></script>
@endpush
