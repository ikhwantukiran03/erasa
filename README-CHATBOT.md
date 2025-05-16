# AI-Powered Wedding Venue Chatbot

This chatbot provides an interactive way for users to get information about wedding venues, packages, and booking processes. It combines rule-based responses with AI-powered natural language processing for a more engaging user experience.

## Features

- Natural language understanding powered by DeepSeek's AI models
- Fallback to rule-based responses when AI is unavailable
- AI fallback for queries not handled by the rule-based system
- Contextual awareness of user authentication state
- Smart suggestion of relevant links based on conversation context
- Floating chat widget available throughout the site
- Full-page chat interface for extended conversations

## Setup

### Configuration

1. Add the following variables to your `.env` file:

```
# ChatBot AI Configuration
CHATBOT_USE_AI=true
DEEPSEEK_API_KEY=your_deepseek_api_key_here
DEEPSEEK_API_URL=https://api.deepseek.com/v1/chat/completions
DEEPSEEK_MODEL=deepseek-chat
DEEPSEEK_TEMPERATURE=0.7
DEEPSEEK_MAX_TOKENS=300
CHATBOT_DEBUG=false
```

2. Set `CHATBOT_USE_AI=true` to enable AI responses as the primary handling method. If set to `false`, the system will use rule-based responses.

3. Get an API key from DeepSeek and set it as the `DEEPSEEK_API_KEY` value. 
   **Important:** Even if `CHATBOT_USE_AI` is set to `false`, adding a valid API key will enable AI fallback for queries not handled by rules.

### Model Configuration

- `DEEPSEEK_MODEL`: The DeepSeek model to use (default: deepseek-chat)
- `DEEPSEEK_TEMPERATURE`: Controls randomness of responses (0-1, where higher values mean more random)
- `DEEPSEEK_MAX_TOKENS`: Maximum length of responses

### Debugging

Set `CHATBOT_DEBUG=true` to enable detailed logging of API requests and responses.

## Usage

### User Interface

The chatbot is available in two forms:

1. **Floating chat widget** - Available on all pages (bottom right corner)
2. **Full chat page** - Available at `/chatbot` or by clicking the Chat Assistant link in the navigation

### How It Works

1. User queries are sent to the backend
2. If AI is enabled (`CHATBOT_USE_AI=true`), the system attempts to generate an AI response first
3. If AI fails or is disabled, the system uses rule-based responses
4. If the rule-based system cannot find a specific match AND a DeepSeek API key is configured:
   - The system will try using AI as a fallback, even if `CHATBOT_USE_AI` is set to false
   - The chatbot has several ways to detect unhandled queries:
     * When the default generic response text is returned
     * When a complex query receives only the generic default links
   - This ensures that uncommon, complex, or specialized questions still receive helpful responses
5. The response is displayed with relevant action links when applicable

## Extending

### Adding New Rule-Based Responses

To add new rule-based responses, edit the `generateRuleBasedResponse` method in `app/Http/Controllers/ChatbotController.php`.

```php
if (strpos($query, 'your_keyword') !== false) {
    return [
        'text' => 'Your response text here',
        'links' => [
            ['text' => 'Link Text', 'url' => route('route.name')],
        ],
        'source' => 'rule'
    ];
}
```

### Enhancing AI Context

To modify the AI system prompt or add new context, edit the `generateAIResponse` method in `app/Http/Controllers/ChatbotController.php`.

## Limitations

- AI responses require an internet connection to access DeepSeek's API
- API usage is subject to DeepSeek's rate limits and pricing
- The chatbot does not maintain conversation history beyond the current session 