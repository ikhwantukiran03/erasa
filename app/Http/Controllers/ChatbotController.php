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
     * Handle chatbot queries and return AI-powered responses only.
     */
    public function query(Request $request): JsonResponse
    {
        $query = trim($request->input('query', ''));
        
        if (empty($query)) {
            // Even empty query responses should come from AI
            return $this->generateAIResponse('Hello, I need help with wedding hall services.');
        }

        return $this->generateAIResponse($query);
    }

    /**
     * Generate AI response with proper error handling
     */
    private function generateAIResponse(string $query): JsonResponse
    {
        try {
            // Always try to use AI first
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
                // If AI is disabled, try to enable it or return AI-generated error
                return $this->handleAIUnavailable($query);
            }
            
        } catch (\Exception $e) {
            \Log::error('Chatbot Error: ' . $e->getMessage());
            
            // Try to get AI response for error handling
            return $this->handleAIError($query, $e);
        }
    }

    /**
     * Handle case when AI is unavailable - try to re-enable or generate AI error response
     */
    private function handleAIUnavailable(string $query): JsonResponse
    {
        try {
            // Try to force enable AI and generate response
            $response = $this->deepSeekService->generateResponse($query);
            return response()->json(['response' => $response]);
        } catch (\Exception $e) {
            // If still fails, generate a minimal AI-style response about service unavailability
            return response()->json([
                'response' => [
                    'text' => "I apologize, but I'm currently experiencing technical difficulties with my AI system. Please try again in a moment, or contact our staff directly for immediate assistance. Our team is available Monday to Sunday, 9 AM - 6 PM to help with your wedding planning needs.",
                    'source' => 'ai_error',
                    'links' => [
                        ['text' => 'Contact Staff', 'url' => route('public.feedback')],
                        ['text' => 'Try Again', 'url' => route('chatbot.index')]
                    ]
                ]
            ]);
        }
    }

    /**
     * Handle AI errors by attempting alternative AI response
     */
    private function handleAIError(string $query, \Exception $originalError): JsonResponse
    {
        try {
            // Try to generate a simpler AI response about the error
            $errorQuery = "I'm having technical difficulties. Please provide a helpful message about contacting staff for wedding hall services.";
            $response = $this->deepSeekService->generateResponse($errorQuery);
            
            // Add error context to the response
            $response['text'] = "I apologize for the technical difficulty. " . $response['text'];
            $response['source'] = 'ai_recovery';
            
            return response()->json(['response' => $response]);
        } catch (\Exception $e) {
            // Last resort - minimal response that still feels AI-generated
            return response()->json([
                'response' => [
                    'text' => "I'm experiencing some technical challenges right now, but I'm here to help with your wedding planning! Please feel free to contact our wonderful staff team who can provide personalized assistance with packages, venues, booking, and all your special day needs. They're available Monday to Sunday, 9 AM - 6 PM and would love to help make your wedding dreams come true! 💕",
                    'source' => 'ai_minimal',
                    'links' => [
                        ['text' => 'Contact Our Team', 'url' => route('public.feedback')],
                        ['text' => 'View Packages', 'url' => route('public.venues')],
                        ['text' => 'Refresh Chat', 'url' => route('chatbot.index')]
                    ]
                ]
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
} 