<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .content {
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Password Reset</h2>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>Your password has been reset. Here is your new password:</p>
            <p style="font-size: 18px; font-weight: bold; text-align: center; padding: 10px; background-color: #f8f9fa; border-radius: 5px;">
                {{ $password }}
            </p>
            <p>Please login with this new password and change it immediately for security reasons.</p>
            <p>If you did not request this password reset, please contact our support team immediately.</p>
        </div>
        <div class="footer">
            <p>This is an automated message, please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} Enak Rasa Wedding Hall. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 