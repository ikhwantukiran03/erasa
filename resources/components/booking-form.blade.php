<!-- resources/views/components/booking-form.blade.php -->

<div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
    <h3 class="text-2xl font-display font-bold text-primary mb-4 text-center">Book Your Special Day</h3>
    <p class="text-gray-600 mb-6 text-center">Fill out the form below to check availability and start planning your dream wedding.</p>
    
    @if(session('success'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif
    
    <form action="{{ route('requests.store') }}" method="POST" class="space-y-4">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="name" class="block text-gray-700 font-medium mb-1">Full Name <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="whatsapp" class="block text-gray-700 font-medium mb-1">WhatsApp Number <span class="text-red-500">*</span></label>
                <input type="text" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition">
                @error('whatsapp')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="email" class="block text-gray-700 font-medium mb-1">Email Address <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="event_date" class="block text-gray-700 font-medium mb-1">Event Date <span class="text-red-500">*</span></label>
                <input type="date" id="event_date" name="event_date" value="{{ old('event_date') }}" required 
                    min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition">
                @error('event_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="venue_id" class="block text-gray-700 font-medium mb-1">Preferred Venue</label>
                <select id="venue_id" name="venue_id" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition">
                    <option value="">-- Select Venue --</option>
                    @foreach(\App\Models\Venue::orderBy('name')->get() as $venue)
                        <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                            {{ $venue->name }}
                        </option>
                    @endforeach
                </select>
                @error('venue_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="package_id" class="block text-gray-700 font-medium mb-1">Interested In Package</label>
                <select id="package_id" name="package_id" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition">
                    <option value="">-- Select Package --</option>
                    @foreach(\App\Models\Package::orderBy('name')->get() as $package)
                        <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                            {{ $package->name }} ({{ $package->venue->name }})
                        </option>
                    @endforeach
                </select>
                @error('package_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label for="message" class="block text-gray-700 font-medium mb-1">Message <span class="text-red-500">*</span></label>
                <textarea id="message" name="message" rows="4" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition"
                    placeholder="Tell us about your event and any special requirements...">{{ old('message') }}</textarea>
                @error('message')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="flex justify-center mt-6">
            <button type="submit" class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-opacity-90 transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-opacity-50">
                Submit Booking Request
            </button>
        </div>
    </form>
</div>