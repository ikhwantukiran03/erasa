# Gmail SMTP Setup for Enak Rasa Wedding Hall

## Your Gmail Configuration

Based on your provided credentials, here's your complete email configuration for the `.env` file:

**⚠️ IMPORTANT: Remove all spaces from your Gmail App Password!**

```env
# Gmail SMTP Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=ikhwantukiran03@gmail.com
MAIL_PASSWORD=fqdvxecedomwchjg
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=ikhwantukiran03@gmail.com
MAIL_FROM_NAME="Enak Rasa Wedding Hall"

# Staff Email Configuration (using your Gmail)
STAFF_EMAIL_ADDRESS=ikhwantukiran03@gmail.com
STAFF_EMAIL_NAME="Enak Rasa Wedding Hall Staff"
```

## ⚠️ Password Format Fix

Your original password: `fqdv xece domw chjg`
**Corrected password (no spaces)**: `fqdvxecedomwchjg`

Gmail app passwords should not have spaces when used in `.env` files.

## Setup Instructions

1. **Add to your .env file**: Copy the configuration above and add it to your `.env` file in the root directory of your Laravel project.

2. **Clear Laravel caches** (run these commands):
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

## How It Works

With this configuration:

- **All booking emails will be sent from**: `ikhwantukiran03@gmail.com`
- **Display name will be**: "Enak Rasa Wedding Hall Staff"
- **Email types sent**:
  - Booking approval emails
  - Booking rejection emails
  - General system notifications

## Email Templates

Your emails will include professional signatures:

```
Best regards,
Enak Rasa Wedding Hall Staff
Making your dream wedding come true
```

## Testing the Setup

To test if your email configuration is working:

1. Create a test booking request through your website
2. Process the request as staff (approve or reject)
3. Check if the email is sent successfully
4. Check Laravel logs at `storage/logs/laravel.log` for any errors

## Gmail App Password Notes

✅ **Your App Password**: `fqdvxecedomwchjg` (spaces removed)
- This is a Gmail App Password (not your regular Gmail password)
- Keep this secure and don't share it
- If you need to regenerate it, go to Google Account Settings > Security > App Passwords

## Security Recommendations

1. **Never commit your .env file** to version control
2. **Keep your app password secure**
3. **Monitor email sending logs** for any suspicious activity
4. **Consider using a dedicated email** for system notifications if this becomes a production system

## Troubleshooting

If emails are not being sent:

1. **Check Gmail settings**: Ensure 2-factor authentication is enabled and app passwords are allowed
2. **Verify credentials**: Make sure the username and app password are correct
3. **Check Laravel logs**: Look at `storage/logs/laravel.log` for error messages
4. **Test SMTP connection**: You can use tools like Telnet to test the SMTP connection

## Email Flow

When a staff member approves/rejects a booking:

1. **System triggers** → EmailService
2. **EmailService configures** → From: ikhwantukiran03@gmail.com
3. **Email sent via Gmail SMTP** → To: Customer's email
4. **Customer receives** → Professional email with Enak Rasa branding

Your email system is now ready to send professional booking notifications from your Gmail account! 