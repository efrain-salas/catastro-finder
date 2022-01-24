<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostalCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'region',
        'town',
        'street',
        'number',
        'postal_code',
    ];

    public static function getFromAddress(string $region, string $town, string $street, string $number): ?string
    {
        $result = static::query()->firstWhere([
            'region' => $region,
            'town' => $town,
            'street' => $street,
            'number' => $number,
        ]);

        return $result?->postal_code;
    }
}
