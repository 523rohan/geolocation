<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\GeolocationLog;
use App\Services\GeolocationService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;



class GeolocationLogController extends Controller
{
    protected $geolocationService;

    public function __construct(GeolocationService $geolocationService)
    {
        $this->geolocationService = $geolocationService;
    }

    public function log(Request $request)
    {
        // fetch ip from user input or current ip
        $ipAddress = $request->ip_address ?? $request->ip();

        if ($ipAddress == '127.0.0.1') {
            $ipAddress = '103.219.60.87'; // Dummy IP address for belgaum
        }
        $data = $this->geolocationService->getGeolocation($ipAddress);

    //    creating record
        if ($data) {
            $log = GeolocationLog::create([
                'ip_address' => $ipAddress,
                'country' => $data['country'],
                'region' => $data['region'],
                'city' => $data['city'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
            ]);


            // display map 
            return view('geolocation', [
                'ip_address' => $log->ip_address,
                'country' => $log->country,
                'region' => $log->region,
                'city' => $log->city,
                'latitude' => $log->latitude,
                'longitude' => $log->longitude,
            ]);
        }

        return response()->json(['error' => 'Unable to fetch geolocation data'], 500);
    }
   
   
}
