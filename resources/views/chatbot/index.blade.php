@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-800">Wedding Venue Assistant</h1>
            <p class="text-gray-600 mt-2">Ask me anything about our wedding venues, packages, or booking process</p>
        </div>

        <div class="bg-white overflow-hidden shadow-lg rounded-lg">
            <div class="p-6">
                <div id="chatbot-container" class="flex flex-col space-y-4 h-96 overflow-y-auto mb-4 p-4 border border-gray-200 rounded-lg">
                    <!-- Chat messages will appear here -->
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
                            <div class="ml-3 bg-gray-100 rounded-lg py-3 px-4 max-w-3xl">
                                <p class="text-gray-800">👋 Hello! I'm your wedding venue assistant. How can I help you today?</p>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <button class="quick-question inline-flex items-center px-3 py-1 border border-primary text-sm font-medium rounded-full text-primary hover:bg-primary hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                        How do I book a venue?
                                    </button>
                                    <button class="quick-question inline-flex items-center px-3 py-1 border border-primary text-sm font-medium rounded-full text-primary hover:bg-primary hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                        What packages do you offer?
                                    </button>
                                    <button class="quick-question inline-flex items-center px-3 py-1 border border-primary text-sm font-medium rounded-full text-primary hover:bg-primary hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                        Check venue availability
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <form id="chatbot-form" class="flex items-center">
                    @csrf
                    <input type="text" id="user-input" name="query" class="flex-grow form-input rounded-l-lg border-gray-300 focus:ring-primary focus:border-primary" placeholder="Type your question here...">
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-r-lg hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
                
                <div class="mt-4">
                    <h3 class="text-sm font-medium text-gray-500">Suggested questions:</h3>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <button class="suggested-question text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200">
                            What is your cancellation policy?
                        </button>
                        <button class="suggested-question text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200">
                            How much deposit do I need to pay?
                        </button>
                        <button class="suggested-question text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200">
                            Which package is best for me?
                        </button>
                        <button class="suggested-question text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200">
                            How can I contact a wedding specialist?
                        </button>
                        @auth
                        <button class="suggested-question text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200">
                            Show my booking status
                        </button>
                        @endauth
                    </div>
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
                    <div class="mr-3 bg-primary text-white rounded-lg py-3 px-4 max-w-3xl">
                        <p>${message}</p>
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
                    linksHtml += `<a href="${link.url}" class="inline-flex items-center px-3 py-1 border border-primary text-sm font-medium rounded-full text-primary hover:bg-primary hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">${link.text}</a>`;
                });
                linksHtml += '</div>';
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
                    <div class="ml-3 bg-gray-100 rounded-lg py-3 px-4 max-w-3xl">
                        <p class="text-gray-800">${responseData.text.replace(/\n/g, '<br>')}</p>
                        ${responseData.source === 'ai' ? '<div class="mt-2 flex items-center text-xs text-gray-500"><svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Powered by DeepSeek AI</div>' : ''}
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
                    <div class="ml-3 bg-gray-100 rounded-lg py-3 px-4">
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
                    text: 'Sorry, I encountered an error. Please try again later.',
                    links: []
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