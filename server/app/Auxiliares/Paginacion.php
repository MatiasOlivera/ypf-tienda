<?php

namespace App\Auxiliares;

class Paginacion
{
    protected $paginado;
    protected $orden;
    protected $campoOrden;

    public function __Construct(){
        $this->paginado = 10;
        $this->campoOrden = 'id';
        $this->orden = 'ASC';
    }

    /**
     * @param integer $int
     */
    Public function setRegistrosPorPagina($int){
        if (is_numeric($int)) {
            $numero = $int + 0;
            if (is_int($numero)) {
                $this->paginado = $int;
            }else{
                $this->paginado = 10;
            }
        }else{
             $this->paginado = 10;
        }
    }

    /**
     * @param string $string
     */
    Public function setCampoOrden($string){
        if ($string) {
            $this->campoOrden = $string;
        }else{
            $this->campoOrden = 'id';
        }
    }

    /**
     * asigna orden ASC a la
     */
    Public function setOrdenASC(){
        $this->orden = 'ASC';
    }

    Public function setOrdenDESC(){
        $this->orden = 'DESC';
    }

    Public function getOrden(){
        return $this->orden;
    }

    Public function getCampoOrden(){
        return $this->campoOrden;
    }

    Public function getRegistrosPorPagina(){
        return $this->paginado;
    }
}
