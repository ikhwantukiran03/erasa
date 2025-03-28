@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('public.venues') }}">Venues</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('public.venues', ['venue_id' => $package->venue_id]) }}">{{ $package->venue->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $package->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <h1>{{ $package->name }}</h1>
            <p class="lead">{{ $package->description }}</p>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Package Details</h5>
                </div>
                <div class="card-body">
                    <h6>Venue: {{ $package->venue->name }}</h6>
                    <p>{{ $package->venue->address_line_1 }}, {{ $package->venue->city }}, {{ $package->venue->state }}</p>
                    
                    @if($package->packageItems->count() > 0)
                        <h6 class="mt-4">Included Items:</h6>
                        <div class="accordion" id="packageItems">
                            @php $prevCategory = ''; @endphp
                            @foreach($package->packageItems->sortBy('item.category.name') as $packageItem)
                                @if($prevCategory != $packageItem->item->category->name)
                                    @if($prevCategory != '')
                                            </ul>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->index }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{ $loop->index }}">
                                                {{ $packageItem->item->category->name }}
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}">
                                            <div class="accordion-body">
                                                <ul class="list-group">
                                    @php $prevCategory = $packageItem->item->category->name; @endphp
                                @endif
                                
                                <li class="list-group-item">
                                    <strong>{{ $packageItem->item->name }}</strong>
                                    @if($packageItem->description)
                                        <p class="mb-0 text-muted">{{ $packageItem->description }}</p>
                                    @endif
                                </li>
                                
                                @if($loop->last)
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card sticky-top" style="top: 20px">
                <div class="card-header">
                    <h5 class="mb-0">Pricing</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($package->prices->sortBy('pax') as $price)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $price->pax }} pax</span>
                                <span class="badge bg-primary rounded-pill">RM{{ number_format($price->price, 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                    
                    <div class="d-grid gap-2 mt-4">
                        <a href="#" class="btn btn-success">Book Now</a>
                        <a href="https://wa.me/60123456789?text=I'm interested in {{ $package->name }} at {{ $package->venue->name }}" target="_blank" class="btn btn-outline-success">
                            <i class="fab fa-whatsapp"></i> Contact via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if($relatedPackages->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <h3>Other Packages at {{ $package->venue->name }}</h3>
                <div class="row">
                    @foreach($relatedPackages as $relatedPackage)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $relatedPackage->name }}</h5>
                                    <p class="card-text">{{ $relatedPackage->description }}</p>
                                    <a href="{{ route('public.package', $relatedPackage) }}" class="btn btn-outline-primary">View Package</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection