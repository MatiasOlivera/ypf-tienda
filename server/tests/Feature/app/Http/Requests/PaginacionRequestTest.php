<?php

namespace Tests\Feature;

use Validator;
use Tests\TestCase;
use App\Http\Requests\PaginacionRequest;

class PaginacionRequestTest extends TestCase
{
    /**
     * Debería pasar la validación cuando no se pasa ningún parámetro
     */
    public function test_deberia_pasar_cuando_no_se_pasa_ningun_parametro()
    {
        $peticion = new PaginacionRequest();
        $peticion->authorize();
        $validador = Validator::make($peticion->all(), $peticion->rules());

        $paso = $validador->passes();
        $errores = $validador->errors()->getMessages();

        $this->assertTrue($paso);
        $this->assertEmpty($errores);
    }

    /**
     * Debería pasar la validación cuando los valores son válidos
     */
    public function test_deberia_pasar_cuando_los_valores_son_validos()
    {
        $input = [
            'buscar' => 'elaion',
            'eliminados' => true,
            'pagina' => 1,
            'porPagina' => 10,
            'ordenarPor' => 'nombre',
            'orden' => 'asc'
        ];
        $peticion = new PaginacionRequest($input);
        $peticion->authorize();
        $validador = Validator::make($peticion->all(), $peticion->rules());

        $paso = $validador->passes();
        $errores = $validador->errors()->getMessages();

        $this->assertTrue($paso);
        $this->assertEmpty($errores);
    }
}
