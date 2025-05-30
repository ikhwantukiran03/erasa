<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Package;
use App\Models\Venue;

class DeepSeekService
{
    protected $apiKey;
    protected $apiUrl;
    protected $model;
    protected $temperature;
    protected $maxTokens;
    protected $debug;

    public function __construct()
    {
        // Use environment variables with fallback for security
        $this->apiKey = env('DEEPSEEK_API_KEY') ?: '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';
        $this->apiUrl = env('DEEPSEEK_API_URL') ?: 'https://openrouter.ai/api/v1/chat/completions';
        $this->model = env('DEEPSEEK_MODEL') ?: 'deepseek/deepseek-r1';
        $this->temperature = (float) (env('DEEPSEEK_TEMPERATURE') ?: 0.7);
        $this->maxTokens = (int) (env('DEEPSEEK_MAX_TOKENS') ?: 300);
        $this->debug = env('CHATBOT_DEBUG') ?: false;
    }

    /**
     * Generate AI response for wedding hall chatbot
     */
    public function generateResponse(string $userMessage): array
    {
        // Always attempt AI response - no fallbacks
        $businessContext = $this->getBusinessContext();
        $systemPrompt = $this->createSystemPrompt($businessContext);

        $messages = [
            [
                'role' => 'system',
                'content' => $systemPrompt
            ],
            [
                'role' => 'user',
                'content' => $userMessage
            ]
        ];

        $payload = [
            'model' => $this->model,
            'messages' => $messages,
            'temperature' => $this->temperature,
            'max_tokens' => $this->maxTokens,
            'stream' => false
        ];

        if ($this->debug) {
            Log::info('DeepSeek API Request:', $payload);
        }

        // Make API request with retries
        $response = $this->makeAPIRequest($payload);
        
        if (!$response->successful()) {
            throw new Exception('DeepSeek API request failed: ' . $response->body());
        }

        $responseData = $response->json();
        
        if (!isset($responseData['choices'][0]['message']['content'])) {
            throw new Exception('Invalid response format from DeepSeek API');
        }

        $aiResponse = $responseData['choices'][0]['message']['content'];

        // Determine relevant links based on the response content
        $links = $this->generateRelevantLinks($userMessage, $aiResponse);

        return [
            'text' => $aiResponse,
            'source' => 'ai',
            'links' => $links,
            'model_used' => $this->model,
            'tokens_used' => $responseData['usage']['total_tokens'] ?? null
        ];
    }

    /**
     * Make API request with retry logic
     */
    private function makeAPIRequest(array $payload, int $retries = 3): \Illuminate\Http\Client\Response
    {
        $lastException = null;
        
        for ($i = 0; $i < $retries; $i++) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                    'HTTP-Referer' => 'https://enakrasa.com',
                    'X-Title' => 'Enak Rasa Wedding Hall Chatbot',
                ])->timeout(30)->post($this->apiUrl, $payload);

                if ($response->successful()) {
                    return $response;
                }
                
                // If not successful, log and continue to retry
                Log::warning("DeepSeek API attempt " . ($i + 1) . " failed: " . $response->body());
                
            } catch (Exception $e) {
                $lastException = $e;
                Log::warning("DeepSeek API attempt " . ($i + 1) . " exception: " . $e->getMessage());
            }
            
            // Wait before retry (exponential backoff)
            if ($i < $retries - 1) {
                sleep(pow(2, $i));
            }
        }
        
        // If all retries failed, throw the last exception or create a new one
        if ($lastException) {
            throw $lastException;
        }
        
        throw new Exception('All API retry attempts failed');
    }

    /**
     * Create system prompt with business context
     */
    private function createSystemPrompt(array $businessContext): string
    {
        return "You are a helpful AI assistant for Enak Rasa Wedding Hall, a premium wedding venue in Malaysia. 

BUSINESS INFORMATION:
- Name: Enak Rasa Wedding Hall
- Location: No. 3, Jalan Lintang 1 Off Jalan Lintang, Kuala Lumpur, Malaysia
- Phone: 013-331 4389
- Email: rasa.enak@gmail.com
- Operating Hours: Monday to Sunday, 9 AM - 6 PM

SERVICES & POLICIES:
- We offer complete wedding packages including venue, catering, and decorations
- Deposit required: RM3000
- Full payment due: 1 month before event
- Cancellation policy: No refund after deposit paid
- We provide halal-certified catering with traditional Malay and international cuisine
- Custom decorations and themes available
- Wedding card creation services available

CURRENT PACKAGES:
{$businessContext['packages']}

AVAILABLE VENUES:
{$businessContext['venues']}

INSTRUCTIONS:
1. Always be helpful, friendly, and professional
2. Provide accurate information about our services
3. If asked about pricing, mention our packages and suggest viewing details
4. For booking inquiries, guide them to our booking process
5. If you don't know specific details, suggest contacting our staff
6. Keep responses concise but informative (under 250 words)
7. Always maintain a warm, welcoming tone suitable for wedding planning
8. If asked about availability, direct them to our calendar or booking form
9. Use natural, conversational language
10. Include relevant emojis when appropriate to make responses more engaging

Remember: You're helping couples plan their special day, so be enthusiastic and supportive!";
    }

    /**
     * Get current business context from database
     */
    private function getBusinessContext(): array
    {
        // Get current packages
        $packages = Package::with('venue')->take(5)->get();
        $packageInfo = "Available Wedding Packages:\n";
        foreach ($packages as $package) {
            $packageInfo .= "• {$package->name} - RM " . number_format($package->price, 2) . "\n";
            $packageInfo .= "  Venue: {$package->venue->name} (Capacity: {$package->venue->capacity} guests)\n";
        }

        // Get venues
        $venues = Venue::take(5)->get();
        $venueInfo = "Our Wedding Venues:\n";
        foreach ($venues as $venue) {
            $venueInfo .= "• {$venue->name} - Capacity: {$venue->capacity} guests\n";
            $venueInfo .= "  Location: {$venue->location}\n";
        }

        return [
            'packages' => $packageInfo,
            'venues' => $venueInfo
        ];
    }

    /**
     * Generate relevant links based on conversation context
     */
    private function generateRelevantLinks(string $userMessage, string $aiResponse): array
    {
        $links = [];
        $message = strtolower($userMessage);
        $response = strtolower($aiResponse);

        // Booking-related links
        if (str_contains($message, 'book') || str_contains($message, 'reservation') || 
            str_contains($response, 'book') || str_contains($response, 'reservation')) {
            $links[] = ['text' => 'Book Now', 'url' => route('booking-requests.create')];
            $links[] = ['text' => 'Check Calendar', 'url' => route('booking.calendar')];
        }

        // Package-related links
        if (str_contains($message, 'package') || str_contains($message, 'price') || 
            str_contains($response, 'package') || str_contains($response, 'price')) {
            $links[] = ['text' => 'View Packages', 'url' => route('public.venues')];
            $links[] = ['text' => 'Get Recommendation', 'url' => route('package-recommendation.index')];
        }

        // Venue-related links
        if (str_contains($message, 'venue') || str_contains($message, 'hall') || 
            str_contains($response, 'venue') || str_contains($response, 'hall')) {
            $links[] = ['text' => 'Browse Venues', 'url' => route('public.venues')];
        }

        // Contact-related links
        if (str_contains($message, 'contact') || str_contains($message, 'phone') || 
            str_contains($response, 'contact') || str_contains($response, 'staff')) {
            $links[] = ['text' => 'Contact Us', 'url' => route('public.feedback')];
        }

        // Wedding card links
        if (str_contains($message, 'card') || str_contains($message, 'invitation') || 
            str_contains($response, 'card') || str_contains($response, 'invitation')) {
            $links[] = ['text' => 'Create Wedding Card', 'url' => route('wedding-cards.create')];
        }

        // Remove duplicates and limit to 3 links
        $uniqueLinks = array_unique($links, SORT_REGULAR);
        return array_slice($uniqueLinks, 0, 3);
    }

    /**
     * Generate conversation context for follow-up messages
     */
    public function generateContextualResponse(array $conversationHistory, string $newMessage): array
    {
        $businessContext = $this->getBusinessContext();
        $systemPrompt = $this->createSystemPrompt($businessContext);

        // Build conversation messages
        $messages = [
            [
                'role' => 'system',
                'content' => $systemPrompt
            ]
        ];

        // Add conversation history (limit to last 6 messages to stay within token limits)
        $recentHistory = array_slice($conversationHistory, -6);
        foreach ($recentHistory as $message) {
            $messages[] = [
                'role' => $message['role'],
                'content' => $message['content']
            ];
        }

        // Add new message
        $messages[] = [
            'role' => 'user',
            'content' => $newMessage
        ];

        $payload = [
            'model' => $this->model,
            'messages' => $messages,
            'temperature' => $this->temperature,
            'max_tokens' => $this->maxTokens,
            'stream' => false
        ];

        if ($this->debug) {
            Log::info('DeepSeek Contextual API Request:', $payload);
        }

        $response = $this->makeAPIRequest($payload);

        if (!$response->successful()) {
            throw new Exception('DeepSeek API request failed: ' . $response->body());
        }

        $responseData = $response->json();
        
        if (!isset($responseData['choices'][0]['message']['content'])) {
            throw new Exception('Invalid response format from DeepSeek API');
        }
        
        $aiResponse = $responseData['choices'][0]['message']['content'];
        $links = $this->generateRelevantLinks($newMessage, $aiResponse);

        return [
            'text' => $aiResponse,
            'source' => 'ai',
            'links' => $links,
            'model_used' => $this->model,
            'tokens_used' => $responseData['usage']['total_tokens'] ?? null
        ];
    }

    /**
     * Check if AI is enabled - always return true to force AI usage
     */
    public function isEnabled(): bool
    {
        // Always return true to ensure AI is used
        // Only return false if there's absolutely no API key
        return !empty($this->apiKey);
    }
} 