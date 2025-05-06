<?php

namespace Tests\Feature;

use App\Services\SupabaseStorageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SupabaseStorageTest extends TestCase
{
    protected $supabaseStorage;

    protected function setUp(): void
    {
        parent::setUp();
        $this->supabaseStorage = new SupabaseStorageService();
    }

    /**
     * Test that the service is properly configured.
     *
     * @return void
     */
    public function test_service_is_configured()
    {
        $this->assertTrue($this->supabaseStorage->isConfigured(), 'Supabase storage service is not configured correctly');
        
        echo "✓ Supabase storage service is configured\n";
    }

    /**
     * Test file upload to Supabase storage.
     *
     * @return void
     */
    public function test_upload_file()
    {
        // Create a fake image file
        $file = UploadedFile::fake()->image('test_image.jpg', 400, 400);
        
        // Upload the file to Supabase
        $filePath = $this->supabaseStorage->uploadFile($file, 'test');
        
        $this->assertNotFalse($filePath, 'File upload failed');
        $this->assertNotEmpty($filePath, 'File path is empty');
        
        echo "✓ File uploaded successfully: {$filePath}\n";
        
        return $filePath;
    }

    /**
     * Test getting a public URL for a file.
     *
     * @depends test_upload_file
     * @return void
     */
    public function test_get_public_url($filePath)
    {
        $publicUrl = $this->supabaseStorage->getPublicUrl($filePath);
        
        $this->assertNotEmpty($publicUrl, 'Public URL is empty');
        $this->assertStringContainsString($filePath, $publicUrl, 'Public URL does not contain the file path');
        
        echo "✓ Public URL generated: {$publicUrl}\n";
        
        return $filePath;
    }

    /**
     * Test deleting a file from Supabase storage.
     *
     * @depends test_get_public_url
     * @return void
     */
    public function test_delete_file($filePath)
    {
        $result = $this->supabaseStorage->deleteFile($filePath);
        
        $this->assertTrue($result, 'File deletion failed');
        
        echo "✓ File deleted successfully\n";
    }
}