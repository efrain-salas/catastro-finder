<?php

namespace App\Services;

use App\Dtos\Catastro\Dnp;
use App\Dtos\Catastro\Region;
use App\Dtos\Catastro\Street;
use App\Dtos\Catastro\StreetType;
use App\Dtos\Catastro\Town;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class CatastroService
{
    protected function http(): PendingRequest
    {
        sleep(1);
        return Http::baseUrl('https://ovc.catastro.meh.es/ovcservweb/OVCSWLocalizacionRC/OVCCallejero.asmx/')
            ->withoutVerifying();
    }

    public function getRegions(): Collection
    {
        $xml = $this->http()->get('ConsultaProvincia')->body();
        $data = simplexml_load_string($xml);

        $regions = [];

        foreach ($data->provinciero->children() as $region) {
            $regions[] = new Region($region);
        }

        return collect($regions);
    }

    public function getTowns(Region $region, string $query = ''): Collection
    {
        $xml = $this->http()->asForm()->post('ConsultaMunicipio', [
            'Provincia' => $region->name,
            'Municipio' => $query,
        ])->body();

        $data = simplexml_load_string($xml);

        $towns = [];

        if ($data->municipiero) {
            foreach ($data->municipiero->children() as $town) {
                $towns[] = new Town($town);
            }
        }

        return collect($towns);
    }

    public function getStreetTypes(): Collection
    {
        $data = json_decode(file_get_contents(resource_path('json/streetTypes.json')));

        $streetTypes = [];

        foreach ($data as $streetType) {
            $streetTypes[] = new StreetType($streetType);
        }

        return collect($streetTypes);
    }

    public function getStreets(Region $region, Town $town, StreetType $type = null, string $query = ''): Collection
    {

        $xml = $this->http()->asForm()->post('ConsultaVia', [
            'Provincia' => $region->name,
            'Municipio' => $town->name,
            'TipoVia' => $type ? $type->code : '',
            'NombreVia' => $query,
        ])->body();

        $data = simplexml_load_string($xml);

        $streets = [];

        if ($data->callejero) {
            foreach ($data->callejero->children() as $street) {
                $streets[] = new Street($street);
            }
        }

        return collect($streets);
    }

    public function checkNumber(Region $region, Town $town, Street $street, int $number): \stdClass
    {
        $xml = $this->http()->asForm()->post('ConsultaNumero', [
            'Provincia' => $region->name,
            'Municipio' => $town->name,
            'TipoVia' => $street->streetType,
            'NomVia' => $street->name,
            'Numero' => $number,
        ])->body();

        $data = simplexml_load_string($xml);

        $response = new \stdClass();
        $response->reference = null;
        $response->nearNumbers = [];

        if ($data->lerr) {
            $response->numberExists = false;

            if ($data->numerero) {
                foreach ($data->numerero->children() as $nump) {
                    $num = (integer) $nump->num->pnp;

                    $reference = '';
                    foreach ($nump->pc->children() as $refPart) $reference .= $refPart;

                    $response->nearNumbers[$num] = $reference;
                }
            }
        } else {
            $response->numberExists = true;

            $reference = '';
            foreach ($data->numerero->children()[0]->pc->children() as $refPart) $reference .= $refPart;

            $response->reference = $reference;
        }

        return $response;
    }

    public function getDataByLocation(Region $region, Town $town, Street $street, int $number, string $block = '', string $stair = '', string $floor = '', string $door = ''): \stdClass
    {
        $xml = $this->http()->asForm()->post('Consulta_DNPLOC', [
            'Provincia' => $region->name,
            'Municipio' => $town->name,
            'Sigla' => $street->streetType,
            'Calle' => $street->name,
            'Numero' => $number,
            'Bloque' => $block,
            'Escalera' => $stair,
            'Planta' => $floor,
            'Puerta' => $door,
        ])->body();

        $data = simplexml_load_string($xml);

        $response = new \stdClass();

        if ($data->lerr) {
            $response->error = (string) $data->lerr->err->des;
        }

        if ($data->bico) {
            $response->concreteResult = true;
            $response->data = new Dnp($data->bico);
        } else if ($data->lrcdnp) {
            $response->concreteResult = false;
            $response->data = [];

            foreach ($data->lrcdnp->children() as $option) {
                $reference = '';
                foreach ($option->rc->children() as $refPart) $reference .= $refPart;
                $response->data[] = $reference;
            }
        }

        return $response;
    }

    public function getDataByReference(Region $region, Town $town, string $reference): \stdClass
    {
        $xml = $this->http()->asForm()->post('Consulta_DNPRC', [
            'Provincia' => $region->name,
            'Municipio' => $town->name,
            'rc' => $reference,
        ])->body();

        $data = simplexml_load_string($xml);

        $response = new \stdClass();

        if ($data->lerr) {
            $response->error = (string) $data->lerr->err->des;
        }

        if ($data->bico) {
            $response->concreteResult = true;
            $response->data = new Dnp($data->bico);
        } else if ($data->lrcdnp) {
            $response->concreteResult = false;
            $response->data = [];

            foreach ($data->lrcdnp->children() as $option) {
                $ref = '';
                foreach ($option->rc->children() as $refPart) $ref .= $refPart;
                $response->data[] = $ref;
            }
        }

        return $response;
    }
}
