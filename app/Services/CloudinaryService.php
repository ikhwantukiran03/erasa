<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class CloudinaryService
{
    private $cloudinary;

    /**
     * Create a new Cloudinary service instance.
     *
     * @return void
     */
    public function __construct()
    {
        Configuration::instance([
            'cloud' => [
                'cloud_name' => 'dwqzoq6lc',
                'api_key' => '886926516794117',
                'api_secret' => 'lkApG6vTkjXduLjv57c58vwGQmc'
            ],
            'url' => [
                'secure' => true
            ]
        ]);

        $this->cloudinary = new Cloudinary();
    }

    /**
     * Upload a file to Cloudinary.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @return array|null
     */
    public function uploadFile(UploadedFile $file, $folder = 'invoices')
    {
        try {
            $options = [
                'folder' => $folder,
                'resource_type' => 'auto',
                'overwrite' => true,
                'unique_filename' => true,
                'transformation' => [
                    'quality' => 'auto',
                    'fetch_format' => 'auto',
                ],
            ];
            
            $result = $this->cloudinary->uploadApi()->upload($file->getRealPath(), $options);
            
            Log::info('Cloudinary upload successful', [
                'public_id' => $result['public_id'],
                'url' => $result['secure_url']
            ]);
            
            return [
                'public_id' => $result['public_id'],
                'url' => $result['secure_url']
            ];
        } catch (\Exception $e) {
            Log::error('Cloudinary upload failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return null;
        }
    }

    /**
     * Delete a file from Cloudinary.
     *
     * @param string $publicId
     * @return bool
     */
    public function deleteFile($publicId)
    {
        try {
            $result = $this->cloudinary->uploadApi()->destroy($publicId);
            return $result['result'] === 'ok';
        } catch (\Exception $e) {
            Log::error('Cloudinary delete failed', [
                'public_id' => $publicId,
                'message' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    public function uploadImage($file, $folder = 'promotions')
    {
        try {
            if (!$file instanceof \Illuminate\Http\UploadedFile) {
                throw new \Exception('Invalid file upload');
            }

            $options = [
                'folder' => $folder,
                'resource_type' => 'image',
                'overwrite' => true,
                'unique_filename' => true,
                'transformation' => [
                    'quality' => 'auto',
                    'fetch_format' => 'auto',
                ],
            ];

            $result = $this->cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                $options
            );

            Log::info('Cloudinary upload successful', [
                'public_id' => $result['public_id'],
                'url' => $result['secure_url']
            ]);

            return [
                'image_id' => $result['public_id'],
                'image_url' => $result['secure_url']
            ];
        } catch (\Exception $e) {
            Log::error('Cloudinary upload failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function deleteImage($publicId)
    {
        return $this->cloudinary->uploadApi()->destroy($publicId);
    }
}