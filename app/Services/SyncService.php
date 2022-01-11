<?php

namespace App\Services;

use App\Dtos\Catastro\Region;
use App\Dtos\Catastro\Town;
use App\Models\Property;
use Illuminate\Support\Collection;

class SyncService
{
    protected CatastroService $catastro;

    public function __construct()
    {
        $this->catastro = new CatastroService();
    }

    public function sync()
    {
        $catastro = $this->catastro;

        $regions = $catastro->getRegions();

        $region = $regions[3];
        $towns = $catastro->getTowns($regions[3]);
        $town = $towns[12];

        $streets = $catastro->getStreets($region, $town);

        foreach ($streets as $street) {
            $numbers = collect();
            $i = 1;
            $noResultsCounter = 0;
            do {
                $results = $catastro->checkNumber($region, $town, $street, $i);

                if ($results->numberExists) {
                    $noResultsCounter = 0;

                    $numbers->push((object) [
                        'number' => $i,
                        'reference' => $results->reference,
                    ]);

                    $i++;
                } else {
                    if ($results->nearNumbers) {
                        $noResultsCounter = 0;

                        $numbers = $numbers->merge(collect($results->nearNumbers)->map(function ($reference, $number) {
                            return (object) [
                                'number' => $number,
                                'reference' => $reference,
                            ];
                        }));
                    } else {
                        $noResultsCounter++;
                    }

                    $i += 6;
                }

                $numbers = $numbers->unique('number');
            } while ($noResultsCounter < 5);

            $propertiesByNumber = collect();
            foreach ($numbers as $number) {
                $propertiesByNumber = $propertiesByNumber->put($number->number, $this->getProperty($region, $town, $number->reference));
            }

            foreach ($propertiesByNumber as $number => $properties) {
                foreach ($properties as $p) {
                    $housing = collect($p->sections)->where('usageType', 'VIVIENDA')->first();
                    if ($housing) {
                        Property::updateOrCreate(
                            [
                                'reference' => $p->reference,
                            ],
                            [
                                'usageType' => $p->usageType,
                                'region' => $region->name,
                                'town' => $town->name,
                                'street' => $street->name,
                                'number' => $number,
                                'stair' => $housing->stair,
                                'floor' => $housing->floor,
                                'door' => $housing->door,
                            ],
                        );
                    }
                }
            }
        }
    }

    protected function getProperty(Region $region, Town $town, string $reference): Collection
    {
        $catastro = $this->catastro;

        $result = $catastro->getDataByReference($region, $town, $reference);

        $properties = collect();

        if ($result->concreteResult) {
            $properties->push($result->data);
        } else {
            foreach ($result->data as $reference) {
                $properties = $properties->merge($this->getProperty($region, $town, $reference));
            }
        }

        return $properties;
    }
}
