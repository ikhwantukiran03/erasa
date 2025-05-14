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
        
        // Check if we should use AI for this query
        if (config('chatbot.use_ai', false) && !empty(config('chatbot.deepseek_api_key'))) {
            try {
                $response = $this->generateAIResponse($query);
                return response()->json(['response' => $response]);
            } catch (\Exception $e) {
                Log::error('AI Chatbot error: ' . $e->getMessage());
                // Fallback to rule-based response if AI fails
                $response = $this->generateRuleBasedResponse($query);
                return response()->json(['response' => $response]);
            }
        }
        
        // Use rule-based response if AI is not configured
        $response = $this->generateRuleBasedResponse($query);
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
            $userContext .= "They have {$bookingCount} bookings and {$requestCount} booking requests in our system.";
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
            $aiText = $result['choices'][0]['message']['content'] ?? 'I apologize, but I couldn\'t generate a response. Please try again.';
            
            // Determine relevant links based on the query and AI response
            $links = $this->determineRelevantLinks($query, $aiText);
            
            return [
                'text' => $aiText,
                'links' => $links,
                'source' => 'ai'
            ];
        }
        
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
        $combinedText = $query . ' ' . $aiText;
        $combinedText = strtolower($combinedText);
        
        // Check for venue-related content
        if (strpos($combinedText, 'venue') !== false || strpos($combinedText, 'location') !== false) {
            $links[] = ['text' => 'View Wedding Venues', 'url' => route('public.venues')];
        }
        
        // Check for booking-related content
        if (strpos($combinedText, 'book') !== false || strpos($combinedText, 'reserve') !== false || 
            strpos($combinedText, 'request') !== false) {
            $links[] = ['text' => 'Submit Booking Request', 'url' => route('booking-requests.create')];
        }
        
        // Check for package-related content
        if (strpos($combinedText, 'package') !== false || strpos($combinedText, 'price') !== false || 
            strpos($combinedText, 'cost') !== false) {
            $links[] = ['text' => 'Find the Perfect Package', 'url' => route('package-recommendation.index')];
        }
        
        // Check for calendar/date-related content
        if (strpos($combinedText, 'date') !== false || strpos($combinedText, 'calendar') !== false || 
            strpos($combinedText, 'availability') !== false || strpos($combinedText, 'available') !== false) {
            $links[] = ['text' => 'View Booking Calendar', 'url' => route('booking.calendar')];
        }
        
        // Add a link for logged-in users to view their bookings
        if (Auth::check() && (strpos($combinedText, 'my booking') !== false || strpos($combinedText, 'my request') !== false)) {
            $links[] = ['text' => 'View My Bookings', 'url' => route('user.bookings')];
        }
        
        return $links;
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