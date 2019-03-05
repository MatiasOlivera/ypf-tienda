<?php

namespace App\Auxiliares;

class Mensaje
{
    protected $error;
    protected $exito;

    public function __Construct(){
        $this->error = [
                'codigo' => 'ERROR_BASE_CONTROLLER' ,
                'descripcion' => 'Ha ocurrido un error, intente nuevamente',
            ];

        $this->exito = [
            'codigo' => null ,
            'descripcion' => 'Operacion Exitosa',
        ];
    }

    /**
     * @param string $mensaje
     * @param string $codigo
     */
    public function setMensajeExito($mensaje, $codigo = null){
        if (!is_null($mensaje)) {
            $this->exito = [
                'codigo' => $codigo ,
                'descripcion' => $mensaje,
            ];
        }
    }

     /**
     * @param string $mensaje
     * @param string $codigo
     */
    public function setMensajeError($mensaje, $codigo = null){
        if (!is_null($mensaje)) {
            $this->error = [
                'codigo' => $codigo ,
                'descripcion' => $mensaje,
            ];
        }
    }

    /**
     * return Objet { codigo:'string', descripcion: 'string'}
     */
    public function getMensajeError()
    {
        $mensaje = $this->exito;
        return compact('mensaje');
    }

    /**
     * return Objet { codigo:'string', descripcion: 'string'}
     */
    public function getMensajeExito()
    {
        $mensaje = $this->exito;
        return compact('mensaje');
    }
}
