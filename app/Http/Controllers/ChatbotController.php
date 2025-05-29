<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;
use App\Services\DeepSeekService;
use App\Models\Package;
use App\Models\Venue;
use App\Models\Booking;

class ChatbotController extends Controller
{
    protected $deepSeekService;

    public function __construct(DeepSeekService $deepSeekService)
    {
        $this->deepSeekService = $deepSeekService;
    }

    /**
     * Display the chatbot interface.
     */
    public function index()
    {
        return view('chatbot.index');
    }

    /**
     * Handle chatbot queries and return AI-powered or fallback responses.
     */
    public function query(Request $request): JsonResponse
    {
        $query = trim($request->input('query', ''));
        
        if (empty($query)) {
            return response()->json([
                'response' => [
                    'text' => 'Please ask me a question about our wedding hall services.',
                    'source' => 'system',
                    'links' => []
                ]
            ]);
        }

        try {
            // Check if AI is enabled and available
            if ($this->deepSeekService->isEnabled()) {
                // Get conversation history from session
                $conversationHistory = Session::get('chatbot_conversation', []);
                
                // Generate AI response
                if (empty($conversationHistory)) {
                    // First message - no context needed
                    $response = $this->deepSeekService->generateResponse($query);
                } else {
                    // Contextual response with conversation history
                    $response = $this->deepSeekService->generateContextualResponse($conversationHistory, $query);
                }

                // Update conversation history
                $conversationHistory[] = ['role' => 'user', 'content' => $query];
                $conversationHistory[] = ['role' => 'assistant', 'content' => $response['text']];
                
                // Keep only last 10 messages to prevent session from getting too large
                if (count($conversationHistory) > 10) {
                    $conversationHistory = array_slice($conversationHistory, -10);
                }

                Session::put('chatbot_conversation', $conversationHistory);
                
                return response()->json([
                    'response' => $response
                ]);
            } else {
                // Fallback to rule-based responses if AI is disabled
                $response = $this->getResponse(strtolower($query));
                
                return response()->json([
                    'response' => $response
                ]);
            }
            
        } catch (\Exception $e) {
            \Log::error('Chatbot Error: ' . $e->getMessage());
            
            // Fallback to rule-based response on error
            $response = $this->getResponse(strtolower($query));
            
            return response()->json([
                'response' => $response
            ]);
        }
    }

    /**
     * Clear conversation history
     */
    public function clearConversation(Request $request): JsonResponse
    {
        Session::forget('chatbot_conversation');
        
        return response()->json([
            'message' => 'Conversation history cleared successfully.'
        ]);
    }

    /**
     * Get conversation history
     */
    public function getConversationHistory(Request $request): JsonResponse
    {
        $conversationHistory = Session::get('chatbot_conversation', []);
        
        return response()->json([
            'conversation' => $conversationHistory
        ]);
    }

