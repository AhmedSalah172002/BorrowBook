<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ImageService
{

    public function uploadImage($image, $oldImage = null)
    {
        if (is_string($image)) {
            return $image;
        }

        if ($oldImage) {
            $this->deleteOldImage($oldImage);
        }

        return Cloudinary::upload($image->getRealPath(), [
            'folder' => "books/" . date("Y") . "/" . date("M"),
        ])->getSecurePath();
    }


    private function deleteOldImage($oldImage)
    {
        $publicId = $this->getPublicIdFromUrl($oldImage);
        if ($publicId) {
            Cloudinary::destroy($publicId);
        }
    }

    /**
     * Extract public ID from Cloudinary URL.
     */
    private function getPublicIdFromUrl(string $url): ?string
    {
        $parts = explode('/', $url);
        return explode('.', join('/', array_slice($parts, 7)))[0];
    }
}
