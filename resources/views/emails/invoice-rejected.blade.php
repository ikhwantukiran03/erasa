<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Rejected - Enak Rasa Wedding Hall</title>
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
        .reason-box {
            background-color: #FEF2F2;
            border-left: 4px solid #EF4444;
            padding: 15px;
            margin: 20px 0;
        }
        .action-box {
            background-color: #FFF7ED;
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
        .btn-warning {
            background-color: #F59E0B;
        }
        .btn-warning:hover {
            background-color: #D97706;
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
            <div class="rejection-badge">❌ PAYMENT REJECTED</div>
        </div>
        
        <div class="content">
            <h2>Hello {{ $customer_name }},</h2>
            
            <p>We regret to inform you that your payment proof has been <strong>REJECTED</strong> and requires resubmission.</p>
            
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
                    <span class="detail-label">Rejected On:</span>
                    <span class="detail-value">{{ $rejected_date }}</span>
                </div>
            </div>
            
            @if(isset($staff_notes) && $staff_notes)
            <div class="reason-box">
                <h4>📝 Reason for Rejection:</h4>
                <p>{{ $staff_notes }}</p>
            </div>
            @endif
            
            <div class="action-box">
                <h4>⚠️ Action Required</h4>
                <p>Please review the rejection reason above and resubmit your payment proof with the correct information.</p>
                
                <h5>Common reasons for rejection:</h5>
                <ul>
                    <li>Unclear or blurry payment receipt</li>
                    <li>Incorrect payment amount</li>
                    <li>Wrong bank account details</li>
                    <li>Missing transaction reference</li>
                    <li>Payment made to wrong account</li>
                </ul>
            </div>
            
            <div class="action-box">
                <h4>🔄 Next Steps</h4>
                <p>To resubmit your payment proof:</p>
                <ol>
                    <li>Log in to your account</li>
                    <li>Go to your booking details</li>
                    <li>Upload a new, clear payment proof</li>
                    <li>Ensure all details are correct</li>
                </ol>
                
                <a href="{{ route('user.bookings.show', $booking_id) }}" class="btn btn-warning">Resubmit Payment Proof</a>
                <a href="{{ route('user.bookings') }}" class="btn">My Bookings</a>
            </div>
            
            <div class="reason-box">
                <h4>💡 Payment Information</h4>
                <p><strong>Bank Details:</strong></p>
                <ul>
                    <li><strong>Bank:</strong> Bank Negara Malaysia</li>
                    <li><strong>Account Number:</strong> 1234-5678-9012</li>
                    <li><strong>Account Name:</strong> Enak Rasa Wedding Hall</li>
                    <li><strong>Reference:</strong> BOOKING-{{ $booking_id }}</li>
                </ul>
                <p><strong>Important:</strong> Please ensure you include the booking reference in your payment.</p>
            </div>
        </div>
        
        <div class="contact-info">
            <strong>Need Help?</strong><br>
            If you have any questions about the rejection or need assistance with resubmission:<br><br>
            📧 Email: rasa.enak@gmail.com<br>
            📞 Phone: 013-331 4389<br>
            🌐 Website: {{ config('app.url') }}
        </div>
        
        <div class="footer">
            <p><strong>Best regards,</strong></p>
            <p><strong>Enak Rasa Wedding Hall Staff</strong></p>
            <p><em>Making your dream wedding come true</em></p>
            <br>
            <p>We apologize for any inconvenience and appreciate your understanding.</p>
            <p>&copy; {{ date('Y') }} Enak Rasa Wedding Hall. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 