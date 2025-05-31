<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Chatbot Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the wedding hall chatbot
    | functionality including FAQ responses and behavior settings.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Chatbot Name and Identity
    |--------------------------------------------------------------------------
    */
    'name' => 'Enak Rasa Assistant',
    'greeting' => 'Welcome to Enak Rasa Wedding Hall! 🎉',
    'description' => 'I\'m here to help you with any questions about our wedding packages, venues, booking process, and more.',

    /*
    |--------------------------------------------------------------------------
    | Contact Information
    |--------------------------------------------------------------------------
    */
    'contact' => [
        'address' => '123 Wedding Street, Kuala Lumpur, Malaysia',
        'phone' => '+60 123 456 789',
        'email' => 'info@enakrasa.com',
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
    | Quick Questions
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
    | Suggested Questions
    |--------------------------------------------------------------------------
    */
    'suggested_questions' => [
        'How to book a wedding hall?',
        'What packages are available?',
        'Check venue availability',
    ],

    /*
    |--------------------------------------------------------------------------
    | Response Settings
    |--------------------------------------------------------------------------
    */
    'responses' => [
        'max_packages_shown' => 3,
        'max_venues_shown' => 3,
        'default_currency' => 'RM',
        'show_source_indicators' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Keywords for Intent Recognition
    |--------------------------------------------------------------------------
    */
    'keywords' => [
        'booking' => ['book', 'booking', 'reserve', 'reservation', 'how to book'],
        'packages' => ['package', 'packages', 'price', 'pricing', 'cost', 'available packages'],
        'venues' => ['venue', 'venues', 'hall', 'location', 'capacity'],
        'payment' => ['payment', 'pay', 'deposit', 'invoice', 'bank'],
        'contact' => ['contact', 'phone', 'email', 'address', 'location', 'reach'],
        'availability' => ['available', 'availability', 'calendar', 'date', 'schedule'],
        'customization' => ['customize', 'customization', 'decoration', 'theme', 'special request'],
        'cancellation' => ['cancel', 'cancellation', 'refund', 'change date'],
        'catering' => ['food', 'catering', 'menu', 'cuisine', 'dietary'],
        'account' => ['account', 'login', 'register', 'dashboard', 'profile'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Error Messages
    |--------------------------------------------------------------------------
    */
    'error_messages' => [
        'general' => 'Sorry, I encountered an error. Please try again later or contact our staff directly.',
        'empty_query' => 'Please ask me a question about our wedding hall services.',
        'no_packages' => 'No packages are currently available. Please contact our staff for more information.',
        'no_venues' => 'No venues are currently available. Please contact our staff for more information.',
    ],

    'contact_info' => [
        'address' => 'No. 3, Jalan Lintang 1 Off Jalan Lintang, Kuala Lumpur, Malaysia',
        'phone' => '013-331 4389',
        'email' => 'rasa.enak@gmail.com',
    ],
];