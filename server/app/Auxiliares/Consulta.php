<?php

namespace App\Auxiliares;

class Consulta
{
    protected $eliminados;
    protected $buscar;
    protected $relaciones;
    protected $campos;


    public function __Construct(){
        $this->eliminados = false;
        $this->campos = null;
        $this->relaciones = null;
        $this->buscar = null;
    }

    public function soloEliminados(){
        $this->eliminados = true;
    }

    /**
     * @param string $string
     */
    public function setBuscar($string){
        if (!is_null($string) ) {
            $this->buscar = $string;
        }
    }

    /**
     * @param array $array
     */
    public function setModelosRelacionados($array){
        if (!is_null($array) && is_array($array)) {
            $this->relaciones = $array;
        }
    }

    /**
     * @param array $array
     */
    public function setCampos($array){
        if (!is_null($array) && is_array($array)) {
            $this->campos = $array;
        }
    }

    /**
     * retorna string a buscar o null
     */
    public function getValorBuscar(){
        return $this->buscar;
    }

    /**
     * retorna Boolean
     */
    public function getEliminados(){
        return $this->eliminados;
    }

    /**
     * retorna Array de campos o null
     */
    public function getCampos(){
        return $this->campos;
    }
    /**
     * retorna Array de nombres de Modelos o null
     */
    public function getRelaciones(){
        return $this->relaciones;
    }
}
