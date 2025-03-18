@extends('layouts.app')

@section('title', 'Manage Bookings - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Manage Bookings</h1>
                <p class="text-gray-600 mt-2">View and manage all venue reservations</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-primary hover:underline">Back to Admin Dashboard</a>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">All Bookings</h2>
                <a href="#" class="bg-primary text-white px-4 py-2 rounded text-sm hover:bg-opacity-90 transition">Create New Booking</a>
            </div>
            
            <div class="p-6">
                <div class="mb-6 flex flex-wrap gap-4">
                    <a href="#" class="px-4 py-2 bg-primary text-white rounded-full text-sm">All</a>
                    <a href="#" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full text-sm hover:bg-gray-300 transition">Upcoming</a>
                    <a href="#" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full text-sm hover:bg-gray-300 transition">Pending</a>
                    <a href="#" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full text-sm hover:bg-gray-300 transition">Completed</a>
                    <a href="#" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full text-sm hover:bg-gray-300 transition">Cancelled</a>
                </div>
                
                <div class="text-center text-gray-500 py-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="mt-4">There are no bookings in the system yet.</p>
                    <a href="#" class="mt-4 inline-block text-primary hover:underline">Create the first booking</a>
                </div>
                
                <!-- When there are bookings, uncomment this section -->
                <!--
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">1</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">John Doe</div>
                                    <div class="text-gray-500">john@example.com</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">Classic Celebration</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">Jan 15, 2026</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Confirmed</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">Rp 45,000,000</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                    <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <a href="#" class="text-red-600 hover:text-red-900">Cancel</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                -->
            </div>
        </div>
    </div>
</div>
@endsection