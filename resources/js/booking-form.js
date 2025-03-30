// Add this to a separate JS file or to the bottom of home.blade.php inside <script> tags

document.addEventListener('DOMContentLoaded', function() {
    // Package filtering based on venue selection
    const venueSelect = document.getElementById('venue_id');
    const packageSelect = document.getElementById('package_id');
    
    if (venueSelect && packageSelect) {
        // Store all original options
        const allPackageOptions = Array.from(packageSelect.options).map(option => {
            return {
                value: option.value,
                text: option.text,
                venue: option.text.includes('(') ? 
                    option.text.split('(')[1].replace(')', '').trim() : null
            };
        });
        
        venueSelect.addEventListener('change', function() {
            const selectedVenue = this.options[this.selectedIndex].text;
            const selectedVenueId = this.value;
            
            // Reset package select
            packageSelect.innerHTML = '<option value="">-- Select Package --</option>';
            
            if (!selectedVenueId) {
                // If no venue selected, add all packages
                allPackageOptions.forEach(option => {
                    if (option.value) { // Skip the empty default option
                        const newOption = document.createElement('option');
                        newOption.value = option.value;
                        newOption.text = option.text;
                        packageSelect.add(newOption);
                    }
                });
            } else {
                // If venue selected, only add packages for that venue
                allPackageOptions.forEach(option => {
                    if (option.value && option.venue === selectedVenue) {
                        const newOption = document.createElement('option');
                        newOption.value = option.value;
                        newOption.text = option.text;
                        packageSelect.add(newOption);
                    }
                });
            }
        });
    }
    
    // Form validation and animations
    const bookingForm = document.querySelector('#booking form');
    
    if (bookingForm) {
        // Add focus animation to form fields
        const formInputs = bookingForm.querySelectorAll('input, select, textarea');
        
        formInputs.forEach(input => {
            // Add focus class to parent element when input is focused
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('input-focused');
            });
            
            // Remove focus class when input loses focus
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('input-focused');
            });
            
            // Add class when input has value
            input.addEventListener('input', function() {
                if (this.value) {
                    this.classList.add('has-value');
                } else {
                    this.classList.remove('has-value');
                }
            });
            
            // Check initial value
            if (input.value) {
                input.classList.add('has-value');
            }
        });
        
        // Smooth scroll to form on "Book Now" button click
        const bookNowButtons = document.querySelectorAll('.cta-btn, a[href="#booking"]');
        
        bookNowButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                if (this.getAttribute('href') === '#booking') {
                    e.preventDefault();
                    const bookingSection = document.getElementById('booking');
                    if (bookingSection) {
                        bookingSection.scrollIntoView({ 
                            behavior: 'smooth',
                            block: 'start'
                        });
                        
                        // Focus the first input after scrolling
                        setTimeout(() => {
                            const firstInput = bookingForm.querySelector('input:not([type=hidden])');
                            if (firstInput) firstInput.focus();
                        }, 800);
                    }
                }
            });
        });
        
        // Form submission animation
        bookingForm.addEventListener('submit', function(e) {
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.innerHTML = '<span class="animate-pulse">Submitting...</span>';
                submitButton.disabled = true;
            }
        });
    }
});