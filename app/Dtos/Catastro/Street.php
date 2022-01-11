<?php

namespace App\Dtos\Catastro;

class Street
{
    public ?string $name = null;
    public ?string $streetType = null;
    public ?string $code = null;

    public function __construct(\SimpleXMLElement $xml)
    {
        $this->name = (string) $xml->dir->nv;
        $this->streetType = (string) $xml->dir->tv;
        $this->code = (string) $xml->dir->cv;
    }
}
