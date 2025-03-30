<div class="booking-form-container bg-white rounded-lg shadow p-6">
    <form action="{{ route('requests.store') }}" method="POST">
        @csrf
        
        @if(session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
                {{ session('success') }}
            </div>
        @endif
        
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
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-dark font-medium mb-1">Full Name <span class="text-red-500">*</span></label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}"
                    required 
                    class="form-input w-full"
                    placeholder="Enter your full name"
                >
            </div>
            
            <div>
                <label for="whatsapp" class="block text-dark font-medium mb-1">WhatsApp Number <span class="text-red-500">*</span></label>
                <input 
                    type="text" 
                    id="whatsapp" 
                    name="whatsapp" 
                    value="{{ old('whatsapp') }}"
                    required 
                    class="form-input w-full"
                    placeholder="+62 812 3456 7890"
                >
            </div>
            
            <div>
                <label for="email" class="block text-dark font-medium mb-1">Email Address <span class="text-red-500">*</span></label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    required 
                    class="form-input w-full"
                    placeholder="your@email.com"
                >
            </div>
            
            <div>
                <label for="event_date" class="block text-dark font-medium mb-1">Event Date</label>
                <input 
                    type="date" 
                    id="event_date" 
                    name="event_date" 
                    value="{{ old('event_date') }}"
                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                    class="form-input w-full"
                >
            </div>
            
            <div>
                <label for="venue_id" class="block text-dark font-medium mb-1">Preferred Venue</label>
                <select id="venue_id" name="venue_id" class="form-input w-full">
                    <option value="">-- Select Venue --</option>
                    @foreach(App\Models\Venue::orderBy('name')->get() as $venue)
                        <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                            {{ $venue->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="package_id" class="block text-dark font-medium mb-1">Interested Package</label>
                <select id="package_id" name="package_id" class="form-input w-full">
                    <option value="">-- Select Package --</option>
                    @foreach(App\Models\Package::orderBy('name')->get() as $package)
                        <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                            {{ $package->name }} ({{ $package->venue->name }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="md:col-span-2">
                <label for="message" class="block text-dark font-medium mb-1">Your Message <span class="text-red-500">*</span></label>
                <textarea 
                    id="message" 
                    name="message" 
                    rows="5" 
                    required 
                    class="form-input w-full"
                    placeholder="Please share details about your event, guest count, and any special requirements"
                >{{ old('message') }}</textarea>
            </div>
        </div>
        
        <div class="mt-6">
            <button type="submit" class="bg-primary text-white px-6 py-3 rounded hover:bg-opacity-90 transition w-full md:w-auto">
                Submit Booking Request
            </button>
        </div>
    </form>
</div>