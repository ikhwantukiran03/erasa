<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SupabaseStorageService
{
    protected $supabaseUrl;
    protected $supabaseKey;
    protected $defaultBucket;

    /**
     * Create a new Supabase service instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->supabaseUrl = env('SUPABASE_URL');
        $this->supabaseKey = env('SUPABASE_KEY');
        $this->defaultBucket = env('SUPABASE_DEFAULT_BUCKET', 'wedding-hall-files');
        
        // Ensure the bucket exists
        $this->ensureBucketExists();
    }

    /**
     * Ensure the bucket exists, create it if it doesn't
     * 
     * @return bool Whether the bucket exists or was created successfully
     */
    protected function ensureBucketExists()
    {
        try {
            // First check if bucket exists
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->supabaseKey,
                'Content-Type' => 'application/json',
            ])->get("{$this->supabaseUrl}/storage/v1/bucket/{$this->defaultBucket}");
            
            // If bucket doesn't exist (404), create it
            if ($response->status() === 404) {
                Log::info('Bucket does not exist, attempting to create it: ' . $this->defaultBucket);
                
                $createResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->supabaseKey,
                    'Content-Type' => 'application/json',
                ])->post("{$this->supabaseUrl}/storage/v1/bucket", [
                    'id' => $this->defaultBucket,
                    'name' => $this->defaultBucket,
                    'public' => true,
                ]);
                
                if ($createResponse->successful()) {
                    Log::info('Successfully created bucket: ' . $this->defaultBucket);
                    return true;
                } else {
                    Log::error('Failed to create bucket', [
                        'status' => $createResponse->status(),
                        'response' => $createResponse->body(),
                    ]);
                    return false;
                }
            }
            
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Exception in bucket check/create', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Upload a file to Supabase storage.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string|null $folder Directory path inside the bucket
     * @param string|null $bucket Override the default bucket
     * @return string|bool The file path if successful, false otherwise
     */
    public function uploadFile(UploadedFile $file, $folder = null, $bucket = null)
    {
        try {
            $bucket = $bucket ?? $this->defaultBucket;
            
            // Generate a unique filename with original extension
            $extension = $file->getClientOriginalExtension();
            $filename = Str::uuid() . '.' . $extension;
            
            // Add folder path if provided
            $filePath = $folder ? "{$folder}/{$filename}" : $filename;
            
            // Get file content
            $fileContent = file_get_contents($file->getRealPath());
            
            // Get the mime type
            $contentType = $file->getMimeType();
            
            // Upload to Supabase
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->supabaseKey,
                'Content-Type' => $contentType,
                'Cache-Control' => 'max-age=3600',
                'x-upsert' => 'true' // Add upsert flag to prevent duplicate uploads
            ])->put(
                "{$this->supabaseUrl}/storage/v1/object/{$bucket}/{$filePath}",
                $fileContent
            );
            
            if ($response->successful()) {
                Log::info('Supabase upload successful', [
                    'bucket' => $bucket,
                    'path' => $filePath,
                    'status' => $response->status()
                ]);
                return $filePath;
            } else {
                Log::error('Supabase upload failed', [
                    'bucket' => $bucket,
                    'path' => $filePath,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Exception in Supabase upload', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Get a public URL for a file.
     *
     * @param string $filePath
     * @param string|null $bucket
     * @return string
     */
    public function getPublicUrl($filePath, $bucket = null)
    {
        $bucket = $bucket ?? $this->defaultBucket;
        return "{$this->supabaseUrl}/storage/v1/object/public/{$bucket}/{$filePath}";
    }

    /**
     * Delete a file from Supabase storage.
     *
     * @param string $filePath
     * @param string|null $bucket
     * @return bool
     */
    public function deleteFile($filePath, $bucket = null)
    {
        try {
            $bucket = $bucket ?? $this->defaultBucket;
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->supabaseKey,
            ])->delete(
                "{$this->supabaseUrl}/storage/v1/object/{$bucket}/{$filePath}"
            );
            
            $success = $response->successful();
            
            if ($success) {
                Log::info('Supabase delete successful', [
                    'bucket' => $bucket,
                    'path' => $filePath,
                ]);
            } else {
                Log::error('Supabase delete failed', [
                    'bucket' => $bucket,
                    'path' => $filePath,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
            
            return $success;
        } catch (\Exception $e) {
            Log::error('Exception in Supabase delete', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }
    
    /**
     * Check if the service is properly configured
     *
     * @return bool
     */
    public function isConfigured()
    {
        return !empty($this->supabaseUrl) && !empty($this->supabaseKey);
    }
}