    /**
     * Generate response based on user query.
     */
    private function getResponse(string $query): array
    {
        // Booking related questions
        if ($this->containsKeywords($query, ['book', 'booking', 'reserve', 'reservation', 'how to book'])) {
            return [
                'text' => "To book our wedding hall:\n\n1. Browse our available packages and venues\n2. Fill out the booking request form\n3. Our staff will review and contact you\n4. Complete payment to confirm your booking\n\nWould you like me to show you our packages or booking form?",
                'source' => 'faq',
                'links' => [
                    ['text' => 'View Packages', 'url' => route('public.venues')],
                    ['text' => 'Book Now', 'url' => route('booking-requests.create')]
                ]
            ];
        }

        // Package related questions
        if ($this->containsKeywords($query, ['package', 'packages', 'price', 'pricing', 'cost', 'available packages'])) {
            $packages = Package::with('venue')->take(3)->get();
            $packageText = "Here are our popular wedding packages:\n\n";
            
            foreach ($packages as $package) {
                $packageText .= "• {$package->name} - RM " . number_format($package->price, 2) . "\n";
                $packageText .= "  Venue: {$package->venue->name}\n";
                $packageText .= "  Capacity: {$package->venue->capacity} guests\n\n";
            }
            
            return [
                'text' => $packageText . "Would you like to see more details about any specific package?",
                'source' => 'database',
                'links' => [
                    ['text' => 'View All Packages', 'url' => route('public.venues')],
                    ['text' => 'Get Recommendation', 'url' => route('package-recommendation.index')]
                ]
            ];
        }

        // Venue related questions
        if ($this->containsKeywords($query, ['venue', 'venues', 'hall', 'location', 'capacity'])) {
            $venues = Venue::take(3)->get();
            $venueText = "Our beautiful wedding venues:\n\n";
            
            foreach ($venues as $venue) {
                $venueText .= "• {$venue->name}\n";
                $venueText .= "  Capacity: {$venue->capacity} guests\n";
                $venueText .= "  Location: {$venue->location}\n\n";
            }
            
            return [
                'text' => $venueText . "Each venue comes with complete facilities and can be customized for your special day.",
                'source' => 'database',
                'links' => [
                    ['text' => 'View Venues', 'url' => route('public.venues')],
                    ['text' => 'Check Availability', 'url' => route('booking.calendar')]
                ]
            ];
        }

        // Payment related questions
        if ($this->containsKeywords($query, ['payment', 'pay', 'deposit', 'invoice', 'bank'])) {
            return [
                'text' => "Payment Information:\n\n• We accept bank transfers and online payments\n• A 30% deposit is required to confirm booking\n• Full payment due 2 weeks before event\n• Payment receipts can be uploaded through your booking dashboard\n\nNeed help with payment? Contact our staff for assistance.",
                'source' => 'faq',
                'links' => [
                    ['text' => 'Login to Dashboard', 'url' => route('login')],
                    ['text' => 'Contact Us', 'url' => route('public.feedback')]
                ]
            ];
        }

        // Contact related questions
        if ($this->containsKeywords($query, ['contact', 'phone', 'email', 'address', 'location', 'reach'])) {
            return [
                'text' => "Contact Enak Rasa Wedding Hall:\n\n📍 Address: 123 Wedding Street, Kuala Lumpur, Malaysia\n📞 Phone: +60 123 456 789\n📧 Email: info@enakrasa.com\n\nOur staff are available Monday to Sunday, 9 AM - 6 PM to assist with your wedding planning needs.",
                'source' => 'faq',
                'links' => [
                    ['text' => 'Send Feedback', 'url' => route('public.feedback')],
                    ['text' => 'Book Consultation', 'url' => route('booking-requests.create')]
                ]
            ];
        }

        // Availability related questions
        if ($this->containsKeywords($query, ['available', 'availability', 'calendar', 'date', 'schedule'])) {
            return [
                'text' => "To check availability:\n\n1. Visit our booking calendar to see available dates\n2. Select your preferred date and venue\n3. Submit a booking request\n4. Our staff will confirm availability within 24 hours\n\nPopular dates book quickly, so we recommend booking 3-6 months in advance!",
                'source' => 'faq',
                'links' => [
                    ['text' => 'Check Calendar', 'url' => route('booking.calendar')],
                    ['text' => 'Submit Request', 'url' => route('booking-requests.create')]
                ]
            ];
        }

        // Customization related questions
        if ($this->containsKeywords($query, ['customize', 'customization', 'decoration', 'theme', 'special request'])) {
            return [
                'text' => "We offer extensive customization options:\n\n• Custom decorations and themes\n• Special dietary requirements\n• Additional services (photography, music, etc.)\n• Personalized wedding cards\n• Flexible seating arrangements\n\nAfter booking confirmation, you can request customizations through your dashboard.",
                'source' => 'faq',
                'links' => [
                    ['text' => 'Create Wedding Card', 'url' => route('wedding-cards.create')],
                    ['text' => 'View Packages', 'url' => route('public.venues')]
                ]
            ];
        }

        // Cancellation related questions
        if ($this->containsKeywords($query, ['cancel', 'cancellation', 'refund', 'change date'])) {
            return [
                'text' => "Cancellation Policy:\n\n• Cancellations 30+ days before event: 80% refund\n• Cancellations 15-29 days before: 50% refund\n• Cancellations less than 15 days: No refund\n• Date changes subject to availability and may incur fees\n\nFor cancellations or changes, please contact our staff immediately.",
                'source' => 'faq',
                'links' => [
                    ['text' => 'Contact Staff', 'url' => route('public.feedback')],
                    ['text' => 'Login to Dashboard', 'url' => route('login')]
                ]
            ];
        }

        // Catering related questions
        if ($this->containsKeywords($query, ['food', 'catering', 'menu', 'cuisine', 'dietary'])) {
            return [
                'text' => "Our Catering Services:\n\n• Traditional Malay cuisine\n• International menu options\n• Halal-certified kitchen\n• Vegetarian and special dietary options\n• Professional catering staff\n• Customizable menu based on guest count\n\nMenu details are included in each package. Special requests can be accommodated.",
                'source' => 'faq',
                'links' => [
                    ['text' => 'View Packages', 'url' => route('public.venues')],
                    ['text' => 'Contact for Menu', 'url' => route('public.feedback')]
                ]
            ];
        }

        // Account/Login related questions
        if ($this->containsKeywords($query, ['account', 'login', 'register', 'dashboard', 'profile'])) {
            return [
                'text' => "Account Management:\n\n• Create an account to track your bookings\n• Access your dashboard to view booking status\n• Upload payment receipts\n• Request customizations\n• View booking history\n\nAlready have a booking? Login to manage your reservation.",
                'source' => 'faq',
                'links' => [
                    ['text' => 'Login', 'url' => route('login')],
                    ['text' => 'Register', 'url' => route('register')]
                ]
            ];
        }

        // Default response for unrecognized queries
        return [
            'text' => "I'm here to help with questions about Enak Rasa Wedding Hall! I can assist with:\n\n• Booking procedures\n• Package information\n• Venue details\n• Payment information\n• Contact details\n• Availability\n• Customization options\n\nWhat would you like to know?",
            'source' => 'system',
            'links' => [
                ['text' => 'View Packages', 'url' => route('public.venues')],
                ['text' => 'Book Now', 'url' => route('booking-requests.create')],
                ['text' => 'Check Calendar', 'url' => route('booking.calendar')]
            ]
        ];
    }

    /**
     * Check if query contains any of the specified keywords.
     */
    private function containsKeywords(string $query, array $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (strpos($query, strtolower($keyword)) !== false) {
                return true;
            }
        }
        return false;
    }
} 