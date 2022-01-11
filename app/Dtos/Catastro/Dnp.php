<?php

namespace App\Dtos\Catastro;

use JetBrains\PhpStorm\Pure;

class Dnp
{
    public ?string $reference = null;
    public ?string $usageType = null;
    public ?string $area = null;
    public ?string $buildingYear = null;
    public array $sections = [];

    public function __construct(\SimpleXMLElement $xml)
    {
        $this->reference = '';
        foreach ($xml->bi->idbi->rc->children() as $refPart) $this->reference .= $refPart;
        if ($xml->bi->debi->luso) $this->usageType = (string) $xml->bi->debi->luso;
        if ($xml->bi->debi->sfc) $this->area = (string) $xml->bi->debi->sfc;
        if ($xml->bi->debi->ant) $this->buildingYear = (string) $xml->bi->debi->ant;

        if ($xml->lcons) {
            foreach ($xml->lcons->children() as $cons) {
                $this->sections[] = new Section($cons);
            }
        }
    }
}
