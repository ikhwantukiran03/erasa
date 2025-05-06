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
    }

    /**
     * Upload a file to Supabase storage.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string|null $bucket
     * @param string|null $folder
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
            ])->put(
                "{$this->supabaseUrl}/storage/v1/object/{$bucket}/{$filePath}",
                $fileContent
            );
            
            if ($response->successful()) {
                return $filePath;
            } else {
                Log::error('Supabase upload failed', [
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
            
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Exception in Supabase delete', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }
}