<?php

namespace App\Http\Requests;

trait CamposPeticion
{
    public function getCampos(): array
    {
        return array_keys($this->rules());
    }
}
