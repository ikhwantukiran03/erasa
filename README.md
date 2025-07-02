# Enak Rasa Wedding Hall Management System

A comprehensive wedding hall management system built with Laravel, featuring booking management, payment processing, AI-powered chatbot, and customer relationship management.

## 🌟 Features

### 👥 User Management
- **Multi-role authentication** (Admin, Staff, Customer)
- **User registration and login** with secure authentication
- **Profile management** with password change functionality
- **Role-based access control** with middleware protection

### 🏛️ Venue & Package Management
- **Venue management** with detailed information and gallery
- **Package creation** with customizable items and pricing tiers
- **Category and item management** for package components
- **Gallery management** with Cloudinary integration for image storage
- **Price management** with multiple pricing options per package

### 📅 Booking System
- **Booking requests** with approval workflow
- **Calendar integration** for availability checking
- **Session management** (Morning/Evening slots)
- **Booking types**: Wedding, Viewing, Reservation, Appointment
- **Automated conflict detection** and resolution
- **Expiry date tracking** for reservations

### 💰 Payment Management
- **Invoice submission** with file upload to Cloudinary
- **Payment verification** workflow for staff
- **Multiple payment types**: Deposit, Second Deposit, Balance, Full Payment
- **Payment schedule calculation** based on booking type
- **Automated email notifications** for payment status updates
- **Bulk payment processing** for staff efficiency

### 🎨 Customization System
- **Package item customization** requests from customers
- **Staff approval workflow** for customizations
- **Detailed customization tracking** and status management

### 🤖 AI-Powered Chatbot
- **DeepSeek AI integration** for intelligent customer support
- **Contextual conversations** with business knowledge
- **Automated responses** for common inquiries
- **Dynamic link generation** based on conversation context
- **Session-based conversation history**

### 💌 Wedding Card Creator
- **Digital wedding invitation** creation
- **Multiple templates** with customizable designs
- **Guest comment system** with moderation
- **Shareable UUID-based links**
- **Venue integration** for automatic address population

### 📧 Communication System
- **Email notifications** for all major events
- **WhatsApp integration** via Twilio (optional)
- **Staff-customer messaging** system
- **Support ticket system** with categorization and replies
- **Automated email templates** for various scenarios

### 📊 Analytics & Reporting
- **Financial reporting** with monthly/yearly summaries
- **Revenue tracking** and invoice analytics
- **Popular package analytics**
- **Booking trend analysis**
- **Dashboard metrics** for quick insights

### 🎯 Promotional System
- **Promotion management** with image uploads
- **Package-specific discounts**
- **Date-based promotional campaigns**
- **Homepage promotional banners**

### ⭐ Feedback System
- **Customer feedback collection** for completed bookings
- **Rating system** (1-5 stars)
- **Feedback moderation** and publishing workflow
- **Public testimonial display**

### 🔍 Package Recommendation
- **AI-powered package suggestions** based on budget and preferences
- **Filtering by venue, capacity, and budget**
- **Intelligent scoring algorithm** for best matches

## 🛠️ Technology Stack

- **Backend**: Laravel 10+
- **Database**: PostgreSQL (with MySQL compatibility)
- **File Storage**: Cloudinary for images and documents
- **AI Service**: DeepSeek via OpenRouter
- **Email**: Laravel Mail with SMTP
- **WhatsApp**: Twilio API
- **Frontend**: Blade templates with Tailwind CSS
- **Authentication**: Laravel Sanctum
- **Broadcasting**: Laravel Echo (for real-time features)

## 📋 Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js & NPM
- PostgreSQL or MySQL database
- Cloudinary account
- DeepSeek API key (via OpenRouter)

## 🚀 Installation

1. **Clone the repository**
```bash
git clone <repository-url>
cd enak-rasa-wedding-hall
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node.js dependencies**
```bash
npm install
```

4. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configure environment variables**
```env
# Database
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Cloudinary (for file storage)
CLOUDINARY_CLOUD_NAME=your_cloud_name
CLOUDINARY_KEY=your_api_key
CLOUDINARY_SECRET=your_api_secret

