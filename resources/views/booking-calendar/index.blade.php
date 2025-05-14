@extends('layouts.app')

@section('content')
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 text-center">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Booking Calendar</h1>
                <p class="text-gray-600 max-w-2xl mx-auto">Check availability and existing bookings for all venues</p>
            </div>
            
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="p-6 md:p-8">
                    <!-- Calendar Controls -->
                    <div class="mb-8">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="w-full md:w-64">
                                <label for="venue-filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Venue</label>
                                <select id="venue-filter" class="w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                    <option value="">All Venues</option>
                                    @foreach($venues as $venue)
                                        <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="flex items-center gap-6">
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center">
                                        <span class="inline-block w-4 h-4 bg-green-500 rounded-full mr-2"></span>
                                        <span class="text-sm font-medium">Wedding</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="inline-block w-4 h-4 bg-orange-500 rounded-full mr-2"></span>
                                        <span class="text-sm font-medium">Reservation</span>
                                    </div>
                                </div>
                                
                                <button id="today-button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                    Today
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div id="calendar" class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal for displaying event details -->
    <div id="eventModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 m-4 max-w-md w-full">
            <div class="flex justify-between items-center mb-4 border-b pb-3">
                <h3 class="text-xl font-bold" id="eventTitle">Booking Details</h3>
                <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="eventDetails" class="space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div class="text-gray-600 font-medium">Date:</div>
                    <div id="eventDate" class="text-gray-800"></div>
                    
                    <div class="text-gray-600 font-medium">Session:</div>
                    <div id="eventSession" class="text-gray-800"></div>
                    
                    <div class="text-gray-600 font-medium">Venue:</div>
                    <div id="eventVenue" class="text-gray-800"></div>
                    
                    <div class="text-gray-600 font-medium">Type:</div>
                    <div id="eventType" class="text-gray-800"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />

<style>
    /* Custom FullCalendar styling */
    .fc-theme-standard td {
        border-color: #e5e7eb;
    }
    .fc .fc-daygrid-day-top {
        justify-content: center;
        padding-top: 8px;
    }
    .fc .fc-col-header-cell-cushion {
        padding: 10px 0;
        font-weight: 600;
    }
    .fc .fc-day-today {
        background-color: rgba(243, 244, 246, 0.5) !important;
    }
    .fc .fc-toolbar-title {
        font-size: 1.5rem;
        font-weight: bold;
    }
    .fc-daygrid-day-events {
        padding: 2px;
    }
    .fc-event {
        border-radius: 4px;
        font-weight: 500;
        padding: 2px 4px;
        border: none !important;
    }
    .fc-button {
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        font-weight: 500 !important;
    }
    .fc-button-primary {
        background-color: #fff !important;
        color: #4b5563 !important;
        border-color: #e5e7eb !important;
    }
    .fc-button-primary:hover {
        background-color: #f9fafb !important;
        color: #111827 !important;
    }
    .fc-button-primary:not(:disabled).fc-button-active,
    .fc-button-primary:not(:disabled):active {
        background-color: #f3f4f6 !important;
        color: #111827 !important;
        border-color: #d1d5db !important;
    }
    .fc-button-primary:focus {
        box-shadow: 0 0 0 3px rgba(209, 213, 219, 0.5) !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const modal = document.getElementById('eventModal');
        const closeModalBtn = document.getElementById('closeModal');
        const venueFilter = document.getElementById('venue-filter');
        const todayButton = document.getElementById('today-button');
        
        let calendar;
        
        // Initialize the calendar
        function initCalendar() {
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                aspectRatio: 1.8,
                firstDay: 1, // Start week on Monday
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'dayGridMonth,listMonth'
                },
                buttonText: {
                    today: 'Today',
                    month: 'Month',
                    list: 'List'
                },
                views: {
                    dayGridMonth: {
                        dayMaxEventRows: 2,
                    }
                },
                dayMaxEvents: true, // Allow "more" link when too many events
                eventClick: function(info) {
                    showEventDetails(info.event);
                },
                events: function(info, successCallback, failureCallback) {
                    // Fetch events from API
                    const venueId = venueFilter.value;
                    const params = new URLSearchParams({
                        start: info.startStr,
                        end: info.endStr
                    });
                    
                    if (venueId) {
                        params.append('venue_id', venueId);
                    }
                    
                    fetch(`/api/calendar-data?${params.toString()}`)
                        .then(response => response.json())
                        .then(data => {
                            successCallback(data);
                        })
                        .catch(error => {
                            console.error('Error fetching calendar data:', error);
                            failureCallback(error);
                        });
                }
            });
            
            calendar.render();
        }
        
        // Show event details in the modal
        function showEventDetails(event) {
            document.getElementById('eventTitle').textContent = 'Booking: ' + event.extendedProps.type;
            document.getElementById('eventDate').textContent = new Date(event.start).toLocaleDateString();
            document.getElementById('eventSession').textContent = event.extendedProps.session;
            document.getElementById('eventVenue').textContent = event.extendedProps.venue;
            document.getElementById('eventType').textContent = event.extendedProps.type;
            
            modal.classList.remove('hidden');
        }
        
        // Close modal
        closeModalBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
        });
        
        // Handle venue filter change
        venueFilter.addEventListener('change', function() {
            calendar.refetchEvents();
        });

        // Today button click
        todayButton.addEventListener('click', function() {
            calendar.today();
        });
        
        // Initialize the calendar
        initCalendar();
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script>
@endpush 