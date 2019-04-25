<?php

namespace App\Auxiliares;

use Carbon\Carbon;

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

    public function ejecutarConsulta()
    {
        try {
            $modelo = $this->modelo;
            if ($modelo == null || $modelo == false) {
                return false;
            }

            if (!is_null($this->relaciones)) {
                $lista = $modelo::with($this->relaciones);
            } else {
                $lista = $modelo;
            }

            if ($this->eliminados) {
                $lista = $lista->onlyTrashed();
            }

            if (!is_null($this->campos)) {
                $lista = $lista->select($this->campos);
            }

            if (!is_null($this->buscar)) {
                $lista = $this->buscar($lista, $this->campos, $this->buscar);
            }

            if ($this->ordenarPor) {
                $lista = $lista->orderBy($this->ordenarPor, $this->orden);
            }

            $resultado = $lista->paginate($this->paginado);

            if ($resultado) {
                $items = $resultado->items();

                $paginacion = [
                    "total" => $resultado->total(),
                    "per_page" => $resultado->perPage(),
                    "current_page" => $resultado->currentPage(),
                    "last_page" => $resultado->lastPage(),
                    "first_page_url" => $resultado->toArray()['first_page_url'],
                    "last_page_url" => $resultado->toArray()['last_page_url'],
                    "next_page_url" => $resultado->nextPageUrl(),
                    "prev_page_url" => $resultado->previousPageUrl(),
                    "path" => $resultado->resolveCurrentPath(),
                    "from" => $resultado->firstItem(),
                    "to" => $resultado->lastItem()
                ];

                return [
                    'datos' => $items,
                    'paginacion' => $paginacion
                ];
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    private function buscar($consulta, $campos, $buscar)
    {
        return $consulta->where(function ($query) use ($campos, $buscar) {
            foreach ($campos as $campo) {
                $query->orWhere($campo, 'like', "%{$buscar}%");
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
