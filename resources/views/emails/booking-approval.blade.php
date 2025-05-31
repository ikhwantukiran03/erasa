<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Approved - Enak Rasa Wedding Hall</title>
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
        .approval-badge {
            background-color: #10B981;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            display: inline-block;
            margin: 20px 0;
            font-weight: bold;
        }
        .booking-details {
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
        .payment-info {
            background-color: #FEF3C7;
            border-left: 4px solid #F59E0B;
            padding: 15px;
            margin: 20px 0;
        }
        .account-info {
            background-color: #F3E8FF;
            border-left: 4px solid #8B5CF6;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            border-top: 1px solid #eee;
            padding-top: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .contact-info {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #8B5CF6;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">Enak Rasa Wedding Hall</div>
            <p>Your Dream Wedding Venue</p>
            <div class="approval-badge">🎉 BOOKING APPROVED! 🎉</div>
        </div>
        
        <div class="content">
            <h2>Hello {{ $name }},</h2>
            
            <p>Congratulations! We are delighted to inform you that your booking request has been <strong>APPROVED</strong>!</p>
            
            <div class="booking-details">
                <h3>📋 Booking Details</h3>
                
                @if(isset($package) && $package && $type !== 'reservation' && $type !== 'appointment')
                <div class="detail-row">
                    <span class="detail-label">Package:</span>
                    <span class="detail-value">{{ $package->name }}</span>
                </div>
                @endif
                
                @if(isset($venue) && $venue)
                <div class="detail-row">
                    <span class="detail-label">Venue:</span>
                    <span class="detail-value">{{ $venue->name }}</span>
                </div>
                @endif
                
                @if(isset($event_date) && $event_date)
                <div class="detail-row">
                    <span class="detail-label">Event Date:</span>
                    <span class="detail-value">{{ $event_date->format('d M Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Session:</span>
                    <span class="detail-value">{{ ucfirst($session) }}</span>
                </div>
                @endif
            </div>
            
            @if(isset($admin_notes) && $admin_notes)
            <div class="confirmation-box">
                <h4>📝 Additional Information:</h4>
                <p>{{ $admin_notes }}</p>
            </div>
            @endif
            
            <div class="confirmation-box">
                <h4>🎫 BOOKING CONFIRMATION</h4>
                <p>Your booking has been confirmed with reference number: <strong>B-{{ $booking_id }}</strong></p>
            </div>
            
            @if(isset($booking_type) && $booking_type === 'wedding')
            <div class="payment-info">
                <h4>⚠️ IMPORTANT: Deposit Payment Required</h4>
                <p>Your booking status is 'Waiting for Deposit'. Please complete your payment to confirm your reservation.</p>
                
                <h5>💳 Payment Details:</h5>
                <ul>
                    <li><strong>Bank:</strong> Bank Negara Malaysia</li>
                    <li><strong>Account Number:</strong> 1234-5678-9012</li>
                    <li><strong>Reference:</strong> BOOKING-{{ $booking_id }}</li>
                </ul>
                
                <p><strong>Please reply to this email with your payment proof once completed.</strong></p>
            </div>
            @endif
            
            @if(isset($account_created) && $account_created)
            <div class="account-info">
                <h4>💻 YOUR ACCOUNT DETAILS</h4>
                <p>We've created an account for you on our website:</p>
                <ul>
                    <li><strong>Email:</strong> {{ $email }}</li>
                    <li><strong>Password:</strong> {{ $password }}</li>
                </ul>
                
                <p><strong>⚠️ IMPORTANT:</strong> For security purposes, please log in and change your password as soon as possible.</p>
                
                <a href="{{ route('login') }}" class="btn">Login to Your Account</a>
                <a href="{{ route('profile.edit') }}" class="btn">Change Password</a>
            </div>
            @else
            <div class="account-info">
                <h4>🔑 ACCESS YOUR BOOKING</h4>
                <p>You can view the details of your booking by logging into your account and navigating to "My Bookings".</p>
                
                <a href="{{ route('login') }}" class="btn">Login to Your Account</a>
            </div>
            @endif
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
            <p>If you have any questions, please don't hesitate to contact us.</p>
            <p>&copy; {{ date('Y') }} Enak Rasa Wedding Hall. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 