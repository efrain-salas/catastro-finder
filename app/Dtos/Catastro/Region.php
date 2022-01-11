<?php

namespace App\Dtos\Catastro;

class Region
{
    public ?string $name = null;
    public ?string $code = null;

    public function __construct(\SimpleXMLElement $xml)
    {
        $this->name = (string) $xml->np;
        $this->code = (string) $xml->cpine;
    }
}
