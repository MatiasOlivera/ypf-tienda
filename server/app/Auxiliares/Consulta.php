<?php

namespace App\Auxiliares;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Consulta
{
    protected $eliminados;
    protected $buscar;
    protected $relaciones;
    protected $campos;
    protected $paginado;
    protected $orden;
    protected $ordenarPor;
    protected $modelo;
    protected $periodoDeBusqueda;

    public function __Construct()
    {
        //consulta
        $this->eliminados = false;
        $this->campos = null;
        $this->relaciones = null;
        $this->buscar = null;
        $this->periodoDeBusqueda = null;
        //paginado
        $this->paginado = 10;
        $this->ordenarPor = 'id';
        $this->orden = 'ASC';
        $this->modelo = null;
    }

    //parametros
    public function setParametros($array)
    {
        if (!is_array($array)) {
            return false;
        }
        if (isset($array['modelo'])) {
            $this->setModelo($array['modelo']);
        }
        if (isset($array['campos'])) {
            $this->setCampos($array['campos']);
        }
        if (isset($array['relaciones'])) {
            $this->setModelosRelacionados($array['relaciones']);
        }
        if (isset($array['eliminados'])) {
            $this->setEliminados($array['eliminados']);
        }
        if (isset($array['buscar'])) {
            $this->setBuscar($array['buscar']);
        }

        if (isset($array['paginado'])) {
            $paginado = $array['paginado'];

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

    public function validarParametros($array)
    {
        $this->setParametros($array);
        $parametros =  $this->getParametros();

        return $parametros;
    }
    public function getParametros()
    {
        return $parametros = [
            'campos'     => $this->campos,
            'relaciones' => $this->relaciones,
            'buscar'     => $this->buscar,
            'eliminados' => $this->eliminados,
            'paginado'   => [
                'porPagina'   => $this->paginado,
                'ordenadoPor' => $this->ordenarPor,
                'orden'       => $this->orden,
            ]
        ];
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

            $Resultado = $lista->paginate($this->paginado);

            if ($Resultado) {
                return $Resultado;
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

    private function getBoolean($boolean)
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

    public function getDateOfString($date)
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
    public function setEliminados($boolean)
    {
        $bool = $this->getBoolean($boolean);

        $this->eliminados = $bool;
    }

    public function setModelo($string)
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
    public function setBuscar($string)
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
    public function setPeriodoBusqueda($dateArray)
    {
        if (!is_null($dateArray) && is_Array($dateArray)) {
            $inicio = $this->getDateOfString($dateArray['desde']);
            $fin    = $this->getDateOfString($dateArray['hasta']);
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
    public function setModelosRelacionados($array)
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
    public function setCampos($array)
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

    //ordenamiento
    /**
     * @param integer $int
     */
    public function setRegistrosPorPagina($int)
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
    public function setOrdenarPor($string)
    {
        if (!is_null($string) && is_string($string)) {
            $this->ordenarPor = $string;
        } else {
            $this->ordenarPor = 'id';
        }
    }

    /**
     * @param boolean $boolean
     * asigna orden ASC o DESC a la consulta
     */
    public function setOrden($boolean)
    {
        if (!is_null($boolean)) {
            $bool = $this->getBoolean($boolean);
            $this->orden = ($bool) ? 'ASC' : 'DESC';
        }
    }
}
