<?php

namespace App\Dtos\Catastro;

class Town
{
    public ?string $name = null;
    public ?string $ineCode = null;
    public ?string $mehCode = null;

    public function __construct(\SimpleXMLElement $xml)
    {
        $this->name = (string) $xml->nm;
        $this->ineCode = (string) $xml->loine->cm;
        $this->mehCode = (string) $xml->locat->cmc;
    }
}
