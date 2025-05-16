<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Venue;
use App\Models\BookingRequest;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Display the chatbot interface
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('chatbot.index');
    }

    /**
     * Process chatbot queries and return responses
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function query(Request $request)
    {
        $query = strtolower($request->input('query'));
        $useAI = config('chatbot.use_ai', false) && !empty(config('chatbot.deepseek_api_key'));
        
        // First try with AI if it's enabled
        if ($useAI) {
            try {
                $response = $this->generateAIResponse($query);
                return response()->json(['response' => $response]);
            } catch (\Exception $e) {
                Log::error('AI Chatbot error: ' . $e->getMessage(), [
                    'query' => $query,
                    'trace' => $e->getTraceAsString()
                ]);
                // Fallback to rule-based response if AI fails
                $response = $this->generateRuleBasedResponse($query);
                return response()->json(['response' => $response]);
            }
        }
        
        // Use rule-based response if AI is not configured as primary
        $response = $this->generateRuleBasedResponse($query);
        
        // Check if rule-based response didn't find a good match
        $isGenericResponse = false;
        
        // Detect default fallback response
        if ($response['text'] === "I'm here to help with your wedding venue booking questions. You can ask about our venues, packages, booking process, availability, or get personalized recommendations.") {
            $isGenericResponse = true;
        }
        
        // Check for very short queries that might have been misunderstood
        if (strlen($query) > 10 && count(explode(' ', $query)) > 2 && !$isGenericResponse) {
            // For more complex queries, check if the response has very generic links
            $defaultLinks = [
                ['text' => 'Browse Wedding Venues', 'url' => route('public.venues')],
                ['text' => 'Find Your Perfect Package', 'url' => route('package-recommendation.index')],
                ['text' => 'Check Availability', 'url' => route('booking.calendar')]
            ];
            
            // If the response has exactly these default links, it might be a generic response
            if (count($response['links']) === 3) {
                $matchCount = 0;
                foreach ($response['links'] as $link) {
                    foreach ($defaultLinks as $defaultLink) {
                        if ($link['text'] === $defaultLink['text']) {
                            $matchCount++;
                            break;
                        }
                    }
                }
                
                if ($matchCount === 3) {
                    $isGenericResponse = true;
                }
            }
        }
        
        // If we have a generic response and DeepSeek is configured, try AI as a fallback
        if ($isGenericResponse && !empty(config('chatbot.deepseek_api_key'))) {
            try {
                Log::info('Using AI fallback for unhandled query', ['query' => $query]);
                $aiResponse = $this->generateAIResponse($query);
                return response()->json(['response' => $aiResponse]);
            } catch (\Exception $e) {
                Log::error('AI fallback error: ' . $e->getMessage(), [
                    'query' => $query,
                    'trace' => $e->getTraceAsString()
                ]);
                // Keep the original rule-based response if AI fails
            }
        }
        
        return response()->json(['response' => $response]);
    }
    
    /**
     * Generate responses using DeepSeek's API
     *
     * @param string $query
     * @return array
     */
    private function generateAIResponse($query)
    {
        // Get data about venues and packages to provide context to the AI
        $venueCount = Venue::count();
        $packageCount = Package::count();
        $venues = Venue::all()->pluck('name')->join(', ');
        
        // Prepare user authentication context
        $userContext = '';
        if (Auth::check()) {
            $user = Auth::user();
            $bookingCount = Booking::where('user_id', $user->id)->count();
            $requestCount = BookingRequest::where('user_id', $user->id)->count();
            $userContext = "The user is logged in as {$user->name}. ";
            $userContext .= "They have {$bookingCount} " . ($bookingCount == 1 ? 'booking' : 'bookings');
            $userContext .= " and {$requestCount} booking " . ($requestCount == 1 ? 'request' : 'requests') . " in our system.";
        } else {
            $userContext = "The user is not logged in.";
        }
        
        // Create system prompt with wedding venue business context
        $systemPrompt = "You are a helpful wedding venue assistant for Enak Rasa Wedding Hall. ";
        $systemPrompt .= "You help customers with information about wedding venues, packages, booking process, and other related inquiries. ";
        $systemPrompt .= "Be friendly, professional, and concise. ";
        $systemPrompt .= "Here's information about our business: ";
        $systemPrompt .= "We have {$venueCount} wedding venues: {$venues}. ";
        $systemPrompt .= "We offer {$packageCount} different wedding packages with various amenities. ";
        $systemPrompt .= "Our booking process involves: 1) Submitting a booking request 2) Staff approval 3) Payment of 30% deposit 4) Final payment due 14 days before event. ";
        $systemPrompt .= "We have a cancellation policy that allows full refunds for cancellations 30+ days before the event. ";
        $systemPrompt .= "{$userContext}";
        
        // Enable debugging if configured
        if (config('chatbot.debug', false)) {
            Log::debug('DeepSeek API Request', [
                'system_prompt' => $systemPrompt,
                'user_query' => $query,
                'model' => config('chatbot.model', 'deepseek-chat'),
                'temperature' => config('chatbot.temperature', 0.7),
                'max_tokens' => config('chatbot.max_tokens', 300),
            ]);
        }
        
        // Make API request to DeepSeek
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('chatbot.deepseek_api_key'),
            'Content-Type' => 'application/json',
        ])->post(config('chatbot.deepseek_api_url', 'https://api.deepseek.com/v1/chat/completions'), [
            'model' => config('chatbot.model', 'deepseek-chat'),
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $query]
            ],
            'temperature' => config('chatbot.temperature', 0.7),
            'max_tokens' => config('chatbot.max_tokens', 300),
        ]);
        
        // Process the response
        if ($response->successful()) {
            $result = $response->json();
            
            if (config('chatbot.debug', false)) {
                Log::debug('DeepSeek API Response', [
                    'status' => $response->status(),
                    'result' => $result,
                ]);
            }
            
            $aiText = $result['choices'][0]['message']['content'] ?? 'I apologize, but I couldn\'t generate a response. Please try again.';
            
            // Determine relevant links based on the query and AI response
            $links = $this->determineRelevantLinks($query, $aiText);
            
            return [
                'text' => $aiText,
                'links' => $links,
                'source' => 'ai'
            ];
        }
        
        // Log error response for debugging
        Log::error('DeepSeek API request failed', [
            'status' => $response->status(),
            'body' => $response->body(),
            'query' => $query
        ]);
        
        // Fallback if the API request fails
        throw new \Exception('API request failed: ' . $response->body());
    }
    
    /**
     * Determine relevant links based on the query and AI response
     *
     * @param string $query
     * @param string $aiText
     * @return array
     */
    private function determineRelevantLinks($query, $aiText)
    {
        $links = [];
        $combinedText = strtolower($query . ' ' . $aiText);
        
        // Check for venue-related content
        if (preg_match('/\b(venue|location|hall|place|space|site)\b/', $combinedText)) {
            $links[] = ['text' => 'View Wedding Venues', 'url' => route('public.venues')];
        }
        
        // Check for booking-related content
        if (preg_match('/\b(book|reserve|request|reservation|schedule|appointment)\b/', $combinedText)) {
            $links[] = ['text' => 'Submit Booking Request', 'url' => route('booking-requests.create')];
        }
        
        // Check for package-related content
        if (preg_match('/\b(package|price|cost|fee|budget|affordable|expensive|option)\b/', $combinedText)) {
            $links[] = ['text' => 'Find the Perfect Package', 'url' => route('package-recommendation.index')];
        }
        
        // Check for calendar/date-related content
        if (preg_match('/\b(date|calendar|availability|available|when|schedule|time|month|day)\b/', $combinedText)) {
            $links[] = ['text' => 'View Booking Calendar', 'url' => route('booking.calendar')];
        }
        
        // Add a link for logged-in users to view their bookings
        if (Auth::check() && preg_match('/\b(my booking|my request|reservation|status)\b/', $combinedText)) {
            $links[] = ['text' => 'View My Bookings', 'url' => route('user.bookings')];
        }
        
        // Add calendar link if asking about dates for specific months
        if (preg_match('/\b(january|february|march|april|may|june|july|august|september|october|november|december)\b/', $combinedText)) {
            if (!in_array(['text' => 'View Booking Calendar', 'url' => route('booking.calendar')], $links)) {
                $links[] = ['text' => 'View Booking Calendar', 'url' => route('booking.calendar')];
            }
        }
        
        // If asking about contact or help
        if (preg_match('/\b(contact|help|support|phone|email|reach|message|chat)\b/', $combinedText)) {
            $links[] = ['text' => 'Contact a Wedding Specialist', 'url' => '#contact-form'];
        }
        
        // For visualization/images related queries
        if (preg_match('/\b(picture|photo|image|gallery|decoration|look|tour|virtual)\b/', $combinedText)) {
            $links[] = ['text' => 'View Gallery', 'url' => route('public.venues')];
        }
        
        // If no links were added, provide default ones based on most common needs
        if (empty($links)) {
            // Limit to 2 default links to avoid overwhelming the user
            $links[] = ['text' => 'Browse Wedding Venues', 'url' => route('public.venues')];
            $links[] = ['text' => 'Submit Booking Request', 'url' => route('booking-requests.create')];
        }
        
        // Limit to at most 3 links to not overwhelm users
        return array_slice($links, 0, 3);
    }

    /**
     * Generate rule-based responses for when AI is unavailable
     *
     * @param string $query
     * @return array
     */
    private function generateRuleBasedResponse($query)
    {
        // Common FAQ patterns and their responses
        if (strpos($query, 'book') !== false || strpos($query, 'reserve') !== false || strpos($query, 'booking process') !== false) {
            return [
                'text' => 'To book a venue, you can follow these steps:
                    1. Browse our available wedding venues and packages
                    2. Submit a booking request with your preferred date and venue
                    3. Our staff will review your request and contact you
                    4. Once approved, you can confirm by paying the deposit',
                'links' => [
                    ['text' => 'View Wedding Venues', 'url' => route('public.venues')],
                    ['text' => 'Submit Booking Request', 'url' => route('booking-requests.create')]
                ],
                'source' => 'rule'
            ];
        }
        
        if (strpos($query, 'package') !== false || strpos($query, 'pricing') !== false) {
            $packageCount = Package::count();
            
            return [
                'text' => "We offer {$packageCount} different wedding packages to suit various budgets and preferences. Each package includes different amenities and services.",
                'links' => [
                    ['text' => 'Find the Perfect Package', 'url' => route('package-recommendation.index')]
                ],
                'source' => 'rule'
            ];
        }
        
        if (strpos($query, 'venue') !== false || strpos($query, 'location') !== false) {
            $venueCount = Venue::count();
            
            return [
                'text' => "We have {$venueCount} beautiful venues available for your special day. Each venue has different capacity and features.",
                'links' => [
                    ['text' => 'View All Venues', 'url' => route('public.venues')]
                ],
                'source' => 'rule'
            ];
        }
        
        if (strpos($query, 'cancel') !== false || strpos($query, 'cancellation') !== false) {
            return [
                'text' => 'Cancellation policy: You can cancel your booking up to 30 days before the event for a full refund of your deposit. Cancellations within 30 days may be subject to fees. Please contact our staff for specific details related to your booking.',
                'links' => [],
                'source' => 'rule'
            ];
        }
        
        if (strpos($query, 'deposit') !== false || strpos($query, 'payment') !== false) {
            return [
                'text' => 'We require a 30% deposit to secure your booking. The remaining balance is due 14 days before your event date. You can make payments through bank transfer or online payment methods.',
                'links' => [],
                'source' => 'rule'
            ];
        }
        
        if (strpos($query, 'date') !== false || strpos($query, 'availability') !== false || strpos($query, 'calendar') !== false) {
            return [
                'text' => 'You can check venue availability on our booking calendar. This shows you which dates are already reserved and which are available for your event.',
                'links' => [
                    ['text' => 'View Booking Calendar', 'url' => route('booking.calendar')]
                ],
                'source' => 'rule'
            ];
        }
        
        if (strpos($query, 'recommendation') !== false || strpos($query, 'suggest') !== false || strpos($query, 'which package') !== false) {
            return [
                'text' => 'Not sure which package is right for you? Our package recommendation tool can help you find the perfect match based on your budget, guest count, and preferences.',
                'links' => [
                    ['text' => 'Get Package Recommendations', 'url' => route('package-recommendation.index')]
                ],
                'source' => 'rule'
            ];
        }
        
        if (Auth::check()) {
            if (strpos($query, 'my booking') !== false || strpos($query, 'my reservation') !== false) {
                $bookingCount = Booking::where('user_id', Auth::id())->count();
                
                if ($bookingCount > 0) {
                    return [
                        'text' => "You have {$bookingCount} " . ($bookingCount == 1 ? 'booking' : 'bookings') . " in our system. You can view details and manage your bookings from your account.",
                        'links' => [
                            ['text' => 'View My Bookings', 'url' => route('user.bookings')]
                        ],
                        'source' => 'rule'
                    ];
                } else {
                    return [
                        'text' => "You don't have any active bookings. Would you like to make a reservation?",
                        'links' => [
                            ['text' => 'Make a Booking Request', 'url' => route('booking-requests.create')]
                        ],
                        'source' => 'rule'
                    ];
                }
            }
            
            if (strpos($query, 'my request') !== false) {
                $requestCount = BookingRequest::where('user_id', Auth::id())->count();
                
                if ($requestCount > 0) {
                    return [
                        'text' => "You have {$requestCount} booking " . ($requestCount == 1 ? 'request' : 'requests') . " in our system. You can check the status from your account.",
                        'links' => [
                            ['text' => 'View My Requests', 'url' => route('booking-requests.my-requests')]
                        ],
                        'source' => 'rule'
                    ];
                } else {
                    return [
                        'text' => "You don't have any booking requests. Would you like to make one?",
                        'links' => [
                            ['text' => 'Submit Booking Request', 'url' => route('booking-requests.create')]
                        ],
                        'source' => 'rule'
                    ];
                }
            }
        }
        
        if (strpos($query, 'contact') !== false || strpos($query, 'help') !== false || strpos($query, 'support') !== false) {
            return [
                'text' => 'For personalized assistance, you can contact our wedding specialists. They can help answer any specific questions about venues, packages, or the booking process.',
                'links' => [
                    ['text' => 'Contact a Wedding Specialist', 'url' => '#contact-form']
                ],
                'source' => 'rule'
            ];
        }
        
        // Default response for queries that don't match any patterns
        return [
            'text' => "I'm here to help with your wedding venue booking questions. You can ask about our venues, packages, booking process, availability, or get personalized recommendations.",
            'links' => [
                ['text' => 'Browse Wedding Venues', 'url' => route('public.venues')],
                ['text' => 'Find Your Perfect Package', 'url' => route('package-recommendation.index')],
                ['text' => 'Check Availability', 'url' => route('booking.calendar')]
            ],
            'source' => 'rule'
        ];
    }
}