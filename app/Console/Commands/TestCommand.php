<?php

namespace App\Console\Commands;

use App\Dtos\Catastro\Region;
use App\Dtos\Catastro\Town;
use App\Services\CatastroService;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TestCommand extends Command
{
    protected CatastroService $catastro;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('memory_limit', -1);

        $catastro = $this->catastro = new CatastroService();

        $regions = $catastro->getRegions();

        $selectedRegionName = $this->anticipate('Select a region', $regions->pluck('name')->all());
        $region = $regions->where('name', $selectedRegionName)->first();

        $towns = $catastro->getTowns($region);

        $selectedTownName = $this->anticipate('Select a town', $towns->pluck('name')->all());
        $town = $towns->where('name', $selectedTownName)->first();

        $streets = $catastro->getStreets($region, $town);

        $selectedStreetName = $this->anticipate('Select a street', $streets->pluck('name')->all());
        $street = $streets->where('name', $selectedStreetName)->first();

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

        $properties = collect();
        foreach ($numbers as $number) {
            $properties = $properties->put($number->number, $this->getProperty($region, $town, $number->reference));
        }

        file_put_contents('/Users/efrain/Downloads/properties.json', json_encode($properties));

        return 0;
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
