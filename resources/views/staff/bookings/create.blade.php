@extends('layouts.app')

@section('title', 'Create Booking - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Create New Booking</h1>
                <p class="text-gray-600 mt-2">Add a new wedding hall booking</p>
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

                <form action="{{ route('staff.bookings.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- User Selection -->
                        <div>
                            <label for="user_id" class="block text-dark font-medium mb-1">Customer <span class="text-red-500">*</span></label>
                            <select id="user_id" name="user_id" required class="form-input @error('user_id') border-red-500 @enderror">
                                <option value="">-- Select Customer --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-sm text-gray-500 mt-1">Select the customer who is making the booking</p>
                        </div>
                        
                        <!-- Venue Selection -->
                        <div>
                            <label for="venue_id" class="block text-dark font-medium mb-1">Venue <span class="text-red-500">*</span></label>
                            <select id="venue_id" name="venue_id" required class="form-input @error('venue_id') border-red-500 @enderror">
                                <option value="">-- Select Venue --</option>
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
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
                                    <option value="{{ $package->id }}" data-venue-id="{{ $package->venue_id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
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
                            <p class="text-sm text-gray-500 mt-1">Select the number of guests for this booking</p>
                        </div>
                        
                        <!-- Booking Date -->
                        <div>
                            <label for="booking_date" class="block text-dark font-medium mb-1">Booking Date <span class="text-red-500">*</span></label>
                            <input type="date" id="booking_date" name="booking_date" value="{{ old('booking_date') }}" required class="form-input @error('booking_date') border-red-500 @enderror" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        </div>
                        
                        <!-- Session Selection -->
                        <div>
                            <label for="session" class="block text-dark font-medium mb-1">Session <span class="text-red-500">*</span></label>
                            <select id="session" name="session" required class="form-input @error('session') border-red-500 @enderror">
                                <option value="morning" {{ old('session') == 'morning' ? 'selected' : '' }}>Morning</option>
                                <option value="evening" {{ old('session') == 'evening' ? 'selected' : '' }}>Evening</option>
                            </select>
                        </div>
                        
                        <!-- Booking Type -->
                        <div>
                            <label for="type" class="block text-dark font-medium mb-1">Booking Type <span class="text-red-500">*</span></label>
                            <select id="type" name="type" required class="form-input @error('type') border-red-500 @enderror">
                                <option value="wedding" {{ old('type') == 'wedding' ? 'selected' : '' }}>Wedding</option>
                                <option value="viewing" {{ old('type') == 'viewing' ? 'selected' : '' }}>Venue Viewing</option>
                                <option value="reservation" {{ old('type') == 'reservation' ? 'selected' : '' }}>Reservation</option>
                            </select>
                        </div>
                        
                        <!-- Booking Status -->
                        <div>
                            <label for="status" class="block text-dark font-medium mb-1">Status <span class="text-red-500">*</span></label>
                            <select id="status" name="status" required class="form-input @error('status') border-red-500 @enderror">
                                <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="waiting for deposit" {{ old('status') == 'waiting for deposit' ? 'selected' : '' }}>Waiting for Deposit</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        
                        <!-- Expiry Date (Optional) -->
                        <div>
                            <label for="expiry_date" class="block text-dark font-medium mb-1">Expiry Date</label>
                            <input type="date" id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}" class="form-input @error('expiry_date') border-red-500 @enderror">
                            <p class="text-sm text-gray-500 mt-1">Optional: Date when booking expires if not confirmed</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('staff.bookings.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition mr-2">
                            Cancel
                        </a>
                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                            Create Booking
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
        // Store package data with prices
        const packagesData = @json($packages->map(function($package) {
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
        }));
        
        const venueSelect = document.getElementById('venue_id');
        const packageSelect = document.getElementById('package_id');
        const priceSelect = document.getElementById('price_id');
        const priceContainer = document.getElementById('price-selection-container');
        const packageOptions = Array.from(packageSelect.options);
        
        // Initialize with any pre-selected values
        const oldPackageId = "{{ old('package_id') }}";
        const oldPriceId = "{{ old('price_id') }}";
        
        // Hide price selection initially if no package is selected
        if (!oldPackageId) {
            priceContainer.style.display = 'none';
        } else {
            filterPackages();
            updatePriceOptions(oldPackageId, oldPriceId);
        }
        
        // Function to filter packages based on selected venue
        function filterPackages() {
            const venueId = venueSelect.value;
            
            // Reset package select
            packageSelect.innerHTML = '<option value="">-- Select Package (Optional) --</option>';
            
            if (!venueId) {
                // If no venue selected, add all packages
                packageOptions.forEach(option => {
                    if (option.value) {
                        packageSelect.appendChild(option.cloneNode(true));
                    }
                });
            } else {
                // Filter packages for selected venue
                packageOptions.forEach(option => {
                    if (option.value && option.dataset.venueId === venueId) {
                        packageSelect.appendChild(option.cloneNode(true));
                    }
                });
            }
            
            // If the original package belongs to this venue, select it
            if (oldPackageId) {
                Array.from(packageSelect.options).forEach(option => {
                    if (option.value === oldPackageId) {
                        option.selected = true;
                    }
                });
            }
        }
        
        // Function to update price options based on selected package
        function updatePriceOptions(packageId, selectedPriceId = null) {
            // Reset price dropdown
            priceSelect.innerHTML = '<option value="">-- Select Number of Guests --</option>';
            
            // Find package in packagesData
            const packageData = packagesData.find(pkg => pkg.id == packageId);
            
            if (packageData && packageData.prices && packageData.prices.length > 0) {
                // Add options for each price
                packageData.prices.forEach(price => {
                    const option = document.createElement('option');
                    option.value = price.id;
                    option.textContent = `${price.pax} pax - RM ${parseFloat(price.price).toLocaleString('en-MY', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                    
                    // Select if it matches the old value
                    if (selectedPriceId && price.id == selectedPriceId) {
                        option.selected = true;
                    }
                    
                    priceSelect.appendChild(option);
                });
                
                // Show price container
                priceContainer.style.display = 'block';
            } else {
                // Hide price container if no prices available
                priceContainer.style.display = 'none';
            }
        }
        
        // Initialize package filtering
        filterPackages();
        
        // Add event listener for venue select
        venueSelect.addEventListener('change', function() {
            filterPackages();
            
            // Reset and hide price options since package will be reset
            priceSelect.innerHTML = '<option value="">-- Select Number of Guests --</option>';
            priceContainer.style.display = 'none';
        });
        
        // Add event listener for package select
        packageSelect.addEventListener('change', function() {
            const packageId = this.value;
            
            if (packageId) {
                updatePriceOptions(packageId);
            } else {
                // Hide price selection if no package selected
                priceSelect.innerHTML = '<option value="">-- Select Number of Guests --</option>';
                priceContainer.style.display = 'none';
            }
        });
        
        // Validate booking date and time availability
        const bookingDateInput = document.getElementById('booking_date');
        const sessionSelect = document.getElementById('session');
        
        async function checkAvailability() {
            const date = bookingDateInput.value;
            const session = sessionSelect.value;
            const venueId = venueSelect.value;
            
            if (!date || !session || !venueId) return;
            
            try {
                const response = await fetch(`/api/check-availability?date=${date}&session=${session}&venue_id=${venueId}`);
                const data = await response.json();
                
                if (!data.available) {
                    alert('This venue is already booked for the selected date and session. Please choose another date or session.');
                }
            } catch (error) {
                console.error('Error checking availability:', error);
            }
        }
        
        // Add event listeners for date and session changes
        bookingDateInput.addEventListener('change', checkAvailability);
        sessionSelect.addEventListener('change', checkAvailability);
        venueSelect.addEventListener('change', checkAvailability);
    });
</script>
@endpush
@endsection