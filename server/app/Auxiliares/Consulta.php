<?php

namespace App\Auxiliares;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Consulta
{
    private $eliminados;
    private $buscar;
    private $relaciones;
    private $campos;
    private $paginado;
    private $orden;
    private $ordenarPor;
    private $modelo;
    private $periodoDeBusqueda;

    public function __construct()
    {
        // consulta
        $this->eliminados = false;
        $this->campos = null;
        $this->relaciones = null;
        $this->buscar = null;
        $this->periodoDeBusqueda = null;

        // paginación
        $this->paginado = 10;
        $this->ordenarPor = 'id';
        $this->orden = 'ASC';
        $this->modelo = null;
    }

    public function setParametros(array $parametros): void
    {
        if (isset($parametros['modelo'])) {
            $this->setModelo($parametros['modelo']);
        }
        if (isset($parametros['campos'])) {
            $this->setCampos($parametros['campos']);
        }
        if (isset($parametros['relaciones'])) {
            $this->setModelosRelacionados($parametros['relaciones']);
        }
        if (isset($parametros['eliminados'])) {
            $this->setEliminados($parametros['eliminados']);
        }
        if (isset($parametros['buscar'])) {
            $this->setBuscar($parametros['buscar']);
        }

        if (isset($parametros['paginado'])) {
            $paginado = $parametros['paginado'];

            if (isset($paginado['porPagina'])) {
                $this->setRegistrosPorPagina($paginado['porPagina']);
            }
            if (isset($paginado['ordenadoPor'])) {
                $this->setOrdenarPor($paginado['ordenadoPor']);
            }
            if (isset($paginado['orden'])) {
                $this->setOrden($paginado['orden']);
            }
        }
    }

    public function ejecutarConsulta(): array
    {
        $modelo = $this->modelo;

        if ($this->relaciones) {
            $consulta = $modelo::with($this->relaciones);
        } else {
            $consulta = $modelo;
        }

        if ($this->eliminados) {
            $consulta = $consulta->onlyTrashed();
        }

        if ($this->campos) {
            $consulta = $consulta->select($this->campos);
        }

        if ($this->campos && $this->buscar) {
            $consulta = $this->buscar($consulta, $this->campos, $this->buscar);
        }

        if ($this->ordenarPor) {
            $consulta = $consulta->orderBy($this->ordenarPor, $this->orden);
        }

        $resultado = $consulta->paginate($this->paginado);

        if ($resultado) {
            $datos = $resultado->items();

            $paginacion = [
                "total" => $resultado->total(),
                "porPagina" => $resultado->perPage(),
                "paginaActual" => $resultado->currentPage(),
                "ultimaPagina" => $resultado->lastPage(),
                "desde" => $resultado->firstItem(),
                "hasta" => $resultado->lastItem(),
                "rutas" => [
                    "primeraPagina" => $resultado->toArray()['first_page_url'],
                    "ultimaPagina" => $resultado->toArray()['last_page_url'],
                    "siguientePagina" => $resultado->nextPageUrl(),
                    "paginaAnterior" => $resultado->previousPageUrl(),
                    "base" => $resultado->resolveCurrentPath(),
                ]
            ];

            return [
                'datos' => $datos,
                'paginacion' => $paginacion
            ];
        }
    }

    private function buscar(Builder $consulta, array $campos, string $valorBuscado): Builder
    {
        return $consulta->where(function ($query) use ($campos, $valorBuscado) {
            foreach ($campos as $campo) {
                $query->orWhere($campo, 'like', "%{$valorBuscado}%");
            }
        });
    }

    private function getBooleano($boolean)
    {
        if (is_null($boolean)) {
            return null;
        }

        if (
            $boolean === true   ||
            $boolean === "true" ||
            $boolean === 1      ||
            $boolean === '1'
        ) {
            return true;
        } elseif (
            $boolean === false   ||
            $boolean === 'false' ||
            $boolean === 0       ||
            $boolean === '0'
        ) {
            return false;
        }
        return null;
    }

    private function validarStringArray($array)
    {
        if (!is_null($array) && is_array($array)) {
            foreach ($array as $string) {
                if (!is_string($string)) {
                    return false;
                }
                return true;
            }
        } else {
            return false;
        }
    }

    private function getFechaComoCadena($date)
    {
        $date = Carbon::parse($date);
        $fecha = $date->isoFormat('YYYY-MM-DD');
        if ($fecha) {
            return  $date;
        }
        return false;
    }

    /**
     * @param boolean $boolean
     */
    private function setEliminados($boolean)
    {
        $bool = $this->getBooleano($boolean);

        $this->eliminados = $bool;
    }

    private function setModelo($string)
    {
        if (!is_null($string) && is_string($string)) {
            $model_name = "\\App\\{$string}";
            $this->modelo = new $model_name;
        } else {
            $this->modelo = null;
        }
    }

    /**
     * @param string $string
     */
    private function setBuscar($string)
    {
        if (!is_null($string) && is_string($string)) {
            $this->buscar = $string;
        } else {
            $this->buscar = null;
        }
    }

    /**
     * @param array $dateArray [desde, hasta]
     */
    private function setPeriodoBusqueda($dateArray)
    {
        if (!is_null($dateArray) && is_Array($dateArray)) {
            $inicio = $this->getFechaComoCadena($dateArray['desde']);
            $fin    = $this->getFechaComoCadena($dateArray['hasta']);
            if ($inicio && $fin) {
                $this->periodoDeBusqueda = [$inicio, $fin];
            } else {
                return false;
            }
        } else {
            $this->periodoDeBusqueda = null;
        }
    }

    /**
     * @param array $array
     */
    private function setModelosRelacionados($array)
    {
        if (!is_null($array)) {
            $validado = $this->validarStringArray($array);
            if ($validado) {
                $this->relaciones = $array;
            } else {
                return false;
            }
        }
    }

    /**
     * @param array $array
     */
    private function setCampos($array)
    {
        if (is_null($array)) {
            return false;
        }
        $validado = $this->validarStringArray($array);
        if ($validado) {
            $this->campos = $array;
        } else {
            return false;
        }
    }

    /**
     * @param integer $int
     */
    private function setRegistrosPorPagina($int)
    {
        if (is_numeric($int)) {
            $numero = $int + 0;
            if (is_int($numero)) {
                $this->paginado = $int;
            } else {
                $this->paginado = 10;
            }
        } else {
            $this->paginado = 10;
        }
    }

    /**
     * @param string $string
     */
    private function setOrdenarPor($string)
    {
        if (!is_null($string) && is_string($string)) {
            $this->ordenarPor = $string;
        } else {
            $this->ordenarPor = 'id';
        }
    }

    /**
     * asigna orden ASC o DESC a la consulta
     * @param boolean $boolean
     */
    private function setOrden($boolean)
    {
        if (!is_null($boolean)) {
            $bool = $this->getBooleano($boolean);
            $this->orden = ($bool) ? 'ASC' : 'DESC';
        }
    }
}
