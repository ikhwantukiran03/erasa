<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EmailService;

class TestInvoiceEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:invoice-email {type=verified} {email=test@example.com}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test invoice email notifications (verified or rejected)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->argument('type');
        $email = $this->argument('email');
        
        $emailService = app(EmailService::class);
        
        // Sample data for testing
        $emailData = [
            'customer_name' => 'John Doe',
            'booking_id' => 123,
            'payment_type' => 'deposit',
            'booking_type' => 'wedding',
            'amount' => 3000.00,
            'verified_date' => now()->format('M d, Y H:i'),
            'rejected_date' => now()->format('M d, Y H:i'),
            'staff_notes' => 'Test email notification - this is a sample staff note.',
        ];
        
        $this->info("Testing {$type} invoice email to: {$email}");
        
        if ($type === 'verified') {
            $result = $emailService->sendInvoiceVerificationEmail($email, $emailData);
            $this->info($result ? 'Invoice verification email sent successfully!' : 'Failed to send invoice verification email.');
        } elseif ($type === 'rejected') {
            $result = $emailService->sendInvoiceRejectionEmail($email, $emailData);
            $this->info($result ? 'Invoice rejection email sent successfully!' : 'Failed to send invoice rejection email.');
        } else {
            $this->error('Invalid type. Use "verified" or "rejected".');
            return 1;
        }
        
        return 0;
    }
}
