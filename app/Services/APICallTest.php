<?php

namespace App\Services;

use Throwable;
use RuntimeException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class APICallTest
{
    /**
     * Download an image from $url and save to the public disk under $folder.
     *
     * @param string $url
     * @param string $folder path inside the public disk, e.g. 'assets/test'
     * @return string|null  saved relative path (e.g. 'assets/test/abc.jpg') or null on failure
     * @throws \Exception on HTTP/client errors
     */
    public function downloadImage(string $url = 'https://picsum.photos/200/300', string $folder = 'assets/test')
    {
        try {
            // Request with timeout
            $response = Http::timeout(15)->get($url);

            // Check HTTP success
            if (! $response->successful()) {
                throw new RuntimeException("HTTP request failed with status {$response->status()}");
            }

            // Determine extension from Content-Type (fallback to jpg)
            $contentType = $response->header('Content-Type', 'image/jpeg');
            $ext = $this->extensionFromContentType($contentType) ?? 'jpg';

            // Generate filename
            $filename = Str::uuid()->toString() . '.' . $ext;

            // Full storage path on the "public" disk
            $storagePath = rtrim($folder, '/') . '/' . $filename;

            // Save to storage (storage/app/public/...)
            $saved = Storage::disk('public')->put($storagePath, $response->body());


            if (! $saved) {
                throw new RuntimeException("Failed to save file to disk at {$storagePath}");
            }

            // Return the relative path inside public disk
            return $storagePath;
        } catch (Throwable $e) {
            // Log or rethrow as needed; rethrowing for visibility here
            throw $e;
        }
    }

    protected function extensionFromContentType(string $contentType): ?string
    {
        $map = [
            'image/jpeg' => 'jpg',
            'image/jpg'  => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            'image/webp' => 'webp',
        ];

        // sometimes content type contains charset -> split it
        $mime = explode(';', $contentType)[0];
        return $map[$mime] ?? null;
    }
}
