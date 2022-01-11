<?php

namespace App\Dtos\Catastro;

class StreetType
{
    public ?string $name = null;
    public ?string $code = null;

    public function __construct(\stdClass $source)
    {
        $this->name = (string) $source->name;
        $this->code = (string) $source->code;
    }
}
