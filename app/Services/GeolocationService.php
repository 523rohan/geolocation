<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;


class GeolocationService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('ABSTRACT_API_KEY');
    }

    public function getGeolocation($ip)
    {
        return Cache::remember("geolocation_{$ip}", 3600, function () use ($ip) {
            try {
                $response = Http::get('https://ipgeolocation.abstractapi.com/v1/', [
                    'api_key' => $this->apiKey,
                    'ip_address' => $ip,
                ]);
    
                Log::info('API Request', ['url' => $response->effectiveUri(), 'ip' => $ip]);
    
                if ($response->successful()) {
                    Log::info('API Response', ['data' => $response->json()]);
                    return $response->json();
                }
    
                Log::error('API Error', ['response' => $response->body()]);
            } catch (\Exception $e) {
                Log::error('API Exception', ['message' => $e->getMessage()]);
            }
    
            return null;
        });
    }
}
