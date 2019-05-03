<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Requests\CastingDeTipos;

class EjemploRequest
{
    use CastingDeTipos;

    public function obtenerCadena($valor)
    {
        return $this->getCadena($valor);
    }

    public function obtenerBooleano($valor)
    {
        return $this->getBooleano($valor);
    }

    public function obtenerEntero($valor)
    {
        return $this->getEntero($valor);
    }
}

class CastingDeTiposTest extends TestCase
{
    /**
     * getCadena: Debería devolver null cuando el valor es null
     *
     * @return void
     */
    public function test_get_cadena_deberia_devolver_null_cuando_el_valor_es_null()
    {
        $instancia = new EjemploRequest();
        $cadena = $instancia->obtenerCadena(null);

        $this->assertNull($cadena);
    }

    /**
     * getCadena: Debería devolver null cuando la cadena está vacía
     *
     * @return void
     */
    public function test_get_cadena_deberia_devolver_null_cuando_la_cadena_esta_vacia()
    {
        $instancia = new EjemploRequest();
        $cadena = $instancia->obtenerCadena('');

        $this->assertNull($cadena);
    }

    /**
     * getCadena: Debería devolver la cadena cuando este definida
     *
     * @return void
     */
    public function test_get_cadena_deberia_devolver_la_cadena_cuando_este_definida()
    {
        $instancia = new EjemploRequest();
        $cadena = $instancia->obtenerCadena('elaion');

        $this->assertEquals('elaion', $cadena);
    }

    /**
     * getBooleano: Debería devolver null cuando el valor es null
     *
     * @return void
     */
    public function test_get_booleano_deberia_devolver_null_cuando_el_valor_es_null()
    {
        $instancia = new EjemploRequest();
        $booleano = $instancia->obtenerBooleano(null);

        $this->assertNull($booleano);
    }

    /**
     * getBooleano: Debería devolver true cuando el valor es 'true'
     *
     * @return void
     */
    public function test_get_booleano_deberia_devolver_true_cuando_el_valor_es_true()
    {
        $instancia = new EjemploRequest();
        $booleano = $instancia->obtenerBooleano('true');

        $this->assertTrue($booleano);
    }

    /**
     * getBooleano: Debería devolver false cuando el valor es 'false'
     *
     * @return void
     */
    public function test_get_booleano_deberia_devolver_false_cuando_el_valor_es_false()
    {
        $instancia = new EjemploRequest();
        $booleano = $instancia->obtenerBooleano('false');

        $this->assertNotTrue($booleano);
    }

    /**
     * getBooleano: Debería devolver el valor cuando no es booleano
     *
     * @return void
     */
    public function test_get_booleano_deberia_devolver_el_valor_cuando_no_es_booleano()
    {
        $instancia = new EjemploRequest();
        $booleano = $instancia->obtenerBooleano('1');

        $this->assertEquals('1', $booleano);
    }

    /**
     * getEntero: Debería devolver null cuando el valor es null
     *
     * @return void
     */
    public function test_get_entero_deberia_devolver_null_cuando_el_valor_es_null()
    {
        $instancia = new EjemploRequest();
        $entero = $instancia->obtenerEntero(null);

        $this->assertNull($entero);
    }

    /**
     * getEntero: Debería devolver el número entero cuando el valor sea numérico
     *
     * @return void
     */
    public function test_get_entero_deberia_devolver_el_numero_entero_cuando_el_valor_sea_numerico()
    {
        $instancia = new EjemploRequest();
        $entero = $instancia->obtenerEntero('10');

        $this->assertEquals(10, $entero);
    }

    /**
     * getEntero: Debería devolver el valor cuando no es numérico
     *
     * @return void
     */
    public function test_get_entero_deberia_devolver_el_valor_cuando_no_es_numerico()
    {
        $instancia = new EjemploRequest();
        $entero = $instancia->obtenerEntero('a');

        $this->assertEquals('a', $entero);
    }
}
