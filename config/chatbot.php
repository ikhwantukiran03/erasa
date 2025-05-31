<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Chatbot Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the wedding hall chatbot
    | functionality. The chatbot is configured to use AI-only responses.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | AI Configuration
    |--------------------------------------------------------------------------
    */
    'ai_enabled' => true, // Always use AI
    'ai_only' => true,    // Never use preset responses
    'force_ai' => true,   // Force AI even if there are issues

    /*
    |--------------------------------------------------------------------------
    | Chatbot Name and Identity
    |--------------------------------------------------------------------------
    */
    'name' => 'Enak Rasa AI Assistant',
    'greeting' => 'Welcome to Enak Rasa Wedding Hall! 🎉',
    'description' => 'I\'m your AI-powered assistant here to help you with any questions about our wedding packages, venues, booking process, and more.',

    /*
    |--------------------------------------------------------------------------
    | Contact Information
    |--------------------------------------------------------------------------
    */
    'contact' => [
        'address' => 'No. 3, Jalan Lintang 1 Off Jalan Lintang, Kuala Lumpur, Malaysia',
        'phone' => '013-331 4389',
        'email' => 'rasa.enak@gmail.com',
        'hours' => 'Monday to Sunday, 9 AM - 6 PM',
    ],

    /*
    |--------------------------------------------------------------------------
    | Business Information
    |--------------------------------------------------------------------------
    */
    'business' => [
        'deposit_percentage' => 30,
        'payment_due_weeks' => 2,
        'advance_booking_months' => '3-6',
        'cancellation_policy' => [
            '30+' => 80,  // 80% refund for 30+ days
            '15-29' => 50, // 50% refund for 15-29 days
            '<15' => 0,    // No refund for less than 15 days
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | AI Response Settings
    |--------------------------------------------------------------------------
    */
    'ai_settings' => [
        'max_retries' => 3,
        'retry_delay' => 2, // seconds
        'timeout' => 30,    // seconds
        'temperature' => 0.7,
        'max_tokens' => 300,
        'model' => 'deepseek/deepseek-r1',
    ],

    /*
    |--------------------------------------------------------------------------
    | Response Settings
    |--------------------------------------------------------------------------
    */
    'responses' => [
        'max_packages_shown' => 5,
        'max_venues_shown' => 5,
        'default_currency' => 'RM',
        'show_source_indicators' => true,
        'conversation_history_limit' => 10,
    ],

    /*
    |--------------------------------------------------------------------------
    | Error Handling
    |--------------------------------------------------------------------------
    */
    'error_handling' => [
        'log_errors' => true,
        'show_technical_errors' => false,
        'fallback_to_contact' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Quick Questions (AI will generate responses for these)
    |--------------------------------------------------------------------------
    */
    'quick_questions' => [
        'How much does it cost?',
        'What\'s included in packages?',
        'How to make payment?',
        'Contact information',
        'Cancellation policy',
        'Customization options',
    ],

    /*
    |--------------------------------------------------------------------------
    | Suggested Questions (AI will generate responses for these)
    |--------------------------------------------------------------------------
    */
    'suggested_questions' => [
        'How to book a wedding hall?',
        'What packages are available?',
        'Check venue availability',
    ],
];