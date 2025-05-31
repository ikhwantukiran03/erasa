@extends('layouts.app')

@section('title', 'Edit Booking - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Edit Booking #{{ $booking->id }}</h1>
                <p class="text-gray-600 mt-2">Update booking information</p>
            </div>
            <a href="{{ route('staff.bookings.index') }}" class="text-primary hover:underline">Back to Bookings</a>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                @if($errors->any())
                    <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                        <p class="font-bold">Please fix the following errors:</p>
                        <ul class="list-disc ml-4 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('staff.bookings.update', $booking) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- User Selection -->
                        <div>
                            <label for="user_id" class="block text-dark font-medium mb-1">Customer <span class="text-red-500">*</span></label>
                            <select id="user_id" name="user_id" required class="form-input @error('user_id') border-red-500 @enderror">
                                <option value="">-- Select Customer --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $booking->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Venue Selection -->
                        <div>
                            <label for="venue_id" class="block text-dark font-medium mb-1">Venue <span class="text-red-500">*</span></label>
                            <select id="venue_id" name="venue_id" required class="form-input @error('venue_id') border-red-500 @enderror">
                                <option value="">-- Select Venue --</option>
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}" {{ old('venue_id', $booking->venue_id) == $venue->id ? 'selected' : '' }}>
                                        {{ $venue->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Package Selection (Optional) -->
                        <div>
                            <label for="package_id" class="block text-dark font-medium mb-1">Package</label>
                            <select id="package_id" name="package_id" class="form-input @error('package_id') border-red-500 @enderror">
                                <option value="">-- Select Package (Optional) --</option>
                                @foreach($packages as $package)
    <option 
        value="{{ $package->id }}" 
        data-venue-id="{{ $package->venue_id }}"
        {{ old('package_id') == $package->id ? 'selected' : '' }}
    >
        {{ $package->name }} ({{ $package->venue->name }})
    </option>
@endforeach

                            </select>
                            <p class="text-sm text-gray-500 mt-1">Available packages will be filtered based on selected venue</p>
                        </div>
                        
                        <!-- Price Selection (Number of Guests) -->
                        <div id="price-selection-container">
                            <label for="price_id" class="block text-dark font-medium mb-1">Number of Guests (Pax)</label>
                            <select id="price_id" name="price_id" class="form-input @error('price_id') border-red-500 @enderror">
                                <option value="">-- Select Number of Guests --</option>
                                <!-- Price options will be populated via JavaScript -->
                            </select>
                            <p class="text-sm text-gray-500 mt-1">Select the expected number of guests for this booking</p>
                        </div>
                        
                        <!-- Booking Date -->
                        <div>
                            <label for="booking_date" class="block text-dark font-medium mb-1">Booking Date <span class="text-red-500">*</span></label>
                            <input type="date" id="booking_date" name="booking_date" value="{{ old('booking_date', $booking->booking_date->format('Y-m-d')) }}" required class="form-input @error('booking_date') border-red-500 @enderror">
                            <p class="text-sm text-gray-500 mt-1" id="booking-date-help">Select the date for this booking</p>
                        </div>
                        
                        <!-- Session Selection -->
                        <div>
                            <label for="session" class="block text-dark font-medium mb-1">Session <span class="text-red-500">*</span></label>
                            <select id="session" name="session" required class="form-input @error('session') border-red-500 @enderror">
                                <option value="morning" {{ old('session', $booking->session) == 'morning' ? 'selected' : '' }}>Morning Session (11:00 AM - 4:00 PM)</option>
                                <option value="evening" {{ old('session', $booking->session) == 'evening' ? 'selected' : '' }}>Evening Session (7:00 PM - 11:00 PM)</option>
                            </select>
                            <p class="text-sm text-gray-500 mt-1">Morning: 11AM-4PM (5 hours) | Evening: 7PM-11PM (4 hours)</p>
                        </div>
                        
                        <!-- Booking Type -->
                        <div>
                            <label for="type" class="block text-dark font-medium mb-1">Booking Type <span class="text-red-500">*</span></label>
                            <select id="type" name="type" required class="form-input @error('type') border-red-500 @enderror">
                                <option value="wedding" {{ old('type', $booking->type) == 'wedding' ? 'selected' : '' }}>Wedding</option>
                                <option value="viewing" {{ old('type', $booking->type) == 'viewing' ? 'selected' : '' }}>Venue Viewing</option>
                                <option value="reservation" {{ old('type', $booking->type) == 'reservation' ? 'selected' : '' }}>Reservation</option>
                                <option value="other" {{ old('type', $booking->type) == 'appointment' ? 'selected' : '' }}>Appointment</option>
                            </select>
                        </div>
                        
                        <!-- Booking Status -->
                        <div>
                            <label for="status" class="block text-dark font-medium mb-1">Status <span class="text-red-500">*</span></label>
                            <select id="status" name="status" required class="form-input @error('status') border-red-500 @enderror">
                                <option value="waiting for deposit" {{ old('status', $booking->status) == 'waiting for deposit' ? 'selected' : '' }}>Waiting for Deposit</option>
                                <option value="pending_verification" {{ old('status', $booking->status) == 'pending_verification' ? 'selected' : '' }}>Pending Verification</option>
                                <option value="ongoing" {{ old('status', $booking->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ old('status', $booking->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $booking->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        
                        <!-- Expiry Date (Optional) -->
                        <div>
                            <label for="expiry_date" class="block text-dark font-medium mb-1">Expiry Date</label>
                            <input type="date" id="expiry_date" name="expiry_date" value="{{ old('expiry_date', $booking->expiry_date ? $booking->expiry_date->format('Y-m-d') : '') }}" class="form-input @error('expiry_date') border-red-500 @enderror">
                            <p class="text-sm text-gray-500 mt-1">Optional: Date when booking expires if not confirmed</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('staff.bookings.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition mr-2">
                            Cancel
                        </a>
                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                            Update Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const packagesData = {!! json_encode($packages->map(function($package) {
        return [
            'id' => $package->id,
            'venue_id' => $package->venue_id,
            'prices' => $package->prices->map(function($price) {
                return [
                    'id' => $price->id,
                    'pax' => $price->pax,
                    'price' => $price->price
                ];
            })
        ];
    })) !!};

    const venueSelect = document.getElementById('venue_id');
    const packageSelect = document.getElementById('package_id');
    const priceSelect = document.getElementById('price_id');
    const priceContainer = document.getElementById('price-selection-container');
    const packageOptions = Array.from(packageSelect.options);
    const typeSelect = document.getElementById('type');
    const bookingDateInput = document.getElementById('booking_date');
    const sessionSelect = document.getElementById('session');
    const dateHelpText = document.getElementById('booking-date-help');

    const oldPackageId = "{{ old('package_id') }}";
    const oldPriceId = "{{ old('price_id') }}";
    
    // Date validation function
    function updateDateValidation() {
        const selectedType = typeSelect.value;
        const today = new Date();
        let minDate;
        let helpText;
        
        if (selectedType === 'reservation' || selectedType === 'wedding') {
            // For reservations and wedding bookings, require at least 6 months advance booking
            minDate = new Date(today.getFullYear(), today.getMonth() + 6, today.getDate());
            helpText = 'Booking date must be at least 6 months from today for reservations and wedding bookings';
        } else {
            // For viewing, require at least 1 day advance booking
            minDate = new Date(today.getFullYear(), today.getMonth(), today.getDate() + 1);
            helpText = 'Select the date for this booking';
        }
        
        // Format date for input min attribute (YYYY-MM-DD)
        const formattedMinDate = minDate.toISOString().split('T')[0];
        bookingDateInput.setAttribute('min', formattedMinDate);
        dateHelpText.textContent = helpText;
        
        // Note: Don't clear existing date in edit mode to preserve current booking date
    }

    function filterPackages() {
        const venueId = venueSelect.value;
        packageSelect.innerHTML = '<option value="">-- Select Package (Optional) --</option>';

        packageOptions.forEach(option => {
            if (option.value && (!venueId || option.dataset.venueId === venueId)) {
                packageSelect.appendChild(option.cloneNode(true));
            }
        });

        if (oldPackageId) {
            Array.from(packageSelect.options).forEach(option => {
                if (option.value === oldPackageId) {
                    option.selected = true;
                }
            });
        }
    }

    function updatePriceOptions(packageId, selectedPriceId = null) {
        priceSelect.innerHTML = '<option value="">-- Select Number of Guests --</option>';

        const packageData = packagesData.find(pkg => pkg.id == packageId);

        if (packageData && packageData.prices.length > 0) {
            packageData.prices.forEach(price => {
                const option = document.createElement('option');
                option.value = price.id;
                option.textContent = `${price.pax} pax - RM ${parseFloat(price.price).toLocaleString('en-MY', {minimumFractionDigits: 2})}`;

                if (selectedPriceId && price.id == selectedPriceId) {
                    option.selected = true;
                }

                priceSelect.appendChild(option);
            });

            priceContainer.style.display = 'block';
        } else {
            priceContainer.style.display = 'none';
        }
    }

    function checkAvailability() {
        const date = bookingDateInput.value;
        const session = sessionSelect.value;
        const venueId = venueSelect.value;

        if (!date || !session || !venueId) return;

        fetch(`/api/check-availability?date=${date}&session=${session}&venue_id=${venueId}`)
            .then(response => response.json())
            .then(data => {
                if (!data.available) {
                    alert('This venue is already booked for the selected date and session.');
                }
            })
            .catch(error => {
                console.error('Availability check failed:', error);
            });
    }

    // Event Listeners
    venueSelect.addEventListener('change', () => {
        filterPackages();
        priceSelect.innerHTML = '<option value="">-- Select Number of Guests --</option>';
        priceContainer.style.display = 'none';
        checkAvailability();
    });

    packageSelect.addEventListener('change', function() {
        const packageId = this.value;
        if (packageId) {
            updatePriceOptions(packageId);
        } else {
            priceSelect.innerHTML = '<option value="">-- Select Number of Guests --</option>';
            priceContainer.style.display = 'none';
        }
    });

    bookingDateInput.addEventListener('change', checkAvailability);
    sessionSelect.addEventListener('change', checkAvailability);
    typeSelect.addEventListener('change', updateDateValidation);

    // On load
    filterPackages();
    if (oldPackageId) {
        updatePriceOptions(oldPackageId, oldPriceId);
    }
    updateDateValidation();
});
</script>
@endpush
@endsection