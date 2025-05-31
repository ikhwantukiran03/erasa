@extends('layouts.app')

@section('title', 'Booking Request Submitted - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Booking progress indicator -->
                <div class="bg-primary bg-opacity-10 px-6 py-4 border-b border-primary border-opacity-20">
                    <div class="flex justify-between">
                        <div class="text-center w-1/3">
                            <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center mx-auto">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <p class="text-sm mt-1 font-medium text-gray-700">Request Details</p>
                        </div>
                        <div class="text-center w-1/3">
                            <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center mx-auto">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <p class="text-sm mt-1 font-medium text-gray-700">Confirmation</p>
                        </div>
                        <div class="text-center w-1/3">
                            <div class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center mx-auto">
                                <span>3</span>
                            </div>
                            <p class="text-sm mt-1 font-medium text-primary">Complete</p>
                        </div>
                    </div>
                    <div class="mt-2 flex">
                        <div class="h-1 w-full bg-green-500"></div>
                        <div class="h-1 w-full bg-green-500"></div>
                    </div>
                </div>
                
                <div class="p-8 text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="h-10 w-10 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    
                    <h1 class="text-2xl font-display font-bold text-gray-800 mb-3">Thank You for Your Booking Request!</h1>
                    <p class="text-gray-600 mb-6">Your request has been successfully submitted. Our team will review it and get back to you shortly.</p>
                    
                    <div class="bg-gray-50 rounded-lg p-5 mb-6 text-left">
                        <h2 class="text-lg font-semibold text-gray-800 mb-3">What Happens Next?</h2>
                        <ul class="space-y-4">
                            <li class="flex">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-7 w-7 rounded-full bg-primary text-white text-sm">1</div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-700">Our team will review your booking request.</p>
                                </div>
                            </li>
                            <li class="flex">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-7 w-7 rounded-full bg-primary text-white text-sm">2</div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-700">You'll receive a confirmation email within 24 hours.</p>
                                </div>
                            </li>
                            <li class="flex">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-7 w-7 rounded-full bg-primary text-white text-sm">3</div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-700">A staff member will contact you via WhatsApp to discuss details.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="flex flex-col md:flex-row items-center justify-center space-y-3 md:space-y-0 md:space-x-4">
                        <a href="{{ route('dashboard') }}" class="bg-primary text-white px-6 py-3 rounded-md font-medium hover:bg-opacity-90 transition shadow-sm w-full md:w-auto">
                            Go to Dashboard
                        </a>
                        <a href="{{ route('booking-requests.create') }}" class="bg-white border border-gray-300 text-gray-700 px-6 py-3 rounded-md font-medium hover:bg-gray-50 transition w-full md:w-auto">
                            Make Another Booking
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center text-sm text-gray-500">
                    <svg class="h-5 w-5 text-primary mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p>Have questions? Contact our support team at <a href="mailto:rasa.enak@gmail.com" class="text-primary hover:underline">rasa.enak@gmail.com</a> or call <a href="tel:+60133314389" class="text-primary hover:underline">013-331 4389</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection