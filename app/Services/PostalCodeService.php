<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PostalCodeService
{
    public function getFromAddress(string $address): ?string
    {
        $addressComponents = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address' => $address,
            'key' => config('services.google_maps.api_key'),
        ])->json('results.0.address_components');

        $component = collect($addressComponents)->first(function ($component) {
            return in_array('postal_code', $component['types']);
        });

        return $component['long_name'] ?? null;
    }
}
