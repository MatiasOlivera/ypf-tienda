<?php

namespace App\Http\Requests;

trait CastingDeTipos
{
    private function getCadena($valor)
    {
        if (is_null($valor) || $valor === '') {
            return null;
        }

        return $valor;
    }

    private function getBooleano($valor)
    {
        if (is_null($valor)) {
            return null;
        }

        if ($valor === 'true') {
            return true;
        }

        if ($valor === 'false') {
            return false;
        }

        return $valor;
    }

    private function getEntero($valor)
    {
        if (is_null($valor)) {
            return null;
        }

        if (is_numeric($valor)) {
            return (int) $valor;
        }

        return $valor;
    }
}
