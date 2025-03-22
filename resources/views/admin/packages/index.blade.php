@extends('layouts.app')

@section('title', 'Manage Packages - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">Manage Packages</h1>
                <p class="text-gray-600 mt-2">View and manage wedding packages</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-primary hover:underline">Back to Admin Dashboard</a>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">All Packages</h2>
                <a href="{{ route('admin.packages.create') }}" class="bg-primary text-white px-4 py-2 rounded text-sm hover:bg-opacity-90 transition">Add New Package</a>
            </div>
            
            @if(session('success'))
                <div class="mx-6 mt-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="mx-6 mt-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="overflow-x-auto">
                @if($packages->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Venue</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price Range</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($packages as $package)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                {{ $package->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ $package->name }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($package->description, 50) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-gray-500">{{ $package->venue->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($package->prices->count() > 0)
                                    <div class="text-gray-500">
                                        RM {{ number_format($package->min_price, 0, ',', '.') }} - 
                                        RM {{ number_format($package->max_price, 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        for {{ $package->prices->min('pax') }} - {{ $package->prices->max('pax') }} pax
                                    </div>
                                @else
                                    <div class="text-gray-500">No prices set</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                {{ $package->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.packages.show', $package) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                <a href="{{ route('admin.packages.edit', $package) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                
                                <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this package?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center text-gray-500 py-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <p class="mt-4">No packages have been added yet.</p>
                    <a href="{{ route('admin.packages.create') }}" class="mt-4 inline-block text-primary hover:underline">Add your first package</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection