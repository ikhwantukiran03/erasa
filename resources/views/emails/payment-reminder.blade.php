<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Reminder - Enak Rasa Wedding Hall</title>
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
        .reminder-badge {
            background-color: #F59E0B;
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
        .reminder-box {
            background-color: #FEF3C7;
            border-left: 4px solid #F59E0B;
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
            <div class="reminder-badge">⚠️ PAYMENT DUE SOON! ⚠️</div>
        </div>
        
        <div class="content">
            <h2>Hello {{ $booking->user->name }},</h2>
            
            <p>This is a friendly reminder that your payment for booking at Enak Rasa Wedding Hall is due in 5 days.</p>
            
            <div class="payment-details">
                <h3>💳 Payment Details</h3>
                
                <div class="detail-row">
                    <span class="detail-label">Booking Reference:</span>
                    <span class="detail-value">B-{{ $booking->id }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Amount Due:</span>
                    <span class="detail-value">RM {{ number_format($booking->total_amount, 2) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Due Date:</span>
                    <span class="detail-value">{{ $booking->payment_due_date->format('d F Y') }}</span>
                </div>
            </div>
            
            <div class="reminder-box">
                <h4>📝 Important Reminder</h4>
                <p>Please ensure your payment is made before the due date to avoid any inconvenience.</p>
                <p>If you have already made the payment, please disregard this reminder.</p>
            </div>
            
            <div class="reminder-box">
                <h4>🔗 Manage Your Booking</h4>
                <p>You can view your booking details and make payments through your account:</p>
                
                <a href="{{ route('user.bookings.show', $booking->id) }}" class="btn">View Booking Details</a>
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