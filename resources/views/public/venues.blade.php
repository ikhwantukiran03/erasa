@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="text-center mb-5">Wedding Venues</h1>
        </div>
    </div>

    <!-- Venue Selection Buttons -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex flex-wrap justify-content-center">
                @foreach($venues as $venue)
                    <a href="{{ route('public.venues', ['venue_id' => $venue->id]) }}" 
                       class="btn btn-lg {{ $selectedVenue && $selectedVenue->id == $venue->id ? 'btn-primary' : 'btn-outline-primary' }} m-2">
                        {{ $venue->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Selected Venue Content -->
    @if($selectedVenue)
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-4">{{ $selectedVenue->name }}</h2>
                <div class="card mb-5">
                    <div class="card-body">
                        <h5 class="card-title">About this Venue</h5>
                        <p class="card-text">{{ $selectedVenue->description }}</p>
                        <p class="card-text">
                            <strong>Address:</strong><br>
                            {{ $selectedVenue->address_line_1 }}<br>
                            {{ $selectedVenue->address_line_2 }}<br>
                            {{ $selectedVenue->city }}, {{ $selectedVenue->state }} {{ $selectedVenue->postal_code }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gallery Section -->
        @if($galleries->count() > 0)
            <div class="row mb-5">
                <div class="col-12">
                    <h3 class="text-center mb-4">Gallery</h3>
                    <div class="row">
                        @foreach($galleries as $gallery)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    @if($gallery->image_path)
                                        <img src="{{ asset('storage/' . $gallery->image_path) }}" class="card-img-top" alt="{{ $gallery->title }}">
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $gallery->title }}</h5>
                                        <p class="card-text">{{ $gallery->description }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Packages Section -->
        @if($packages->count() > 0)
            <div class="row">
                <div class="col-12">
                    <h3 class="text-center mb-4">Wedding Packages</h3>
                    <div class="row">
                        @foreach($packages as $package)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $package->name }}</h5>
                                        <p class="card-text">{{ $package->description }}</p>
                                        
                                        @if($package->prices->count() > 0)
                                            <div class="mb-3">
                                                <h6>Pricing:</h6>
                                                <ul class="list-unstyled">
                                                    @foreach($package->prices as $price)
                                                        <li>{{ $price->pax }} pax: RM{{ $price->price }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        
                                        <a href="{{ route('public.package', $package) }}" class="btn btn-primary">View Details</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="row">
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    Please select a venue from above to view packages and galleries.
                </div>
            </div>
        </div>
    @endif
</div>
@endsection