<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Verified - Enak Rasa Wedding Hall</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #8B5CF6;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #8B5CF6;
        }
        .verification-badge {
            background-color: #10B981;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            display: inline-block;
            margin: 20px 0;
            font-weight: bold;
        }
        .payment-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eee;
        }
        .detail-label {
            font-weight: bold;
            color: #666;
        }
        .detail-value {
            color: #333;
        }
        .confirmation-box {
            background-color: #EBF8FF;
            border-left: 4px solid #3B82F6;
            padding: 15px;
            margin: 20px 0;
        }
        .next-steps {
            background-color: #F0FDF4;
            border-left: 4px solid #10B981;
            padding: 15px;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            background-color: #8B5CF6;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 5px;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #7C3AED;
        }
        .contact-info {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .footer {
            border-top: 1px solid #eee;
            padding-top: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">Enak Rasa Wedding Hall</div>
            <p>Your Dream Wedding Venue</p>
            <div class="verification-badge">✅ PAYMENT VERIFIED! ✅</div>
        </div>
        
        <div class="content">
            <h2>Hello {{ $customer_name }},</h2>
            
            <p>Great news! Your payment has been <strong>VERIFIED</strong> and processed successfully!</p>
            
            <div class="payment-details">
                <h3>💳 Payment Details</h3>
                
                <div class="detail-row">
                    <span class="detail-label">Booking Reference:</span>
                    <span class="detail-value">B-{{ $booking_id }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Payment Type:</span>
                    <span class="detail-value">
                        @if($payment_type === 'deposit')
                            @if($booking_type === 'wedding')
                                Deposit Payment (RM 3,000)
                            @else
                                Deposit Payment (50%)
                            @endif
                        @elseif($payment_type === 'second_deposit')
                            Second Deposit (50% of total)
                        @elseif($payment_type === 'balance')
                            @if($booking_type === 'wedding')
                                Balance Payment (Remaining amount)
                            @else
                                Balance Payment (50%)
                            @endif
                        @elseif($payment_type === 'full_payment')
                            Full Payment (100%)
                        @else
                            {{ ucfirst(str_replace('_', ' ', $payment_type)) }}
                        @endif
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Amount:</span>
                    <span class="detail-value">RM {{ number_format($amount, 2) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Verified On:</span>
                    <span class="detail-value">{{ $verified_date }}</span>
                </div>
                
                @if(isset($staff_notes) && $staff_notes)
                <div class="detail-row">
                    <span class="detail-label">Staff Notes:</span>
                    <span class="detail-value">{{ $staff_notes }}</span>
                </div>
                @endif
            </div>
            
            <div class="confirmation-box">
                <h4>🎉 Payment Status Updated</h4>
                <p>Your booking payment has been successfully verified and your booking status has been updated accordingly.</p>
            </div>
            
            @if($payment_type === 'deposit' && $booking_type === 'wedding')
            <div class="next-steps">
                <h4>📋 Next Steps</h4>
                <p>Your deposit has been verified! Here's what happens next:</p>
                <ul>
                    <li><strong>Second Deposit:</strong> Due 6 months before your event date</li>
                    <li><strong>Balance Payment:</strong> Due 1 month before your event date</li>
                </ul>
                <p>We'll send you reminders when each payment is due.</p>
            </div>
            @elseif($payment_type === 'deposit' && $booking_type !== 'wedding')
            <div class="next-steps">
                <h4>📋 Next Steps</h4>
                <p>Your deposit has been verified! Here's what happens next:</p>
                <ul>
                    <li><strong>Balance Payment:</strong> Due 1 week before your event date</li>
                </ul>
                <p>We'll send you a reminder when the balance payment is due.</p>
            </div>
            @elseif($payment_type === 'second_deposit')
            <div class="next-steps">
                <h4>📋 Next Steps</h4>
                <p>Your second deposit has been verified! Here's what happens next:</p>
                <ul>
                    <li><strong>Balance Payment:</strong> Due 1 month before your event date</li>
                </ul>
                <p>We'll send you a reminder when the balance payment is due.</p>
            </div>
            @elseif($payment_type === 'balance' || $payment_type === 'full_payment')
            <div class="next-steps">
                <h4>🎊 All Payments Complete!</h4>
                <p>Congratulations! All payments for your booking have been completed and verified.</p>
                <p>Our team will now begin preparing for your special event. We'll be in touch with final details closer to your event date.</p>
            </div>
            @endif
            
            <div class="confirmation-box">
                <h4>🔗 Manage Your Booking</h4>
                <p>You can view your booking details, payment history, and manage customizations through your account:</p>
                
                <a href="{{ route('user.bookings.show', $booking_id) }}" class="btn">View Booking Details</a>
                <a href="{{ route('user.bookings') }}" class="btn">My Bookings</a>
            </div>
        </div>
        
        <div class="contact-info">
            <strong>Contact Information:</strong><br>
            📧 Email: rasa.enak@gmail.com<br>
            📞 Phone: 013-331 4389<br>
            🌐 Website: {{ config('app.url') }}
        </div>
        
        <div class="footer">
            <p><strong>Best regards,</strong></p>
            <p><strong>Enak Rasa Wedding Hall Staff</strong></p>
            <p><em>Making your dream wedding come true</em></p>
            <br>
            <p>Thank you for choosing Enak Rasa Wedding Hall! 🙏</p>
            <p>If you have any questions about your payment or booking, please don't hesitate to contact us.</p>
            <p>&copy; {{ date('Y') }} Enak Rasa Wedding Hall. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 