@extends('layouts.app')

@section('title', 'Book Your Event - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-10">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-display font-bold text-primary">Book Your Special Day</h1>
                <p class="text-gray-600 mt-2">Fill out the form below to request a booking at Enak Rasa Wedding Hall</p>
            </div>
            
            @if(session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                    {{ session('success') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                    <p class="font-bold">Please fix the following errors:</p>
                    <ul class="list-disc ml-4 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <form action="{{ route('booking-requests.store') }}" method="POST" class="booking p-6 md:p-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Personal Information -->
                        <div class="md:col-span-2">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Personal Information</h2>
                        </div>
                        
                        <div class="form-group">
                            <label for="name" class="block text-dark font-medium mb-1">Full Name <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}" 
                                required 
                                class="form-input w-full"
                                placeholder="Enter your full name"
                            >
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="block text-dark font-medium mb-1">Email Address <span class="text-red-500">*</span></label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}" 
                                required 
                                class="form-input w-full"
                                placeholder="your@email.com"
                            >
                        </div>
                        
                        <div class="form-group">
                            <label for="whatsapp_no" class="block text-dark font-medium mb-1">WhatsApp Number <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                id="whatsapp_no" 
                                name="whatsapp_no" 
                                value="{{ old('whatsapp_no', Auth::check() ? Auth::user()->whatsapp : '') }}" 
                                required 
                                class="form-input w-full"
                                placeholder="+62 812 3456 7890"
                            >
                            <p class="text-sm text-gray-500 mt-1">Include country code for WhatsApp notifications</p>
                        </div>
                        
                        <!-- Booking Details -->
                        <div class="md:col-span-2 pt-4 border-t border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Booking Details</h2>
                        </div>
                        
                        <div class="form-group">
                            <label for="type" class="block text-dark font-medium mb-1">Request Type <span class="text-red-500">*</span></label>
                            <select 
                                id="type" 
                                name="type" 
                                required 
                                class="form-input w-full"
                            >
                                <option value="booking" {{ old('type') == 'booking' ? 'selected' : '' }}>Booking (Confirm Reservation)</option>
                                <option value="reservation" {{ old('type') == 'reservation' ? 'selected' : '' }}>Reservation (Hold Date)</option>
                                <option value="viewing" {{ old('type') == 'viewing' ? 'selected' : '' }}>Venue Viewing</option>
                                <option value="appointment" {{ old('type') == 'appointment' ? 'selected' : '' }}>Consultation Appointment</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="event_date" class="block text-dark font-medium mb-1">Event Date</label>
                            <input 
                                type="date" 
                                id="event_date" 
                                name="event_date" 
                                value="{{ old('event_date') }}"
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                class="form-input w-full"
                            >
                            <p class="text-sm text-gray-500 mt-1">Tentative date for your event</p>
                        </div>
                        
                        <div class="form-group">
                            <label for="venue_id" class="block text-dark font-medium mb-1">Preferred Venue</label>
                            <select id="venue_id" name="venue_id" class="form-input w-full">
                                <option value="">-- Select Venue --</option>
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                                        {{ $venue->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="package_id" class="block text-dark font-medium mb-1">Interested Package</label>
                            <select id="package_id" name="package_id" class="form-input w-full">
                                <option value="">-- Select Package --</option>
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
                        </div>

                        <!-- New Price Selection Field -->
                        <div class="form-group" id="price-selection-container">
                            <label for="price_id" class="block text-dark font-medium mb-1">Number of Guests (Pax)</label>
                            <select id="price_id" name="price_id" class="form-input w-full">
                                <option value="">-- Select Number of Guests --</option>
                                <!-- Price options will be populated via JavaScript -->
                            </select>
                            <p class="text-sm text-gray-500 mt-1">Select the expected number of guests for your event</p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="message" class="block text-dark font-medium mb-1">Your Message <span class="text-red-500">*</span></label>
                            <textarea 
                                id="message" 
                                name="message" 
                                rows="5" 
                                required 
                                class="form-input w-full"
                                placeholder="Please share details about your event, guest count, and any special requirements or questions"
                            >{{ old('message') }}</textarea>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex justify-center">
                        <button type="submit" class="bg-primary text-white px-8 py-3 rounded-md font-medium hover:bg-opacity-90 transition">
                            Submit Booking Request
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

    const oldPackageId = "{{ old('package_id') }}";
    const oldPriceId = "{{ old('price_id') }}";

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

    const bookingDateInput = document.getElementById('booking_date');
    const sessionSelect = document.getElementById('session');

    bookingDateInput.addEventListener('change', checkAvailability);
    sessionSelect.addEventListener('change', checkAvailability);

    // On load
    filterPackages();
    if (oldPackageId) {
        updatePriceOptions(oldPackageId, oldPriceId);
    }
});
</script>
@endpush
@endsection