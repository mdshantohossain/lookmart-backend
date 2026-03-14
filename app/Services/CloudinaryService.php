<?php

namespace App\Services;
use Cloudinary\Cloudinary;

class CloudinaryService
{
    protected object $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
        ]);

    }

    public function upload($file, $folder = 'images')
    {
        $uploaded = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
            'folder' => $folder, // optional
        ]);

        return [
            'url' => $uploaded['secure_url'],
            'public_id' => $uploaded['public_id'],
        ];
    }

    public function delete($publicId)
    {
        return $this->cloudinary->uploadApi()->destroy($publicId);
    }
}
