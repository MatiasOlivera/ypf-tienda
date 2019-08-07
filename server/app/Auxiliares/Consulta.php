<?php

namespace App\Auxiliares;

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

    public function ejecutarConsulta(array $parametros): array
    {
        $this->setParametros($parametros);

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

        $resultado = $consulta->paginate($this->paginado, ['*'], 'pagina');

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

    private function setParametros(array $parametros): void
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
            if (isset($paginado['ordenarPor'])) {
                $this->setOrdenarPor($paginado['ordenarPor']);
            }
            if (isset($paginado['orden'])) {
                $this->setOrden($paginado['orden']);
            }
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

    /**
     * @param bool $soloEliminados
     */
    private function setEliminados(bool $soloEliminados = false): void
    {
        $this->eliminados = $soloEliminados;
    }

    private function setModelo($modelo): void
    {
        if (is_string($modelo)) {
            $nombreModelo = "\\App\\{$modelo}";
            $this->modelo = new $nombreModelo;
        } else {
            $this->modelo = $modelo;
        }
    }

    /**
     * @param string $valorBuscado
     */
    private function setBuscar(string $valorBuscado): void
    {
        $this->buscar = $valorBuscado;
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
     * @param string $campo
     */
    private function setOrdenarPor(string $campo): void
    {
        $this->ordenarPor = $campo;
    }

    /**
     * asigna orden ASC o DESC a la consulta
     * @param string $orden
     */
    private function setOrden(string $orden = 'ASC'): void
    {
        $this->orden = $orden;
    }
}
