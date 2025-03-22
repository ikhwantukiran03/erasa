@extends('layouts.app')

@section('title', 'View Package - Enak Rasa Wedding Hall')

@section('content')
<div class="bg-gray-50 py-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-display font-bold text-dark">{{ $package->name }}</h1>
                <p class="text-gray-600 mt-2">View package details</p>
            </div>
            <a href="{{ route('admin.packages.index') }}" class="text-primary hover:underline">Back to Packages</a>
        </div>
        
        <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Package Information</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">Package Name</p>
                                <p class="text-gray-800 font-medium">{{ $package->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Venue</p>
                                <p class="text-gray-800">{{ $package->venue->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Description</p>
                                <p class="text-gray-800">{{ $package->description ?: 'No description provided' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Created</p>
                                <p class="text-gray-800">{{ $package->created_at->format('M d, Y H:i:s') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Last Updated</p>
                                <p class="text-gray-800">{{ $package->updated_at->format('M d, Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Package Prices</h3>
                        <div class="overflow-hidden border border-gray-200 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Number of Guests (Pax)</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($package->prices as $price)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $price->pax }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $price->formatted_price }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center" colspan="2">No prices have been set for this package.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Package Items</h3>
                    
                    @php
                        $packageItemsByCategory = $package->packageItems->groupBy(function($item) {
                            return $item->item->category->name;
                        });
                    @endphp
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($packageItemsByCategory as $categoryName => $packageItems)
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                                    <h4 class="font-medium text-gray-700">{{ $categoryName }}</h4>
                                </div>
                                <ul class="divide-y divide-gray-200">
                                    @foreach($packageItems as $packageItem)
                                        <li class="px-4 py-3">
                                            <p class="font-medium text-gray-800">{{ $packageItem->item->name }}</p>
                                            @if($packageItem->item->description)
                                                <p class="text-sm text-gray-600 mt-1">{{ $packageItem->item->description }}</p>
                                            @endif
                                            @if($packageItem->description)
                                                <p class="text-sm text-gray-500 mt-2 italic">Note: {{ $packageItem->description }}</p>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @empty
                            <div class="md:col-span-2 text-center text-gray-500 py-8">
                                <p>No items have been added to this package.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                
                <div class="mt-8 flex gap-4">
                    <a href="{{ route('admin.packages.edit', $package) }}" class="bg-primary text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                        Edit Package
                    </a>
                    <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this package?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                            Delete Package
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection