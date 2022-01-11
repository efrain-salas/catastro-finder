<?php

namespace App\Dtos\Catastro;

class Section
{
    public ?string $usageType = null;
    public ?string $area = null;
    public ?string $block = null;
    public ?string $stair = null;
    public ?string $floor = null;
    public ?string $door = null;

    public function __construct(\SimpleXMLElement $xml)
    {
        $this->usageType = (string) $xml->lcd;
        $this->area = (string) $xml->dfcons->stl;

        if ($xml->dt) {
            $loint = $xml->dt->lourb->loint;

            if ($loint->bq) $this->block = (string) $loint->bq;
            if ($loint->es) $this->stair = (string) $loint->es;
            if ($loint->pt) $this->floor = (string) $loint->pt;
            if ($loint->pu) $this->door = (string) $loint->pu;
        }
    }
}