# DeepSeek AI (via OpenRouter)
DEEPSEEK_API_KEY=your_openrouter_api_key
DEEPSEEK_API_URL=https://openrouter.ai/api/v1/chat/completions
DEEPSEEK_MODEL=deepseek/deepseek-r1

# Email configuration
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@enakrasa.com
MAIL_FROM_NAME="Enak Rasa Wedding Hall"

# Staff email for notifications
STAFF_EMAIL_ADDRESS=staff@enakrasa.com
STAFF_EMAIL_NAME="Enak Rasa Wedding Hall Staff"

# WhatsApp (Optional - via Twilio)
TWILIO_SID=your_twilio_sid
TWILIO_AUTH_TOKEN=your_twilio_token
TWILIO_WHATSAPP_FROM=whatsapp:+1234567890
```

6. **Run database migrations**
```bash
php artisan migrate
```

7. **Seed the database (optional)**
```bash
php artisan db:seed
```

8. **Build frontend assets**
```bash
npm run build
```

9. **Start the development server**
```bash
php artisan serve
```

## 👤 Default User Accounts

After seeding, you can log in with:

- **Admin**: admin@enakrasa.com / password
- **Staff**: staff@enakrasa.com / password
- **Customer**: customer@enakrasa.com / password

## 🎯 Key Features Explained

### Booking Workflow
1. **Customer submits** booking request via public form
2. **Staff reviews** and approves/rejects request
3. **Approved requests** automatically create user accounts
4. **Booking confirmation** sent via email
5. **Payment workflow** begins based on booking type

### Payment System
- **Wedding bookings**: RM3000 deposit → 50% second deposit → balance
- **Other events**: 50% deposit → 50% balance
- **Alternative**: Full payment option available
- **Automatic status updates** based on payment verification

### AI Chatbot Integration
- **Business context aware** responses
- **Package and venue information** integration
- **Intelligent link suggestions** based on conversation
- **Conversation history** for context retention

### File Management
- **Cloudinary integration** for secure, scalable file storage
- **Automatic image optimization** and format conversion
- **Public URL generation** for easy access
- **File deletion** when records are removed

## 🔧 Console Commands

### Email Testing
```bash
# Test invoice verification email
php artisan test:invoice-email verified test@example.com

# Test invoice rejection email
php artisan test:invoice-email rejected test@example.com
```

### Additional Commands
```bash
# Clear application cache
php artisan cache:clear

# Clear configuration cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Clear view cache
php artisan view:clear
```

## 📝 API Endpoints

### Booking Calendar API
- `GET /api/calendar/data` - Get calendar events
- `GET /api/calendar/venues` - Get venue list
- `GET /api/calendar/customers` - Get customer list
- `POST /api/calendar/check-availability` - Check date availability
- `POST /api/calendar/quick-booking` - Create quick booking

### Package Recommendation
- `POST /api/packages/recommend` - Get package recommendations

## 🔐 Security Features

- **CSRF protection** on all forms
- **Role-based middleware** for route protection
- **File upload validation** and sanitization
- **SQL injection prevention** via Eloquent ORM
- **Password hashing** with bcrypt
- **Secure file storage** via Cloudinary

## 🚀 Deployment

### Production Setup
1. **Set environment to production**
```env
APP_ENV=production
APP_DEBUG=false
```

2. **Optimize for production**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

3. **Set up queue worker** (if using queues)
```bash
php artisan queue:work --daemon
```

4. **Configure web server** (Apache/Nginx) to point to `/public` directory

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 📞 Support

For support and inquiries:
- **Email**: rasa.enak@gmail.com
- **Phone**: 013-331 4389
- **Address**: No. 3, Jalan Lintang 1 Off Jalan Lintang, Kuala Lumpur, Malaysia

## 🔄 Version History

- **v1.0.0** - Initial release with core booking and payment features
- **v1.1.0** - Added AI chatbot integration
- **v1.2.0** - Wedding card creator and enhanced reporting
- **v1.3.0** - Customization system and improved UI/UX

---

Built with ❤️ for Enak Rasa Wedding Hall
