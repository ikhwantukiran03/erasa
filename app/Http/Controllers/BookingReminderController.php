<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class BookingReminderController extends Controller
{
    public function sendPaymentReminders()
    {
        $today = Carbon::today();
        $dueDate = $today->copy()->addDays(5);

        $bookings = Booking::where('payment_due_date', $dueDate)
            ->where('status', '!=', 'cancelled')
            ->get();

        foreach ($bookings as $booking) {
            try {
                Mail::send('emails.payment-reminder', ['booking' => $booking], function($message) use ($booking) {
                    $message->to($booking->user->email)
                           ->subject('Payment Reminder - Enak Rasa Wedding Hall');
                });
                Log::info("Payment reminder sent for booking #{$booking->id}");
            } catch (\Exception $e) {
                Log::error("Failed to send payment reminder for booking #{$booking->id}: {$e->getMessage()}");
            }
        }

        return response()->json(['message' => 'Payment reminders sent successfully']);
    }

    public function sendEventReminders()
    {
        $today = Carbon::today();
        $eventDate = $today->copy()->addDays(3);

        $bookings = Booking::where('event_date', $eventDate)
            ->where('status', '!=', 'cancelled')
            ->get();

        foreach ($bookings as $booking) {
            try {
                Mail::send('emails.event-reminder', ['booking' => $booking], function($message) use ($booking) {
                    $message->to($booking->user->email)
                           ->subject('Upcoming Event Reminder - Enak Rasa Wedding Hall');
                });
                Log::info("Event reminder sent for booking #{$booking->id}");
            } catch (\Exception $e) {
                Log::error("Failed to send event reminder for booking #{$booking->id}: {$e->getMessage()}");
            }
        }

        return response()->json(['message' => 'Event reminders sent successfully']);
    }

    public function sendAllReminders()
    {
        $this->sendPaymentReminders();
        $this->sendEventReminders();
        return response()->json(['message' => 'All reminders sent successfully']);
    }
} 