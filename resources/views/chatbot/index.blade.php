@extends('layouts.app')

@section('title', 'Wedding Assistant - Enak Rasa Wedding Hall')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-light via-white to-secondary py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="flex items-center justify-center mb-4">
                <div class="bg-primary rounded-full p-4 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                        <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-display font-bold text-dark">Wedding Assistant</h1>
                    <p class="text-gray-600 text-lg">Get instant answers about our wedding hall services</p>
                </div>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-medium overflow-hidden">
                <!-- Chat Header -->
                <div class="bg-primary text-white p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                                    <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Enak Rasa Assistant</h3>
                                <p class="text-white text-opacity-80 text-sm">Online • Ready to help</p>
                            </div>
                        </div>
                        <div class="text-white text-opacity-60">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Chat Messages Area -->
                <div id="chatbot-container" class="h-96 overflow-y-auto p-6 space-y-4 bg-gray-50">
                    <!-- Welcome Message -->
                    <div class="chat-message assistant">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                                        <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3 bg-white rounded-lg py-3 px-4 max-w-3xl shadow-sm">
                                <p class="text-gray-800">Welcome to Enak Rasa Wedding Hall! 🎉</p>
                                <p class="text-gray-800 mt-2">I'm here to help you with any questions about our wedding packages, venues, booking process, and more. What would you like to know?</p>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <button class="suggested-question text-xs px-3 py-1 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors">
                                        How to book a wedding hall?
                                    </button>
                                    <button class="suggested-question text-xs px-3 py-1 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors">
                                        What packages are available?
                                    </button>
                                    <button class="suggested-question text-xs px-3 py-1 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors">
                                        Check venue availability
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Questions -->
                <div class="px-6 py-4 bg-white border-t border-gray-200">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Quick Questions:</h4>
                    <div class="flex flex-wrap gap-2">
                        <button class="quick-question text-sm px-3 py-2 bg-primary bg-opacity-10 text-primary rounded-lg hover:bg-opacity-20 transition-colors">
                            How much does it cost?
                        </button>
                        <button class="quick-question text-sm px-3 py-2 bg-primary bg-opacity-10 text-primary rounded-lg hover:bg-opacity-20 transition-colors">
                            What's included in packages?
                        </button>
                        <button class="quick-question text-sm px-3 py-2 bg-primary bg-opacity-10 text-primary rounded-lg hover:bg-opacity-20 transition-colors">
                            How to make payment?
                        </button>
                        <button class="quick-question text-sm px-3 py-2 bg-primary bg-opacity-10 text-primary rounded-lg hover:bg-opacity-20 transition-colors">
                            Contact information
                        </button>
                        <button class="quick-question text-sm px-3 py-2 bg-primary bg-opacity-10 text-primary rounded-lg hover:bg-opacity-20 transition-colors">
                            Cancellation policy
                        </button>
                        <button class="quick-question text-sm px-3 py-2 bg-primary bg-opacity-10 text-primary rounded-lg hover:bg-opacity-20 transition-colors">
                            Customization options
                        </button>
                    </div>
                </div>

                <!-- Chat Input -->
                <div class="p-6 bg-white border-t border-gray-200">
                    <form id="chatbot-form" class="flex space-x-4">
                        <div class="flex-1">
                            <input 
                                type="text" 
                                id="user-input" 
                                placeholder="Ask me anything about our wedding hall services..." 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                                autocomplete="off"
                            >
                        </div>
                        <button 
                            type="submit" 
                            class="bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Send
                        </button>
                    </form>
                </div>
            </div>

            <!-- Help Section -->
            <div class="mt-8 bg-white rounded-xl shadow-soft p-6">
                <h3 class="text-xl font-display font-semibold text-dark mb-4">Need More Help?</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('public.venues') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="bg-primary rounded-lg p-2 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm12 12V8l-4 4-4-4v8h8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-dark">Browse Venues</h4>
                            <p class="text-sm text-gray-600">View our beautiful halls</p>
                        </div>
                    </a>
                    <a href="{{ route('booking-requests.create') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="bg-primary rounded-lg p-2 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-dark">Book Now</h4>
                            <p class="text-sm text-gray-600">Start your booking</p>
                        </div>
                    </a>
                    <a href="{{ route('user.chat.index') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="bg-primary rounded-lg p-2 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-2 0c0 .993-.241 1.929-.668 2.754l-1.524-1.525a3.997 3.997 0 00.078-2.183l1.562-1.562C17.756 8.249 18 9.1 18 10zm-5.165 3.913l1.58 1.58A5.98 5.98 0 0110 16a5.98 5.98 0 01-4.415-1.507l1.58-1.58A3.996 3.996 0 0010 14c.657 0 1.267-.164 1.835-.087zm-6.748-1.159l1.562 1.562A3.996 3.996 0 006 10c0-.993.241-1.929.668-2.754l1.524 1.525a3.997 3.997 0 00-.078 2.183zM10 6a3.996 3.996 0 00-1.835.087L6.587 4.509A5.98 5.98 0 0110 4c1.657 0 3.157.672 4.243 1.757L12.835 7.165A3.996 3.996 0 0010 6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-dark">Contact Us</h4>
                            <p class="text-sm text-gray-600">Chat with our staff</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatbotForm = document.getElementById('chatbot-form');
        const userInput = document.getElementById('user-input');
        const chatbotContainer = document.getElementById('chatbot-container');
        const suggestedQuestions = document.querySelectorAll('.suggested-question');
        const quickQuestions = document.querySelectorAll('.quick-question');
        
        // Function to add user message to chat
        function addUserMessage(message) {
            const messageElement = document.createElement('div');
            messageElement.className = 'chat-message user';
            messageElement.innerHTML = `
                <div class="flex items-start justify-end">
                    <div class="mr-3 bg-primary text-white rounded-lg py-3 px-4 max-w-3xl shadow-sm">
                        <p>${escapeHtml(message)}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            `;
            chatbotContainer.appendChild(messageElement);
            chatbotContainer.scrollTop = chatbotContainer.scrollHeight;
        }
        
        // Function to add assistant message to chat
        function addAssistantMessage(responseData) {
            const messageElement = document.createElement('div');
            messageElement.className = 'chat-message assistant';
            
            let linksHtml = '';
            if (responseData.links && responseData.links.length > 0) {
                linksHtml = '<div class="mt-3 flex flex-wrap gap-2">';
                responseData.links.forEach(link => {
                    linksHtml += `<a href="${escapeHtml(link.url)}" class="inline-flex items-center px-3 py-1 border border-primary text-sm font-medium rounded-full text-primary hover:bg-primary hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">${escapeHtml(link.text)}</a>`;
                });
                linksHtml += '</div>';
            }
            
            // Convert text with newlines to HTML with <br> tags
            const formattedText = escapeHtml(responseData.text).replace(/\n/g, '<br>');
            
            // Source indicator
            let sourceIndicator = '';
            if (responseData.source === 'faq') {
                sourceIndicator = '<div class="mt-2 flex items-center text-xs text-gray-500"><svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>FAQ Response</div>';
            } else if (responseData.source === 'database') {
                sourceIndicator = '<div class="mt-2 flex items-center text-xs text-gray-500"><svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>Live Data</div>';
            }
            
            messageElement.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                                <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 bg-white rounded-lg py-3 px-4 max-w-3xl shadow-sm">
                        <p class="text-gray-800">${formattedText}</p>
                        ${sourceIndicator}
                        ${linksHtml}
                    </div>
                </div>
            `;
            chatbotContainer.appendChild(messageElement);
            chatbotContainer.scrollTop = chatbotContainer.scrollHeight;
        }
        
        // Function to show typing indicator
        function showTypingIndicator() {
            const typingElement = document.createElement('div');
            typingElement.className = 'chat-message assistant typing-indicator';
            typingElement.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                                <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 bg-white rounded-lg py-3 px-4 shadow-sm">
                        <div class="flex space-x-1">
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                        </div>
                    </div>
                </div>
            `;
            chatbotContainer.appendChild(typingElement);
            chatbotContainer.scrollTop = chatbotContainer.scrollHeight;
        }
        
        // Function to remove typing indicator
        function removeTypingIndicator() {
            const typingIndicator = document.querySelector('.typing-indicator');
            if (typingIndicator) {
                typingIndicator.remove();
            }
        }
        
        // Function to handle chat submission
        function handleChatSubmission(message) {
            if (!message.trim()) return;
            
            addUserMessage(message);
            showTypingIndicator();
            
            // Send request to server
            fetch('{{ route("chatbot.query") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ query: message })
            })
            .then(response => response.json())
            .then(data => {
                removeTypingIndicator();
                addAssistantMessage(data.response);
            })
            .catch(error => {
                removeTypingIndicator();
                console.error('Error:', error);
                addAssistantMessage({
                    text: 'Sorry, I encountered an error. Please try again later or contact our staff directly.',
                    source: 'system',
                    links: [
                        { text: 'Contact Us', url: '{{ route("public.feedback") }}' }
                    ]
                });
            });
            
            userInput.value = '';
        }
        
        // Form submission
        chatbotForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleChatSubmission(userInput.value);
        });
        
        // Suggested questions
        suggestedQuestions.forEach(question => {
            question.addEventListener('click', function() {
                handleChatSubmission(this.textContent.trim());
            });
        });
        
        // Quick questions
        quickQuestions.forEach(question => {
            question.addEventListener('click', function() {
                handleChatSubmission(this.textContent.trim());
            });
        });
        
        // Utility function to escape HTML
        function escapeHtml(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
        
        // Check for URL query parameter 'q' to pre-fill a question
        const urlParams = new URLSearchParams(window.location.search);
        const prefilledQuestion = urlParams.get('q');
        
        if (prefilledQuestion) {
            // Wait a brief moment for chat to initialize
            setTimeout(() => {
                handleChatSubmission(prefilledQuestion);
            }, 500);
        }
        
        // Focus input on load
        userInput.focus();
    });
</script>
@endpush
@endsection