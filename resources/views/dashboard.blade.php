@extends('layouts.app')

@section('title', 'Dashboard - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-display font-bold text-dark">Dashboard</h1>
        <p class="text-gray-600 mt-2">Welcome back, {{ Auth::user()->name }}!</p>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Your Profile</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-gray-600 font-medium">Personal Information</h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">Full Name</p>
                                <p class="text-gray-800">{{ Auth::user()->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email Address</p>
                                <p class="text-gray-800">{{ Auth::user()->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">WhatsApp Number</p>
                                <p class="text-gray-800">{{ Auth::user()->whatsapp }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Account Type</p>
                                <p class="text-gray-800">{{ ucfirst(Auth::user()->role) }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-gray-600 font-medium">Quick Actions</h3>
                        <div class="mt-4 space-y-3">
                            <a href="#" class="block bg-primary bg-opacity-10 text-primary p-4 rounded hover:bg-opacity-20 transition">
                                <div class="font-semibold">Book a Venue</div>
                                <p class="text-sm mt-1">Reserve our wedding hall for your special day</p>
                            </a>
                            <a href="#" class="block bg-primary bg-opacity-10 text-primary p-4 rounded hover:bg-opacity-20 transition">
                                <div class="font-semibold">View Bookings</div>
                                <p class="text-sm mt-1">Check your upcoming and past reservations</p>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="block bg-primary bg-opacity-10 text-primary p-4 rounded hover:bg-opacity-20 transition">
                                <div class="font-semibold">Update Profile</div>
                                <p class="text-sm mt-1">Edit your personal information</p>
                            </a>
                            <a href="{{ route('profile.edit') }}#delete-account" class="block bg-red-100 text-red-600 p-4 rounded hover:bg-red-200 transition">
                                <div class="font-semibold">Delete Account</div>
                                <p class="text-sm mt-1">Permanently delete your account</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::user()->isAdmin())
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-semibold text-gray-800">Admin Panel</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="#" class="block bg-indigo-50 text-indigo-600 p-4 rounded hover:bg-indigo-100 transition">
                        <div class="font-semibold">Manage Users</div>
                        <p class="text-sm mt-1">View and edit user accounts</p>
                    </a>
                    <a href="#" class="block bg-green-50 text-green-600 p-4 rounded hover:bg-green-100 transition">
                        <div class="font-semibold">All Bookings</div>
                        <p class="text-sm mt-1">View and manage all reservations</p>
                    </a>
                    <a href="#" class="block bg-purple-50 text-purple-600 p-4 rounded hover:bg-purple-100 transition">
                        <div class="font-semibold">Site Settings</div>
                        <p class="text-sm mt-1">Configure website options</p>
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection