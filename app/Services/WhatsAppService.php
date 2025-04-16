<?php

namespace App\Services;

class WhatsAppService
{
    /**
     * Send a WhatsApp message.
     *
     * @param string $to The recipient's WhatsApp number with country code
     * @param string $message The message to send
     * @return bool Whether the message was sent successfully
     */
    public function sendMessage($to, $message)
    {
        // This is a placeholder for the actual WhatsApp API integration
        // In a real application, you would use a service like Twilio, MessageBird, or WhatsApp Business API
        
        // For demonstration purposes, we'll just log the message
        \Illuminate\Support\Facades\Log::info("WhatsApp message to $to: $message");
        
        // Return true as if the message was sent successfully
        return true;
    }

    /**
     * Generate a WhatsApp URL that can be opened to start a conversation with the given number.
     *
     * @param string $number The WhatsApp number with country code
     * @param string $message Optional pre-filled message
     * @return string The WhatsApp URL
     */
    public function generateWhatsAppUrl($number, $message = '')
    {
        // Remove any non-numeric characters from the number
        $cleanNumber = preg_replace('/[^0-9]/', '', $number);
        
        // URL encode the message
        $encodedMessage = urlencode($message);
        
        // Generate the WhatsApp URL
        return "https://wa.me/{$cleanNumber}?text={$encodedMessage}";
    }
}