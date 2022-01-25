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

            $doors = $groupedByNumber->map(function (Property $property) {
                $floor = in_array($property->floor, ['0', '00']) ? 'Bajo' : $property->floor;
                return $floor . 'º ' . $property->door;
            })->join("\n");

            return $header . "\n" . $doors;
        })->join("\n\n");
    }
}
