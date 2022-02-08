<?php

namespace App\Services;

use App\Models\PostalCode;
use App\Models\Property;
use Illuminate\Support\Collection;

class PrettyShareService
{
    public function prettify(Collection $properties): string
    {
        return $properties->groupBy(function (Property $property) {
            return $property->street . '-' . $property->number;
        })->map(function (Collection $groupedByNumber) {
            $p = $groupedByNumber->first();
            $postalCode = PostalCode::getFromAddress($p->region, $p->town, $p->street, $p->number);

            $header = $p->street . ', ' . $p->number . ' (' . $postalCode . ' - ' . $p->town . ')';

            $stairs = $groupedByNumber->sortBy('stair')->groupBy('stair')->map(function (Collection $groupedByStair) {
                $p = $groupedByStair->first();
                $stair = $p->stair != 'E' ? ltrim($p->stair, '0') : '';
                $header = $stair ? 'Escalera ' . $stair : '';

                $doors = $groupedByStair->sortBy('floor')->map(function (Property $property) {
                    $floor = in_array($property->floor, ['0', '00']) ? 'Bajo' : ltrim($property->floor, '0') . 'º';
                    return $floor . ' ' . $property->door;
                })->join("\n");

                return $header ? $header . "\n" . $doors : $doors;
            })->join("\n");

            return $header . "\n" . $stairs;
        })->join("\n\n");
    }
}
