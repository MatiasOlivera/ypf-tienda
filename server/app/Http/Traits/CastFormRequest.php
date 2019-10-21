<?php

namespace App\Http\Traits;

use Exception;

trait CastFormRequest
{
    /**
     * Los atributos que deberían ser transformados a tipos nativos.
     *
     * @var array
     */
    abstract public function casts(): array;

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $casts = $this->casts();
        foreach ($casts as $atributo => $tipo) {
            if (!$this->has($atributo)) {
                continue;
            }

            $valor = $this->input($atributo);
            $valorConvertido = $this->convertirValorATipo($valor, $tipo);

            $this->merge([$atributo => $valorConvertido]);
        }
    }

    /**
     * Convertir un atributo a un tipo nativo de PHP.
     *
     * @param $valor
     * @param $tipo
     *
     * @return mixed
     */
    protected function convertirValorATipo($valor, string $tipo)
    {
        if ($valor === 'null') {
            return null;
        }

        switch ($tipo) {
            case 'int':
            case 'integer':
                return $this->tipoInteger($valor);
            case 'real':
            case 'float':
            case 'double':
                return $this->tipoFloat($valor);
            case 'string':
                return $this->tipoString($valor);
            case 'bool':
            case 'boolean':
                return $this->tipoBoolean($valor);
            case 'array':
                return $this->tipoArray($valor);
            default:
                throw new Exception("El tipo $tipo no es válido");
        }
    }

    /**
     * Convertir al tipo boolean
     *
     * @param  mixed $valor
     * @return bool
     */
    protected function tipoBoolean($valor)
    {
        if ($valor === "false") {
            return false;
        }

        if (in_array($valor, ["true", "1", "0", 1, 0], true)) {
            return (bool) $valor;
        }

        return $valor;
    }

    /**
     * Convertir al tipo float
     *
     * @param  numeric $valor
     * @return int|mixed
     */
    protected function tipoFloat($valor)
    {
        if (is_numeric($valor)) {
            return (float) $valor;
        }
        return $valor;
    }

    /**
     * Convertir al tipo integer
     *
     * @param  numeric $valor
     * @return int|mixed
     */
    protected function tipoInteger($valor)
    {
        if (is_numeric($valor)) {
            return (int) $valor;
        }
        return $valor;
    }

    /**
     * Convertir al tipo string
     *
     * @param  mixed $valor
     * @return string
     */
    protected function tipoString($valor)
    {
        return (string) $valor;
    }

    /**
     * Convertir al tipo array
     *
     * @param  mixed $valor
     * @return array
     */
    protected function tipoArray($valor)
    {
        return explode(',', $valor);
    }
}
