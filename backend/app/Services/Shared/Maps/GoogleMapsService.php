<?php

declare(strict_types=1);

namespace App\Services\Shared\Maps;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleMapsService
{
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.google.maps_key', '');
    }

    public function geocode(string $address): ?array
    {
        if (empty($this->apiKey)) {
            return null;
        }

        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                'address' => $address,
                'key' => $this->apiKey,
            ]);

            $data = $response->json();

            if ($data['status'] === 'OK' && ! empty($data['results'])) {
                $result = $data['results'][0];

                return [
                    'lat' => $result['geometry']['location']['lat'],
                    'lng' => $result['geometry']['location']['lng'],
                    'formatted_address' => $result['formatted_address'],
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('Google Maps geocode failed: '.$e->getMessage());
        }

        return null;
    }

    public function reverseGeocode(float $lat, float $lng): string
    {
        if (empty($this->apiKey)) {
            return '';
        }

        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                'latlng' => "{$lat},{$lng}",
                'key' => $this->apiKey,
            ]);

            $data = $response->json();

            if ($data['status'] === 'OK' && ! empty($data['results'])) {
                return $data['results'][0]['formatted_address'];
            }
        } catch (\Throwable $e) {
            Log::warning('Google Maps reverse geocode failed: '.$e->getMessage());
        }

        return '';
    }

    public function getStaticMapUrl(float $lat, float $lng, int $zoom = 14, string $size = '600x300'): string
    {
        $params = http_build_query([
            'center' => "{$lat},{$lng}",
            'zoom' => $zoom,
            'size' => $size,
            'markers' => "color:red|{$lat},{$lng}",
            'key' => $this->apiKey,
        ]);

        return "https://maps.googleapis.com/maps/api/staticmap?{$params}";
    }
}
