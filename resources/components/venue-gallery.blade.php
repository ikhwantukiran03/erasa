<!-- resources/views/components/venue-gallery.blade.php -->

<div class="bg-white py-12">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-display font-bold text-center text-primary mb-2">Photo Gallery</h2>
        <p class="text-gray-600 text-center mb-10">Explore our beautiful wedding venue through these images</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 gallery-container" id="gallery-grid">
            @foreach($galleries as $gallery)
                <div class="gallery-item rounded-lg overflow-hidden shadow-lg transition transform hover:scale-105 cursor-pointer" 
                     data-src="{{ $gallery->source === 'local' ? $gallery->image_path : $gallery->image_url }}">
                    <div class="aspect-w-16 aspect-h-12 bg-gray-200">
                        <img 
                            src="{{ $gallery->source === 'local' ? $gallery->image_path : $gallery->image_url }}" 
                            alt="{{ $gallery->title }}" 
                            class="object-cover w-full h-full"
                            onerror="this.src='{{ asset('img/placeholder.jpg') }}'; this.onerror=null;"
                        >
                    </div>
                    <div class="p-4 bg-white">
                        <h3 class="font-semibold text-lg text-gray-800">{{ $gallery->title }}</h3>
                        @if($gallery->description)
                            <p class="text-gray-600 mt-1 text-sm line-clamp-2">{{ $gallery->description }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        @if(count($galleries) === 0)
            <div class="text-center text-gray-500 py-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-lg">No gallery images available for this venue</p>
            </div>
        @endif
    </div>
</div>

<!-- Lightbox Modal -->
<div id="lightbox-modal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-90 flex items-center justify-center">
    <button id="close-lightbox" class="absolute top-4 right-4 text-white hover:text-gray-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
    
    <button id="prev-image" class="absolute left-4 text-white hover:text-gray-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>
    
    <div id="lightbox-content" class="max-w-4xl max-h-[90vh] overflow-hidden">
        <img id="lightbox-image" src="" alt="Gallery Image" class="max-w-full max-h-[80vh] mx-auto">
        <div class="bg-white p-4">
            <h3 id="lightbox-title" class="font-semibold text-lg text-gray-800"></h3>
            <p id="lightbox-description" class="text-gray-600 mt-1"></p>
        </div>
    </div>
    
    <button id="next-image" class="absolute right-4 text-white hover:text-gray-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
    
    .aspect-h-12 {
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
        const closeLightbox = document.getElementById('close-lightbox');
        const prevImage = document.getElementById('prev-image');
        const nextImage = document.getElementById('next-image');
        
        let currentIndex = 0;
        const galleries = @json($galleries);
        
        // Open lightbox when clicking on a gallery item
        galleryItems.forEach((item, index) => {
            item.addEventListener('click', function() {
                currentIndex = index;
                updateLightbox();
                lightboxModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            });
        });
        
        // Close lightbox
        closeLightbox.addEventListener('click', function() {
            lightboxModal.classList.add('hidden');
            document.body.style.overflow = ''; // Re-enable scrolling
        });
        
        // Close lightbox when clicking outside the content
        lightboxModal.addEventListener('click', function(e) {
            if (e.target === lightboxModal) {
                lightboxModal.classList.add('hidden');
                document.body.style.overflow = '';
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
        
        // Update lightbox content
        function updateLightbox() {
            const gallery = galleries[currentIndex];
            const imgSrc = gallery.source === 'local' 
                ? gallery.image_path 
                : gallery.image_url;
            
            lightboxImage.src = imgSrc;
            lightboxTitle.textContent = gallery.title;
            lightboxDescription.textContent = gallery.description || '';
            
            // Add error handling for images
            lightboxImage.onerror = function() {
                this.src = '{{ asset('img/placeholder.jpg') }}';
                this.onerror = null;
            };
            
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
                lightboxModal.classList.add('hidden');
                document.body.style.overflow = '';
            } else if (e.key === 'ArrowLeft') {
                currentIndex = (currentIndex - 1 + galleries.length) % galleries.length;
                updateLightbox();
            } else if (e.key === 'ArrowRight') {
                currentIndex = (currentIndex + 1) % galleries.length;
                updateLightbox();
            }
        });
    });
</script>
@endpush