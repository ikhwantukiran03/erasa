<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Chatbot AI Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the chatbot AI functionality.
    | You can enable or disable AI integration and provide your API key.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Enable AI
    |--------------------------------------------------------------------------
    |
    | Set this to true to use DeepSeek's models for chatbot responses.
    | When false, the system will use rule-based responses.
    |
    */
    'use_ai' => env('CHATBOT_USE_AI', true),

    /*
    |--------------------------------------------------------------------------
    | DeepSeek API Key
    |--------------------------------------------------------------------------
    |
    | Your DeepSeek API key for accessing their models.
    | Never commit this key directly in the code.
    |
    */
    'deepseek_api_key' => env('DEEPSEEK_API_KEY', ''),
    
    /*
    |--------------------------------------------------------------------------
    | DeepSeek API URL
    |--------------------------------------------------------------------------
    |
    | The API endpoint for DeepSeek's chat completions API.
    |
    */
    'deepseek_api_url' => env('DEEPSEEK_API_URL', 'https://api.deepseek.com/v1/chat/completions'),

    /*
    |--------------------------------------------------------------------------
    | Model Configuration
    |--------------------------------------------------------------------------
    |
    | Configure which model to use and its parameters
    |
    */
    'model' => env('DEEPSEEK_MODEL', 'deepseek-chat'),
    'temperature' => env('DEEPSEEK_TEMPERATURE', 0.7),
    'max_tokens' => env('DEEPSEEK_MAX_TOKENS', 300),

    /*
    |--------------------------------------------------------------------------
    | User Context
    |--------------------------------------------------------------------------
    |
    | Whether to include user-specific context in AI prompts
    |
    */
    'include_user_context' => true,

    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    |
    | When enabled, will log API requests and responses for debugging
    |
    */
    'debug' => env('CHATBOT_DEBUG', false),
];