<!-- resources/views/components/venue-gallery.blade.php -->

<div class="bg-gray-50 py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-display font-bold text-primary mb-4">Photo Gallery</h2>
            <p class="text-gray-600 md:text-lg max-w-3xl mx-auto">Explore our beautiful wedding venue through these captivating images</p>
            <div class="w-24 h-1 bg-primary mx-auto mt-6"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 gallery-container" id="gallery-grid">
            @foreach($galleries as $index => $gallery)
                <div class="gallery-item rounded-xl overflow-hidden shadow-lg transition-all duration-500 transform hover:scale-105 hover:shadow-xl cursor-pointer group {{ $index % 7 === 0 ? 'md:col-span-2 md:row-span-2' : '' }}" 
                     data-src="{{ $gallery->source === 'local' ? asset('storage/'.$gallery->image_path) : $gallery->image_url }}"
                     data-title="{{ $gallery->title }}"
                     data-description="{{ $gallery->description }}">
                    <div class="{{ $index % 7 === 0 ? 'aspect-w-16 aspect-h-12' : 'aspect-w-1 aspect-h-1' }} bg-gray-100 relative">
                        <img 
                            src="{{ $gallery->source === 'local' ? asset('storage/'.$gallery->image_path) : $gallery->image_url }}" 
                            alt="{{ $gallery->title }}" 
                            class="object-cover w-full h-full"
                            onerror="this.src='{{ asset('img/placeholder.jpg') }}'; this.onerror=null;"
                            loading="lazy"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col justify-end">
                            <div class="p-5 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                <h3 class="font-display font-semibold text-lg text-white">{{ $gallery->title }}</h3>
                                @if($gallery->description)
                                    <p class="text-white/80 mt-2 text-sm line-clamp-2 opacity-0 group-hover:opacity-100 transition-all duration-300 delay-100">{{ $gallery->description }}</p>
                                @endif
                                <div class="mt-3 opacity-0 group-hover:opacity-100 transition-all duration-300 delay-150">
                                    <span class="inline-flex items-center text-xs text-white/90 border border-white/30 px-3 py-1 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if(count($galleries) === 0)
            <div class="text-center text-gray-500 py-16 bg-white rounded-xl shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto text-gray-300 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-xl font-display">No gallery images available for this venue</p>
                <p class="mt-2 text-gray-400">Please check back later or contact us for more information</p>
            </div>
        @endif
    </div>
</div>

<!-- Lightbox Modal -->
<div id="lightbox-modal" class="fixed inset-0 z-50 hidden bg-black/95 backdrop-blur-sm flex items-center justify-center">
    <button id="close-lightbox" class="absolute top-6 right-6 text-white hover:text-primary transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
    
    <button id="prev-image" class="absolute left-6 text-white hover:text-primary transition-colors transform -translate-y-1/2 top-1/2 bg-black/30 p-2 rounded-full">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>
    
    <div id="lightbox-content" class="max-w-5xl max-h-[90vh] overflow-hidden rounded-xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300">
        <img id="lightbox-image" src="" alt="Gallery Image" class="max-w-full max-h-[75vh] mx-auto">
        <div class="bg-white p-6">
            <h3 id="lightbox-title" class="font-display font-bold text-xl text-gray-800"></h3>
            <p id="lightbox-description" class="text-gray-600 mt-2"></p>
        </div>
    </div>
    
    <button id="next-image" class="absolute right-6 text-white hover:text-primary transition-colors transform -translate-y-1/2 top-1/2 bg-black/30 p-2 rounded-full">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </button>
</div>

