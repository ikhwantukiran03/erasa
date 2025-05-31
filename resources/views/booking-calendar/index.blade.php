@extends('layouts.app')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .fc {
            font-family: 'Inter', sans-serif;
        }
        .fc-header-toolbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
        }
        .fc-toolbar-title {
            color: white !important;
            font-weight: 600;
            font-size: 1.5rem;
        }
        .fc-button {
            background: rgba(255, 255, 255, 0.2) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            color: white !important;
            border-radius: 0.5rem !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .fc-button:hover {
            background: rgba(255, 255, 255, 0.3) !important;
            transform: translateY(-1px);
        }
        .fc-button-active {
            background: rgba(255, 255, 255, 0.4) !important;
        }
        .fc-daygrid-day:hover {
            background-color: #f8fafc;
            cursor: pointer;
        }
        .fc-daygrid-day.fc-day-future:hover {
            background-color: #e0f2fe;
            cursor: pointer;
        }
        .fc-daygrid-day.fc-day-future:hover .fc-daygrid-day-number {
            color: #0277bd;
            font-weight: 600;
        }
        .fc-daygrid-day.fc-day-past {
            background-color: #f5f5f5;
            cursor: not-allowed;
        }
        .fc-daygrid-day.fc-day-past .fc-daygrid-day-number {
            color: #9e9e9e;
        }
        .fc-event {
            border-radius: 0.375rem !important;
            border: none !important;
            padding: 2px 6px !important;
            font-size: 0.75rem;
            font-weight: 500;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .venue-filter-btn {
            transition: all 0.3s ease;
        }
        .venue-filter-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .stats-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        .stats-card.blue {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .stats-card.green {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
        .stats-card.purple {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Booking Calendar</h1>
                        <p class="text-gray-600 mt-1">Manage your venue bookings and availability</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button id="refreshBtn" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-sync-alt mr-2"></i>
                            Refresh
                        </button>
                        <a href="/booking-request" class="inline-flex items-center px-4 py-2 bg-orange-400 text-white rounded-lg hover:bg-orange-600 transition">
                            <i class="fas fa-plus mr-2"></i>
                            New Booking
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            

            <!-- Filter Section -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Filter Bookings</h3>
                        <div class="flex flex-wrap gap-2" id="venueFilters">
                            <button class="venue-filter-btn px-4 py-2 bg-gray-800 text-white rounded-full text-sm font-medium" data-venue="all">
                                All Venues
                            </button>
                            <!-- Venue filter buttons will be populated by JavaScript -->
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-600">Wedding</span>
                        </div>
                       
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-orange-500 rounded-full"></div>
                            <span class="text-sm text-gray-600">Reservation</span>
                        </div>
                        
                        <!-- Session Timing Info -->
                        <div class="border-l border-gray-300 pl-4">
                            <div class="text-xs text-gray-500 space-y-1">
                                <div><strong>Morning:</strong> 11AM-4PM</div>
                                <div><strong>Evening:</strong> 7PM-11PM</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar Container -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <!-- Calendar Instructions -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-blue-200 p-4">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-blue-800 text-sm font-medium">
                            💡 <strong>Tip:</strong> Click on any future date in the calendar to create a booking for that date. The booking form will open with your selected date pre-filled.
                        </p>
                    </div>
                </div>
                
                <div id="calendar" class="p-6"></div>
            </div>

            
        </div>
    </div>

    <!-- Booking Details Modal -->
    <div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl max-w-md w-full max-h-96 overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Booking Details</h3>
                        <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div id="modalContent">
                        <!-- Modal content will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let calendar;
        let currentVenueFilter = 'all';
        let venues = [];
        let bookings = [];

        document.addEventListener('DOMContentLoaded', function() {
            initializeCalendar();
            loadVenues();
            loadBookings();
            updateStats();

            // Event listeners
            document.getElementById('refreshBtn').addEventListener('click', function() {
                loadBookings();
                updateStats();
            });

            document.getElementById('closeModal').addEventListener('click', function() {
                document.getElementById('bookingModal').classList.add('hidden');
            });
            
            // Close modal when clicking outside
            document.getElementById('bookingModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        });

        function initializeCalendar() {
            const calendarEl = document.getElementById('calendar');
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
               
                buttonText:{
                    today: 'Today'
                },

                height: 'auto',
                events: function(info, successCallback, failureCallback) {
                    fetchCalendarData(info.start, info.end, successCallback, failureCallback);
                },
                eventClick: function(info) {
                    showBookingDetails(info.event);
                },
                dateClick: function(info) {
                    // Handle date click for booking request form redirection
                    const clickedDate = new Date(info.date);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    
                    if (clickedDate >= today) {
                        // Build URL with date and venue parameters
                        let url = '/booking-request?event_date=' + info.dateStr;
                        
                        // Add venue_id if a specific venue is filtered
                        if (currentVenueFilter !== 'all') {
                            url += '&venue_id=' + currentVenueFilter;
                        }
                        
                        // Redirect to booking request form
                        window.location.href = url;
                    } else {
                        alert('Cannot book dates in the past. Please select a future date.');
                    }
                },
                eventDisplay: 'block',
                dayMaxEvents: 3,
                moreLinkClick: 'popover',
                selectable: true,
                selectMirror: true
            });
            calendar.render();
        }

        function fetchCalendarData(start, end, successCallback, failureCallback) {
            const params = new URLSearchParams({
                start: start.toISOString().split('T')[0],
                end: end.toISOString().split('T')[0]
            });
            
            if (currentVenueFilter !== 'all') {
                params.append('venue_id', currentVenueFilter);
            }

            fetch(`/api/calendar-data?${params}`)
                .then(response => response.json())
                .then(data => {
                    bookings = data;
                    successCallback(data);
                })
                .catch(error => {
                    console.error('Error fetching calendar data:', error);
                    failureCallback(error);
                });
        }

        function loadVenues() {
            fetch('/api/venues')
                .then(response => response.json())
                .then(data => {
                    venues = data;
                    renderVenueFilters();
                })
                .catch(error => console.error('Error loading venues:', error));
        }

        function renderVenueFilters() {
            const container = document.getElementById('venueFilters');
            
            venues.forEach(venue => {
                const button = document.createElement('button');
                button.className = 'venue-filter-btn px-4 py-2 bg-gray-200 text-gray-800 rounded-full text-sm font-medium hover:bg-gray-300 transition';
                button.textContent = venue.name;
                button.dataset.venue = venue.id;
                
                button.addEventListener('click', function() {
                    // Update active state
                    document.querySelectorAll('.venue-filter-btn').forEach(btn => {
                        btn.className = 'venue-filter-btn px-4 py-2 bg-gray-200 text-gray-800 rounded-full text-sm font-medium hover:bg-gray-300 transition';
                    });
                    this.className = 'venue-filter-btn px-4 py-2 bg-gray-800 text-white rounded-full text-sm font-medium';
                    
                    currentVenueFilter = this.dataset.venue;
                    calendar.refetchEvents();
                    updateStats();
                });
                
                container.appendChild(button);
            });
        }

        function loadBookings() {
            calendar.refetchEvents();
        }

        function updateStats() {
            const today = new Date();
            const startOfWeek = new Date(today.getFullYear(), today.getMonth(), today.getDate() - today.getDay());
            const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);

            // These would be fetched from API in real implementation
            fetch('/api/calendar-data')
                .then(response => response.json())
                .then(data => {
                    const todayBookings = data.filter(booking => {
                        const bookingDate = new Date(booking.start);
                        return bookingDate.toDateString() === today.toDateString();
                    }).length;

                    const weekBookings = data.filter(booking => {
                        const bookingDate = new Date(booking.start);
                        return bookingDate >= startOfWeek && bookingDate <= today;
                    }).length;

                    const monthBookings = data.filter(booking => {
                        const bookingDate = new Date(booking.start);
                        return bookingDate >= startOfMonth && bookingDate <= today;
                    }).length;

                    ;
                    
                    document.getElementById('availableVenues').textContent = venues.length - todayBookings;
                });
        }

        

        function showBookingDetails(event) {
            const modalContent = document.getElementById('modalContent');
            const props = event.extendedProps;

            modalContent.innerHTML = `
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-4 h-4 rounded-full ${getBookingColor(props.type)}"></div>
                        <span class="font-medium text-lg">${event.title}</span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        
                        <div>
                            <span class="text-gray-500">Venue:</span>
                            <div class="font-medium">${props.venue}</div>
                        </div>
                        <div>
                            <span class="text-gray-500">Session:</span>
                            <div class="font-medium capitalize">${props.session}</div>
                        </div>
                        
                    </div>
                    
                    
                </div>
            `;

            document.getElementById('bookingModal').classList.remove('hidden');
        }

        function getBookingColor(type) {
            const colors = {
                'wedding': 'bg-green-500',
                'viewing': 'bg-blue-500',
                'reservation': 'bg-orange-500',
                'appointment': 'bg-purple-500'
            };
            return colors[type] || 'bg-gray-500';
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric',
                year: 'numeric'
            });
        }
    </script>
@endsection