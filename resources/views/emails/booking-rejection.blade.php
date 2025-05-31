<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Request Update - Enak Rasa Wedding Hall</title>
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
        .rejection-badge {
            background-color: #EF4444;
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
        .reason-box {
            background-color: #FEF2F2;
            border-left: 4px solid #EF4444;
            padding: 15px;
            margin: 20px 0;
        }
        .account-info {
            background-color: #F3E8FF;
            border-left: 4px solid #8B5CF6;
            padding: 15px;
            margin: 20px 0;
        }
        .explore-options {
            background-color: #F0FDF4;
            border-left: 4px solid #10B981;
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
        .btn-green {
            background-color: #10B981;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">Enak Rasa Wedding Hall</div>
            <p>Your Dream Wedding Venue</p>
            <div class="rejection-badge">Booking Request Update</div>
        </div>
        
        <div class="content">
            <h2>Hello {{ $name }},</h2>
            
            <p>We regret to inform you that your booking request has been <strong>DECLINED</strong>.</p>
            
            <div class="booking-details">
                <h3>📋 Requested Booking Details</h3>
                
                @if(isset($package) && $package)
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
                @endif
            </div>
            
            <div class="reason-box">
                <h4>📝 Reason for Decline:</h4>
                <p>{{ $admin_notes }}</p>
            </div>
            
            @if(isset($account_created) && $account_created)
            <div class="account-info">
                <h4>💻 YOUR ACCOUNT DETAILS</h4>
                <p>Despite this rejection, we've created an account for you on our website so you can explore other options:</p>
                <ul>
                    <li><strong>Email:</strong> {{ $email }}</li>
                    <li><strong>Password:</strong> {{ $password }}</li>
                </ul>
                
                <p><strong>⚠️ IMPORTANT:</strong> For security purposes, please log in and change your password as soon as possible.</p>
                <p>You can explore other packages and venues that might suit your needs.</p>
                
                <a href="{{ route('login') }}" class="btn">Login to Your Account</a>
                <a href="{{ route('profile.edit') }}" class="btn">Change Password</a>
            </div>
            @else
            <div class="explore-options">
                <h4>🔍 EXPLORE OTHER OPTIONS</h4>
                <p>You can log into your account to explore other packages and venues that might better suit your needs.</p>
                
                <a href="{{ route('login') }}" class="btn">Login to Your Account</a>
            </div>
            @endif
            
            <div class="explore-options">
                <h4>🌟 Alternative Options</h4>
                <p>We have many other beautiful packages and venues available. Please consider exploring:</p>
                <ul>
                    <li>Different event dates that might be available</li>
                    <li>Alternative venue options</li>
                    <li>Modified package selections</li>
                    <li>Customized arrangements to fit your needs</li>
                </ul>
                
                <a href="{{ route('public.venues') }}" class="btn btn-green">View All Venues</a>
                <a href="{{ config('app.url') }}" class="btn btn-green">Browse Packages</a>
            </div>
            
            <p>We apologize for any inconvenience and thank you for considering Enak Rasa Wedding Hall. We hope to have the opportunity to serve you in the future with an alternative arrangement that better suits your needs.</p>
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
            <p>Thank you for considering Enak Rasa Wedding Hall! 🙏</p>
            <p>If you have any questions, please don't hesitate to contact us.</p>
            <p>&copy; {{ date('Y') }} Enak Rasa Wedding Hall. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 