@push('styles')
<style>
    .aspect-w-16 {
        position: relative;
        padding-bottom: 75%;
    }
    
    .aspect-w-1 {
        position: relative;
        padding-bottom: 100%;
    }
    
    .aspect-h-12, .aspect-h-1 {
        position: absolute;
        height: 100%;
        width: 100%;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Animation for gallery items */
    .gallery-container .gallery-item {
        opacity: 0;
        animation: fade-in-up 0.6s ease forwards;
    }
    
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .gallery-container .gallery-item:nth-child(1) { animation-delay: 0.1s; }
    .gallery-container .gallery-item:nth-child(2) { animation-delay: 0.2s; }
    .gallery-container .gallery-item:nth-child(3) { animation-delay: 0.3s; }
    .gallery-container .gallery-item:nth-child(4) { animation-delay: 0.4s; }
    .gallery-container .gallery-item:nth-child(5) { animation-delay: 0.5s; }
    .gallery-container .gallery-item:nth-child(6) { animation-delay: 0.6s; }
    .gallery-container .gallery-item:nth-child(7) { animation-delay: 0.7s; }
    .gallery-container .gallery-item:nth-child(8) { animation-delay: 0.8s; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const galleryItems = document.querySelectorAll('.gallery-item');
        const lightboxModal = document.getElementById('lightbox-modal');
        const lightboxImage = document.getElementById('lightbox-image');
        const lightboxTitle = document.getElementById('lightbox-title');
        const lightboxDescription = document.getElementById('lightbox-description');
        const lightboxContent = document.getElementById('lightbox-content');
        const closeLightbox = document.getElementById('close-lightbox');
        const prevImage = document.getElementById('prev-image');
        const nextImage = document.getElementById('next-image');
        
        let currentIndex = 0;
        const galleries = @json($galleries);
        
        // Open lightbox when clicking on a gallery item
        galleryItems.forEach((item, index) => {
            item.addEventListener('click', function() {
                currentIndex = index;
                openLightbox();
            });
        });
        
        // Close lightbox
        closeLightbox.addEventListener('click', function() {
            closeLightboxModal();
        });
        
        // Close lightbox when clicking outside the content
        lightboxModal.addEventListener('click', function(e) {
            if (e.target === lightboxModal) {
                closeLightboxModal();
            }
        });
        
        // Navigate to previous image
        prevImage.addEventListener('click', function() {
            currentIndex = (currentIndex - 1 + galleries.length) % galleries.length;
            updateLightbox();
        });
        
        // Navigate to next image
        nextImage.addEventListener('click', function() {
            currentIndex = (currentIndex + 1) % galleries.length;
            updateLightbox();
        });
        
        // Open lightbox with animation
        function openLightbox() {
            updateLightbox();
            lightboxModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
            
            // Add animation
            setTimeout(() => {
                lightboxContent.classList.remove('scale-95', 'opacity-0');
                lightboxContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
        
        // Close lightbox with animation
        function closeLightboxModal() {
            lightboxContent.classList.remove('scale-100', 'opacity-100');
            lightboxContent.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                lightboxModal.classList.add('hidden');
                document.body.style.overflow = ''; // Re-enable scrolling
            }, 300);
        }
        
        // Update lightbox content
        function updateLightbox() {
            const gallery = galleries[currentIndex];
            const imgSrc = gallery.source === 'local' 
                ? '/storage/' + gallery.image_path 
                : gallery.image_url;
            
            // Fade out effect during image change
            lightboxImage.style.opacity = '0';
            setTimeout(() => {
                lightboxImage.src = imgSrc;
                lightboxTitle.textContent = gallery.title;
                lightboxDescription.textContent = gallery.description || '';
                
                // Fade in when loaded
                lightboxImage.onload = function() {
                    lightboxImage.style.opacity = '1';
                };
                
                // Add error handling for images
                lightboxImage.onerror = function() {
                    this.src = '{{ asset('img/placeholder.jpg') }}';
                    this.onerror = null;
                    lightboxImage.style.opacity = '1';
                };
            }, 300);
            
            // Handle navigation button visibility
            if (galleries.length <= 1) {
                prevImage.classList.add('hidden');
                nextImage.classList.add('hidden');
            } else {
                prevImage.classList.remove('hidden');
                nextImage.classList.remove('hidden');
            }
        }
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (lightboxModal.classList.contains('hidden')) return;
            
            if (e.key === 'Escape') {
                closeLightboxModal();
            } else if (e.key === 'ArrowLeft') {
                currentIndex = (currentIndex - 1 + galleries.length) % galleries.length;
                updateLightbox();
            } else if (e.key === 'ArrowRight') {
                currentIndex = (currentIndex + 1) % galleries.length;
                updateLightbox();
            }
        });
        
        // Add transition styles to the lightbox image
        lightboxImage.style.transition = 'opacity 0.3s ease';
    });
</script>
@endpush