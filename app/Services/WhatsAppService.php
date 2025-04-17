<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $sid;
    protected $token;
    protected $fromNumber;
    protected $client;

    /**
     * Create a new WhatsApp service instance.
     */
    public function __construct()
    {
        $this->sid = env('TWILIO_SID');
        $this->token = env('TWILIO_AUTH_TOKEN');
        $this->fromNumber = env('TWILIO_WHATSAPP_FROM');
        
        if ($this->sid && $this->token) {
            $this->client = new Client($this->sid, $this->token);
        }
    }

    /**
     * Send a WhatsApp message.
     *
     * @param string $to The recipient's WhatsApp number with country code
     * @param string $message The message to send
     * @return bool Whether the message was sent successfully
     */
    public function sendMessage($to, $message)
    {
        // Clean the phone number - remove any non-numeric characters
        $to = preg_replace('/[^0-9]/', '', $to);
        
        // Add "whatsapp:" prefix required by Twilio
        $formattedTo = "whatsapp:+$to";
        $formattedFrom = "whatsapp:" . $this->fromNumber;
        
        try {
            // Check if Twilio client is available
            if (!$this->client) {
                Log::warning("Twilio credentials not configured. WhatsApp message not sent.");
                Log::info("WhatsApp message to $to: $message");
                return false;
            }
            
            // Send message via Twilio
            $this->client->messages->create(
                $formattedTo,
                [
                    'from' => $formattedFrom,
                    'body' => $message
                ]
            );
            
            Log::info("WhatsApp message sent to $to successfully");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send WhatsApp message: " . $e->getMessage());
            return false;
        }
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