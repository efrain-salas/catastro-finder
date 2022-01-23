<?php

namespace App\Console\Commands;

use App\Models\PostalCode;
use App\Models\Property;
use App\Services\PostalCodeService;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class SyncPostalCodesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:postal-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync postal codes';

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
        $postalCodeService = new PostalCodeService();

        Property::query()->distinct(['region', 'town', 'street', 'number'])->chunk(100, function (Collection $properties) use ($postalCodeService) {
            $properties->each(function (Property $property) use ($postalCodeService) {
                $exists = PostalCode::query()->where([
                    'region' => $property->region,
                    'town' => $property->town,
                    'street' => $property->street,
                    'number' => $property->number,
                ])->exists();

                if ( ! $exists) {
                    $address = implode(', ', [$property->street, $property->number, $property->town, $property->region]);

                    if ($postalCode = $postalCodeService->getFromAddress($address)) {
                        PostalCode::create([
                            'region' => $property->region,
                            'town' => $property->town,
                            'street' => $property->street,
                            'number' => $property->number,
                            'postal_code' => $postalCode,
                        ]);

                        sleep(1);
                    }
                }
            });
        });

        return 0;
    }
}
