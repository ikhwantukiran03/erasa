<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Get the staff email configuration
     */
    private function getStaffEmailConfig()
    {
        return [
            'address' => env('STAFF_EMAIL_ADDRESS', 'staff@enakrasa.com'),
            'name' => env('STAFF_EMAIL_NAME', 'Enak Rasa Wedding Hall Staff')
        ];
    }

    /**
     * Send an email notification.
     *
     * @param string $to The recipient's email address
     * @param string $subject The email subject
     * @param string $message The message content
     * @param array $data Additional data for the email template
     * @return bool Whether the email was sent successfully
     */
    public function sendNotification($to, $subject, $message, $data = [])
    {
        try {
            $staffConfig = $this->getStaffEmailConfig();
            
            Mail::send('emails.notification', [
                'message' => $message,
                'data' => $data
            ], function ($mail) use ($to, $subject, $staffConfig) {
                $mail->to($to)
                     ->subject($subject)
                     ->from($staffConfig['address'], $staffConfig['name']);
            });

            Log::info("Email notification sent to {$to} successfully. Subject: {$subject}");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send email notification: " . $e->getMessage());
            Log::error("Error code: " . $e->getCode());
            Log::error("Error trace: " . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Send booking approval email.
     *
     * @param string $to The recipient's email address
     * @param array $bookingData Booking information
     * @return bool Whether the email was sent successfully
     */
    public function sendBookingApprovalEmail($to, $bookingData)
    {
        $subject = "🎉 Your Booking Request has been APPROVED! - Enak Rasa Wedding Hall";
        
        try {
            $staffConfig = $this->getStaffEmailConfig();
            
            Mail::send('emails.booking-approval', $bookingData, function ($mail) use ($to, $subject, $staffConfig) {
                $mail->to($to)
                     ->subject($subject)
                     ->from($staffConfig['address'], $staffConfig['name']);
            });

            Log::info("Booking approval email sent to {$to} successfully.");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send booking approval email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send booking rejection email.
     *
     * @param string $to The recipient's email address
     * @param array $bookingData Booking information
     * @return bool Whether the email was sent successfully
     */
    public function sendBookingRejectionEmail($to, $bookingData)
    {
        $subject = "Booking Request Update - Enak Rasa Wedding Hall";
        
        try {
            $staffConfig = $this->getStaffEmailConfig();
            
            Mail::send('emails.booking-rejection', $bookingData, function ($mail) use ($to, $subject, $staffConfig) {
                $mail->to($to)
                     ->subject($subject)
                     ->from($staffConfig['address'], $staffConfig['name']);
            });

            Log::info("Booking rejection email sent to {$to} successfully.");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send booking rejection email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send invoice verification email.
     *
     * @param string $to The recipient's email address
     * @param array $invoiceData Invoice and booking information
     * @return bool Whether the email was sent successfully
     */
    public function sendInvoiceVerificationEmail($to, $invoiceData)
    {
        $subject = "✅ Payment Verified - Enak Rasa Wedding Hall";
        
        try {
            $staffConfig = $this->getStaffEmailConfig();
            
            Mail::send('emails.invoice-verified', $invoiceData, function ($mail) use ($to, $subject, $staffConfig) {
                $mail->to($to)
                     ->subject($subject)
                     ->from($staffConfig['address'], $staffConfig['name']);
            });

            Log::info("Invoice verification email sent to {$to} successfully.");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send invoice verification email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send invoice rejection email.
     *
     * @param string $to The recipient's email address
     * @param array $invoiceData Invoice and booking information
     * @return bool Whether the email was sent successfully
     */
    public function sendInvoiceRejectionEmail($to, $invoiceData)
    {
        $subject = "❌ Payment Rejected - Action Required - Enak Rasa Wedding Hall";
        
        try {
            $staffConfig = $this->getStaffEmailConfig();
            
            Mail::send('emails.invoice-rejected', $invoiceData, function ($mail) use ($to, $subject, $staffConfig) {
                $mail->to($to)
                     ->subject($subject)
                     ->from($staffConfig['address'], $staffConfig['name']);
            });

            Log::info("Invoice rejection email sent to {$to} successfully.");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send invoice rejection email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send forgot password email.
     *
     * @param string $to The recipient's email address
     * @param array $data User and new password information
     * @return bool Whether the email was sent successfully
     */
    public function sendForgotPasswordEmail($to, $data)
    {
        $subject = "🔑 Your New Password - Enak Rasa Wedding Hall";
        
        try {
            $staffConfig = $this->getStaffEmailConfig();
            
            Mail::send('emails.forgot-password', $data, function ($mail) use ($to, $subject, $staffConfig) {
                $mail->to($to)
                     ->subject($subject)
                     ->from($staffConfig['address'], $staffConfig['name']);
            });

            Log::info("Forgot password email sent to {$to} successfully.");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send forgot password email: " . $e->getMessage());
            return false;
        }
    }
} 