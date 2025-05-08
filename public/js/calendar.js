document.addEventListener('DOMContentLoaded', function () {
    let currentMonth = new Date().getMonth() + 1;
    let currentYear = new Date().getFullYear();
    let selectedVenueId = '';

    loadVenues();
    loadCalendarData();
    loadUpcomingBookings();

    document.getElementById('prev-month').addEventListener('click', function () {
        if (currentMonth === 1) {
            currentMonth = 12;
            currentYear--;
        } else {
            currentMonth--;
        }
        loadCalendarData();
    });

    document.getElementById('next-month').addEventListener('click', function () {
        if (currentMonth === 12) {
            currentMonth = 1;
            currentYear++;
        } else {
            currentMonth++;
        }
        loadCalendarData();
    });

    document.getElementById('venue-select').addEventListener('change', function () {
        selectedVenueId = this.value;
        loadCalendarData();
        loadUpcomingBookings();
    });

    function loadVenues() {
        fetch('/api/venues')
            .then(response => response.json())
            .then(data => {
                const venueSelect = document.getElementById('venue-select');
                data.forEach(venue => {
                    const option = document.createElement('option');
                    option.value = venue.id;
                    option.textContent = venue.name;
                    venueSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error loading venues:', error));
    }

    function loadCalendarData() {
        let url = `/api/calendar-data?month=${currentMonth}&year=${currentYear}`;
        if (selectedVenueId) url += `&venue_id=${selectedVenueId}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                document.getElementById('current-month').textContent = `${data.meta.currentMonth} ${data.meta.currentYear}`;
                renderCalendar(data.days);
            })
            .catch(error => console.error('Error loading calendar data:', error));
    }

    function renderCalendar(days) {
        const calendarBody = document.getElementById('calendar-body');
        calendarBody.innerHTML = '';

        days.forEach(day => {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';
            if (!day.isCurrentMonth) dayElement.classList.add('outside-month');
            if (day.isToday) dayElement.classList.add('today');

            const dayNumber = document.createElement('div');
            dayNumber.className = 'day-number';
            dayNumber.textContent = day.day;
            dayElement.appendChild(dayNumber);

            const bookingStatus = document.createElement('div');
            bookingStatus.className = 'booking-status';

            const morningSession = document.createElement('div');
            morningSession.className = day.bookings.morning ? 'session booked' : 'session available';
            morningSession.textContent = day.bookings.morning ? 'Morning: Booked' : 'Morning: Available';
            bookingStatus.appendChild(morningSession);

            const eveningSession = document.createElement('div');
            eveningSession.className = day.bookings.evening ? 'session booked' : 'session available';
            eveningSession.textContent = day.bookings.evening ? 'Evening: Booked' : 'Evening: Available';
            bookingStatus.appendChild(eveningSession);

            dayElement.appendChild(bookingStatus);

            if (day.bookings.morning || day.bookings.evening) {
                const tooltip = document.createElement('div');
                tooltip.className = 'tooltip';
                let tooltipContent = '<strong>Booking Details:</strong><br>';
                if (day.bookings.morning && day.bookingDetails.morning) {
                    tooltipContent += `<p>Morning: ${day.bookingDetails.morning.venue.name}</p>`;
                }
                if (day.bookings.evening && day.bookingDetails.evening) {
                    tooltipContent += `<p>Evening: ${day.bookingDetails.evening.venue.name}</p>`;
                }
                tooltip.innerHTML = tooltipContent;
                dayElement.appendChild(tooltip);
            }

            calendarBody.appendChild(dayElement);
        });
    }

    function loadUpcomingBookings() {
        let url = '/api/upcoming-bookings';
        if (selectedVenueId) url += `?venue_id=${selectedVenueId}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                renderUpcomingBookings(data);
            })
            .catch(error => console.error('Error loading upcoming bookings:', error));
    }

    function renderUpcomingBookings(bookings) {
        const bookingsBody = document.getElementById('upcoming-bookings-body');
        bookingsBody.innerHTML = '';

        if (bookings.length === 0) {
            const row = document.createElement('tr');
            const cell = document.createElement('td');
            cell.colSpan = 3;
            cell.textContent = 'No upcoming bookings';
            cell.style.textAlign = 'center';
            cell.className = 'py-4';
            row.appendChild(cell);
            bookingsBody.appendChild(row);
            return;
        }

        bookings.forEach(booking => {
            const row = document.createElement('tr');
            row.className = 'border-b hover:bg-gray-50';

            const dateCell = document.createElement('td');
            dateCell.textContent = booking.formattedDate;
            dateCell.className = 'py-3 px-4';
            row.appendChild(dateCell);

            const sessionCell = document.createElement('td');
            sessionCell.textContent = booking.session === 'morning' ? 'Morning' : 'Evening';
            sessionCell.className = 'py-3 px-4';
            row.appendChild(sessionCell);

            const venueCell = document.createElement('td');
            venueCell.textContent = booking.venue.name;
            venueCell.className = 'py-3 px-4';
            row.appendChild(venueCell);

            bookingsBody.appendChild(row);
        });
    }
});
