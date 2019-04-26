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

    private function validarArrayDeStrings(array $arreglo): void
    {
        foreach ($arreglo as $elemento) {
            if (!is_string($elemento)) {
                throw new Exception("No es un array de strings");
            }
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
     * @param bool $soloEliminados
     */
    private function setEliminados(bool $soloEliminados = false): void
    {
        $this->eliminados = $soloEliminados;
    }

    private function setModelo(string $modelo): void
    {
        $nombreModelo = "\\App\\{$modelo}";
        $this->modelo = new $nombreModelo;
    }

    /**
     * @param string $valorBuscado
     */
    private function setBuscar(string $valorBuscado): void
    {
        $this->buscar = $valorBuscado;
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
     * @param array $relaciones
     */
    private function setModelosRelacionados(array $relaciones): void
    {
        $this->validarArrayDeStrings($relaciones);
        $this->relaciones = $relaciones;
    }

    /**
     * @param array $campos
     */
    private function setCampos(array $campos): void
    {
        $this->validarArrayDeStrings($campos);
        $this->campos = $campos;
    }

    /**
     * @param int $limite
     */
    private function setRegistrosPorPagina(int $limite): void
    {
        $this->paginado = $limite;
    }

    /**
     * @param string $string
     */
    private function setOrdenarPor(string $string): void
    {
        $this->ordenarPor = $string;
    }

    /**
     * asigna orden ASC o DESC a la consulta
     * @param bool $orden
     */
    private function setOrden(bool $orden = true): void
    {
        $this->orden = $orden ? 'ASC' : 'DESC';
    }
}
