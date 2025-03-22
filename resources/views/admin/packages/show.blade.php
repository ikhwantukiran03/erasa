@extends('layouts.app')

@section('title', $package->name . ' - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-10">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-display font-bold text-primary">{{ $package->name }}</h1>
                <div class="flex items-center mt-2 text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>{{ $package->venue->name }}</span>
                </div>
            </div>
            <a href="{{ route('admin.packages.index') }}" class="mt-4 md:mt-0 text-primary hover:underline flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Packages
            </a>
        </div>
        
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
            <div class="p-6 md:p-8">
                <!-- Package Overview -->
                <div class="flex flex-col md:flex-row md:items-center pb-6 border-b border-gray-100">
                    <div class="flex-1">
                        <div class="prose max-w-none">
                            @if($package->description)
                                <p class="text-gray-700">{{ $package->description }}</p>
                            @else
                                <p class="text-gray-500 italic">No description provided</p>
                            @endif
                        </div>
                    </div>
                    <div class="mt-6 md:mt-0 md:ml-8 flex flex-col items-start md:items-end">
                        <div class="bg-primary/10 px-4 py-3 rounded-lg text-center">
                            <p class="text-gray-600 text-sm">Price Range</p>
                            <p class="text-primary text-2xl font-bold">
                                RM {{ number_format($package->min_price, 0, ',', '.') }}
                                @if($package->min_price != $package->max_price)
                                    - RM {{ number_format($package->max_price, 0, ',', '.') }}
                                @endif
                            </p>
                            @if($package->prices->count() > 0)
                                <p class="text-xs text-gray-500 mt-1">
                                    for {{ $package->prices->min('pax') }} - {{ $package->prices->max('pax') }} guests
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Pricing Details -->
                <div class="mt-8">
                    <h3 class="text-xl font-display font-semibold text-dark mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pricing Details
                    </h3>
                    
                    <div class="bg-gray-50 rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Number of Guests</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Per Guest</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($package->prices as $price)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">
                                            {{ $price->pax }} pax
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-semibold">
                                            {{ $price->formatted_price }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ 'RM ' . number_format($price->price / $price->pax, 0, ',', '.') }} / person
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-500 text-center" colspan="3">
                                            No prices have been set for this package.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Package Items -->
                <div class="mt-10">
                    <h3 class="text-xl font-display font-semibold text-dark mb-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Package Inclusions
                    </h3>
                    
                    @php
                        $packageItemsByCategory = $package->packageItems->groupBy(function($item) {
                            return $item->item->category->name;
                        });
                    @endphp
                    
                    @if($packageItemsByCategory->count() > 0)
                        <div class="space-y-8">
                            @foreach($packageItemsByCategory as $categoryName => $packageItems)
                                <div>
                                    <h4 class="text-lg font-semibold text-primary mb-4">{{ $categoryName }}</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($packageItems as $packageItem)
                                            <div class="bg-white p-4 rounded-lg border border-gray-200 hover:shadow-md transition duration-200 flex">
                                                <div class="flex-shrink-0 mr-3">
                                                    <div class="h-10 w-10 bg-primary/10 rounded-full flex items-center justify-center text-primary">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h5 class="font-medium text-gray-800">{{ $packageItem->item->name }}</h5>
                                                    @if($packageItem->description)
                                                        <p class="text-sm text-gray-600 mt-1">{{ $packageItem->description }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="mt-4 text-gray-500">No items have been added to this package.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="flex gap-4 justify-end">
            <a href="{{ route('admin.packages.edit', $package) }}" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-opacity-90 transition inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Package
            </a>
            <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this package?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-opacity-90 transition inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Package
                </button>
            </form>
        </div>
    </div>
</div>
@endsection