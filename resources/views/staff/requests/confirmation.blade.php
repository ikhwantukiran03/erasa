@extends('layouts.app')

@section('title', 'Booking Request Confirmed - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto text-center">
            <div class="bg-white rounded-lg shadow-md overflow-hidden p-8">
                <div class="flex justify-center mb-6">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                
                <h1 class="text-2xl md:text-3xl font-display font-bold text-gray-800 mb-4">Thank You for Your Request!</h1>
                
                <div class="text-gray-600 mb-8">
                    <p class="mb-4">Your booking request has been successfully submitted to our team.</p>
                    <p class="mb-4">Our staff will review your request and contact you via WhatsApp shortly. Please ensure that your WhatsApp number is active.</p>
                    <p>For any urgent inquiries, you can reach us at <strong>+62 812 3456 7890</strong>.</p>
                </div>
                
                <div class="flex flex-wrap justify-center gap-4 mt-8">
                    <a href="{{ route('public.venues') }}" class="bg-primary text-white px-6 py-2 rounded-md hover:bg-opacity-90 transition">
                        Explore Venues
                    </a>
                    
                    @if(Auth::check())
                        <a href="{{ route('booking-requests.my-requests') }}" class="bg-white text-primary border border-primary px-6 py-2 rounded-md hover:bg-gray-50 transition">
                            View My Requests
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="bg-white text-primary border border-primary px-6 py-2 rounded-md hover:bg-gray-50 transition">
                            Sign In to Track Requests
                        </a>
                    @endif
                </div>
            </div>
            
            <div class="mt-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">What Happens Next?</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <div class="bg-blue-100 rounded-full w-10 h-10 flex items-center justify-center mb-4">
                            <span class="text-blue-600 font-semibold">1</span>
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-2">Review</h3>
                        <p class="text-gray-600 text-sm">Our team will review your request details and check availability.</p>
                    </div>
                    
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <div class="bg-blue-100 rounded-full w-10 h-10 flex items-center justify-center mb-4">
                            <span class="text-blue-600 font-semibold">2</span>
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-2">Confirmation</h3>
                        <p class="text-gray-600 text-sm">You will receive a WhatsApp message with a response to your request.</p>
                    </div>
                    
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <div class="bg-blue-100 rounded-full w-10 h-10 flex items-center justify-center mb-4">
                            <span class="text-blue-600 font-semibold">3</span>
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-2">Next Steps</h3>
                        <p class="text-gray-600 text-sm">If approved, we'll guide you through the booking process and payment details.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection