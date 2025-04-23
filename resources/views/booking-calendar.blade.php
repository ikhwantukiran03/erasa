@extends('layouts.app')

@section('title', 'Booking Calendar - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Booking Calendar</h1>
                <p class="text-gray-600 mt-2">View venue availability and booked dates</p>
            </div>
            <div>
                <a href="{{ route('dashboard') }}" class="text-primary hover:underline">Back to Dashboard</a>
            </div>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Venue Availability</h2>
                
                <!-- Venue Filter Dropdown -->
                <div class="flex space-x-4">
                    <div>
                        <select id="venue-filter" class="form-input py-2 px-3 rounded border-gray-300 text-sm">
                            <option value="">All Venues</option>
                            <!-- Venues will be loaded via JavaScript -->
                        </select>
                    </div>

                    <a href="{{ route('booking-requests.create') }}" class="bg-primary text-white px-4 py-2 rounded text-sm hover:bg-opacity-90 transition">Create New Booking</a>
                </div>
            </div>
            
            <!-- Month Navigation -->
            <div class="px-6 py-3 bg-gray-50 flex justify-between items-center">
                <div class="flex space-x-4 items-center">
                    <button id="prev-month" class="text-gray-700 hover:text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <h3 id="current-month-year" class="text-lg font-semibold text-gray-800">Loading...</h3>
                    <button id="next-month" class="text-gray-700 hover:text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
                
                <button id="today-button" class="text-sm text-gray-600 hover:text-primary">Today</button>
            </div>
            
            <!-- Legend -->
            <div class="px-6 py-2 bg-gray-50 border-t border-gray-200 flex space-x-6">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-400 rounded mr-2"></div>
                    <span class="text-sm text-gray-600">Morning Session Booked</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-400 rounded mr-2"></div>
                    <span class="text-sm text-gray-600">Evening Session Booked</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-purple-400 rounded mr-2"></div>
                    <span class="text-sm text-gray-600">Both Sessions Booked</span>
                </div>
            </div>
            
            <!-- Calendar -->
            <div class="p-6">
                <div id="calendar-loading" class="py-8 text-center text-gray-500">
                    <svg class="animate-spin h-8 w-8 mx-auto text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="mt-2">Loading calendar...</p>
                </div>
                
                <div id="calendar-grid" class="grid grid-cols-7 gap-px bg-gray-200 hidden">
                    <!-- Calendar Headers (Days of Week) -->
                    <div class="bg-gray-50 py-2 text-center text-sm font-medium text-gray-700">Sun</div>
                    <div class="bg-gray-50 py-2 text-center text-sm font-medium text-gray-700">Mon</div>
                    <div class="bg-gray-50 py-2 text-center text-sm font-medium text-gray-700">Tue</div>
                    <div class="bg-gray-50 py-2 text-center text-sm font-medium text-gray-700">Wed</div>
                    <div class="bg-gray-50 py-2 text-center text-sm font-medium text-gray-700">Thu</div>
                    <div class="bg-gray-50 py-2 text-center text-sm font-medium text-gray-700">Fri</div>
                    <div class="bg-gray-50 py-2 text-center text-sm font-medium text-gray-700">Sat</div>
                    
                    <!-- Calendar Days will be added here via JavaScript -->
                </div>
            </div>
        </div>
        
        <!-- Upcoming Bookings Section -->
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Upcoming Bookings</h2>
            </div>
            
            <div class="p-6">
                <div id="bookings-loading" class="py-8 text-center text-gray-500">
                    <svg class="animate-spin h-8 w-8 mx-auto text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="mt-2">Loading bookings...</p>
                </div>
                
                <div id="upcoming-bookings-table" class="hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Venue</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="upcoming-bookings-body" class="bg-white divide-y divide-gray-200">
                                <!-- Upcoming bookings will be added here via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div id="no-bookings-message" class="text-center text-gray-500 py-8 hidden">
                    <p>No upcoming bookings found.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Current state
        let currentMonth = new Date().getMonth() + 1; // JavaScript months are 0-indexed
        let currentYear = new Date().getFullYear();
        let selectedVenueId = '';
        
        // Initialize the calendar
        loadVenues();
        loadCalendarData();
        loadUpcomingBookings();
        
        // Event listeners
        document.getElementById('venue-filter').addEventListener('change', function() {
            selectedVenueId = this.value;
            loadCalendarData();
            loadUpcomingBookings();
        });
        
        document.getElementById('prev-month').addEventListener('click', function() {
            if (currentMonth === 1) {
                currentMonth = 12;
                currentYear--;
            } else {
                currentMonth--;
            }
            loadCalendarData();
        });
        
        document.getElementById('next-month').addEventListener('click', function() {
            if (currentMonth === 12) {
                currentMonth = 1;
                currentYear++;
            } else {
                currentMonth++;
            }
            loadCalendarData();
        });
        
        document.getElementById('today-button').addEventListener('click', function() {
            currentMonth = new Date().getMonth() + 1;
            currentYear = new Date().getFullYear();
            loadCalendarData();
        });
        
        // Functions to load data from API
        function loadVenues() {
            fetch('/api/venues')
                .then(response => response.json())
                .then(venues => {
                    const venueFilter = document.getElementById('venue-filter');
                    
                    venues.forEach(venue => {
                        const option = document.createElement('option');
                        option.value = venue.id;
                        option.textContent = venue.name;
                        venueFilter.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading venues:', error));
        }
        
        function loadCalendarData() {
            // Show loading indicator
            document.getElementById('calendar-loading').classList.remove('hidden');
            document.getElementById('calendar-grid').classList.add('hidden');
            
            // Build API URL with query parameters
            const url = new URL('/api/calendar-data', window.location.origin);
            url.searchParams.append('month', currentMonth);
            url.searchParams.append('year', currentYear);
            if (selectedVenueId) {
                url.searchParams.append('venue_id', selectedVenueId);
            }
            
            // Fetch data from API
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    // Update month/year display
                    document.getElementById('current-month-year').textContent = `${data.meta.currentMonth} ${data.meta.currentYear}`;
                    
                    // Clear existing calendar days (except the header row)
                    const calendarGrid = document.getElementById('calendar-grid');
                    while (calendarGrid.children.length > 7) {
                        calendarGrid.removeChild(calendarGrid.lastChild);
                    }
                    
                    // Add calendar days
                    data.days.forEach(day => {
                        const dayElement = createCalendarDay(day);
                        calendarGrid.appendChild(dayElement);
                    });
                    
                    // Hide loading indicator and show calendar
                    document.getElementById('calendar-loading').classList.add('hidden');
                    document.getElementById('calendar-grid').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error loading calendar data:', error);
                    document.getElementById('calendar-loading').textContent = 'Error loading calendar data. Please try again.';
                });
        }
        
        function loadUpcomingBookings() {
            // Show loading indicator
            document.getElementById('bookings-loading').classList.remove('hidden');
            document.getElementById('upcoming-bookings-table').classList.add('hidden');
            document.getElementById('no-bookings-message').classList.add('hidden');
            
            // Build API URL with query parameters
            const url = new URL('/api/upcoming-bookings', window.location.origin);
            if (selectedVenueId) {
                url.searchParams.append('venue_id', selectedVenueId);
            }
            
            // Fetch data from API
            fetch(url)
                .then(response => response.json())
                .then(bookings => {
                    if (bookings.length === 0) {
                        // Show no bookings message
                        document.getElementById('bookings-loading').classList.add('hidden');
                        document.getElementById('no-bookings-message').classList.remove('hidden');
                        return;
                    }
                    
                    // Clear existing rows
                    const tableBody = document.getElementById('upcoming-bookings-body');
                    tableBody.innerHTML = '';
                    
                    // Add booking rows
                    bookings.forEach(booking => {
                        const row = createBookingRow(booking);
                        tableBody.appendChild(row);
                    });
                    
                    // Hide loading indicator and show table
                    document.getElementById('bookings-loading').classList.add('hidden');
                    document.getElementById('upcoming-bookings-table').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error loading upcoming bookings:', error);
                    document.getElementById('bookings-loading').textContent = 'Error loading booking data. Please try again.';
                });
        }
        
        // Helper functions to create HTML elements
        function createCalendarDay(day) {
            const dayElement = document.createElement('div');
            dayElement.className = `min-h-[120px] bg-white p-1 relative ${day.isToday ? 'ring-2 ring-primary ring-inset' : ''}`;
            
            // Date number
            const dateNumber = document.createElement('div');
            dateNumber.className = 'text-right mb-2';
            
            const dateSpan = document.createElement('span');
            if (day.isCurrentMonth) {
                dateSpan.className = `inline-block px-2 py-1 text-sm ${day.isToday ? 'font-bold text-primary' : 'text-gray-700'}`;
            } else {
                dateSpan.className = 'inline-block px-2 py-1 text-sm text-gray-400';
            }
            dateSpan.textContent = day.day;
            
            dateNumber.appendChild(dateSpan);
            dayElement.appendChild(dateNumber);
            
            // Booking indicators
            if (day.isCurrentMonth) {
                if (day.bookings.morning && day.bookings.evening) {
                    // Both sessions booked
                    const overlay = document.createElement('div');
                    overlay.className = 'absolute inset-0 bg-purple-400 bg-opacity-20 pointer-events-none';
                    dayElement.appendChild(overlay);
                    
                    const bookingInfo = document.createElement('div');
                    bookingInfo.className = 'text-xs p-1';
                    
                    if (day.bookingDetails.morning) {
                        const morningInfo = document.createElement('div');
                        morningInfo.className = 'mb-1 bg-yellow-400 text-yellow-900 px-2 py-1 rounded text-xs overflow-hidden text-ellipsis';
                        morningInfo.textContent = `Morning: ${day.bookingDetails.morning.venue.name}`;
                        bookingInfo.appendChild(morningInfo);
                    }
                    
                    if (day.bookingDetails.evening) {
                        const eveningInfo = document.createElement('div');
                        eveningInfo.className = 'bg-blue-400 text-blue-900 px-2 py-1 rounded text-xs overflow-hidden text-ellipsis';
                        eveningInfo.textContent = `Evening: ${day.bookingDetails.evening.venue.name}`;
                        bookingInfo.appendChild(eveningInfo);
                    }
                    
                    dayElement.appendChild(bookingInfo);
                } else if (day.bookings.morning) {
                    // Morning session booked
                    const overlay = document.createElement('div');
                    overlay.className = 'absolute inset-0 bg-yellow-400 bg-opacity-20 pointer-events-none';
                    dayElement.appendChild(overlay);
                    
                    const bookingInfo = document.createElement('div');
                    bookingInfo.className = 'text-xs p-1';
                    
                    const morningInfo = document.createElement('div');
                    morningInfo.className = 'bg-yellow-400 text-yellow-900 px-2 py-1 rounded text-xs overflow-hidden text-ellipsis';
                    morningInfo.textContent = `Morning: ${day.bookingDetails.morning.venue.name}`;
                    bookingInfo.appendChild(morningInfo);
                    
                    dayElement.appendChild(bookingInfo);
                } else if (day.bookings.evening) {
                    // Evening session booked
                    const overlay = document.createElement('div');
                    overlay.className = 'absolute inset-0 bg-blue-400 bg-opacity-20 pointer-events-none';
                    dayElement.appendChild(overlay);
                    
                    const bookingInfo = document.createElement('div');
                    bookingInfo.className = 'text-xs p-1';
                    
                    const eveningInfo = document.createElement('div');
                    eveningInfo.className = 'bg-blue-400 text-blue-900 px-2 py-1 rounded text-xs overflow-hidden text-ellipsis';
                    eveningInfo.textContent = `Evening: ${day.bookingDetails.evening.venue.name}`;
                    bookingInfo.appendChild(eveningInfo);
                    
                    dayElement.appendChild(bookingInfo);
                }
            }
            
            return dayElement;
        }
        
        function createBookingRow(booking) {
            const row = document.createElement('tr');
            
            // Date column
            const dateCell = document.createElement('td');
            dateCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-900';
            dateCell.textContent = booking.formattedDate;
            row.appendChild(dateCell);
            
            // Session column
            const sessionCell = document.createElement('td');
            sessionCell.className = 'px-6 py-4 whitespace-nowrap';
            
            const sessionSpan = document.createElement('span');
            sessionSpan.className = `px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                booking.session === 'morning' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800'
            }`;
            sessionSpan.textContent = booking.session.charAt(0).toUpperCase() + booking.session.slice(1);
            sessionCell.appendChild(sessionSpan);
            row.appendChild(sessionCell);
            
            // Venue column
            const venueCell = document.createElement('td');
            venueCell.className = 'px-6 py-4 whitespace-nowrap';
            
            const venueDiv = document.createElement('div');
            venueDiv.className = 'text-sm text-gray-900';
            venueDiv.textContent = booking.venue.name;
            venueCell.appendChild(venueDiv);
            row.appendChild(venueCell);
            
            // Customer column
            const customerCell = document.createElement('td');
            customerCell.className = 'px-6 py-4 whitespace-nowrap';
            
            const customerNameDiv = document.createElement('div');
            customerNameDiv.className = 'text-sm text-gray-900';
            customerNameDiv.textContent = booking.customer.name;
            customerCell.appendChild(customerNameDiv);
            
            const customerEmailDiv = document.createElement('div');
            customerEmailDiv.className = 'text-xs text-gray-500';
            customerEmailDiv.textContent = booking.customer.email;
            customerCell.appendChild(customerEmailDiv);
            
            row.appendChild(customerCell);
            
            // Type column
            const typeCell = document.createElement('td');
            typeCell.className = 'px-6 py-4 whitespace-nowrap';
            
            const typeSpan = document.createElement('span');
            typeSpan.className = 'text-sm text-gray-900 capitalize';
            typeSpan.textContent = booking.type;
            typeCell.appendChild(typeSpan);
            row.appendChild(typeCell);
            
            // Actions column (for staff/admin)
            const isStaffOrAdmin = {{ auth()->user()->isAdmin() || auth()->user()->isStaff() ? 'true' : 'false' }};
            if (isStaffOrAdmin) {
                const actionsCell = document.createElement('td');
                actionsCell.className = 'px-6 py-4 whitespace-nowrap text-sm font-medium';
                
                const viewLink = document.createElement('a');
                viewLink.href = `/staff/bookings/${booking.id}`;
                viewLink.className = 'text-indigo-600 hover:text-indigo-900';
                viewLink.textContent = 'View';
                actionsCell.appendChild(viewLink);
                
                row.appendChild(actionsCell);
            }
            
            return row;
        }
    });
</script>
@endpush
@endsection