<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Cliente;
use Tests\TestCase;
use App\ClienteRazonSocial;
use Tests\Feature\Utilidades\AuthHelper;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Utilidades\EloquenceSolucion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilidades\EstructuraJsonHelper;
use Tests\Feature\Utilidades\AtributosClienteRazonSocial;

class ClienteRazonSocialControllerTest extends TestCase
{
    use AuthHelper;
    use RefreshDatabase;
    use EloquenceSolucion;
    use EstructuraJsonHelper;
    use AtributosClienteRazonSocial;

    private function getEstructuraRazones()
    {
        return array_merge(['razonesSociales']);
    }

    private function getEstructuraRazonSocial()
    {
        return ['razonSocial' => $this->atributosClienteRazonSocial];
    }

    private function getEstructuraRazonSocialConMensaje()
    {
        return array_merge(
            ['razonSocial' => $this->atributosClienteRazonSocial],
            $this->estructuraMensaje
        );
    }

    /**
     * No debería obtener ninguna razon social
     */
    public function testNoDeberiaObtenerNingunaRazonSocial()
    {
        $cliente = factory(Cliente::class)->create();
        $id = $cliente->id;

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', "api/clientes/$id/razones");

        $estructura = $this->getEstructuraRazones();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertExactJson(['razonesSociales' => []]);
    }

    /**
     * Debería obtener razones sociales
     */
    public function testDeberiaObtenerRazonesSociales()
    {
        $cliente = factory(Cliente::class)->states('razonesSociales')->create();
        $id = $cliente->id;

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', "api/clientes/$id/razones");

        $estructura = $this->getEstructuraRazones();
        $razones = $cliente->razonesSociales()
            ->orderBy('denominacion', 'ASC')
            ->get()
            ->toArray();

        $respuesta
            ->assertOk()
            ->assertJsonStructure($estructura)
            ->assertJson(['razonesSociales' => $razones]);
    }

    public function test_deberia_crear_una_razon_social()
    {
        $cliente = factory(Cliente::class)->create();
        $id = $cliente->id;

        $razonSocial = factory(ClienteRazonSocial::class)->make()->toArray();

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', "api/clientes/$id/razones", $razonSocial);

        $estructura = $this->getEstructuraRazonSocialConMensaje();
        unset($razonSocial['id']);

        $respuesta
            ->assertStatus(201)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'razonSocial' => $razonSocial,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'GUARDADO',
                    'descripcion' => "La razón social {$razonSocial['denominacion']} ha sido creada"
                ]
            ]);
    }

    public function test_deberia_obtener_una_razon_social()
    {
        $cliente = factory(Cliente::class)->create();
        $razonSocial = factory(ClienteRazonSocial::class)->create();
        $cliente->razonesSociales()->attach($razonSocial);
        $cliente->save();

        $clienteId = $cliente->id;
        $id = $razonSocial->id;

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('GET', "api/clientes/$clienteId/razones/$id");

        $estructura = $this->getEstructuraRazonSocial();

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson(['razonSocial' => $razonSocial->toArray()]);
    }

    public function test_deberia_editar_una_razon_social()
    {
        $cliente = factory(Cliente::class)->create();
        $razonSocial = factory(ClienteRazonSocial::class)->create();
        $cliente->razonesSociales()->attach($razonSocial);
        $cliente->save();

        $clienteId = $cliente->id;
        $id = $razonSocial->id;

        $razonSocialModificada = array_merge($razonSocial->toArray(), ['denominacion' => 'AppLab']);

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('PUT', "api/clientes/$clienteId/razones/$id", $razonSocialModificada);

        $estructura = $this->getEstructuraRazonSocialConMensaje();
        unset($razonSocialModificada['updated_at']);

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'razonSocial' => $razonSocialModificada,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ACTUALIZADO',
                    'descripcion' => "La razón social AppLab ha sido modificada"
                ]
            ]);
    }

    public function test_deberia_eliminar_una_razon_social()
    {
        $cliente = factory(Cliente::class)->create();
        $razonSocial = factory(ClienteRazonSocial::class)->create();
        $cliente->razonesSociales()->attach($razonSocial);
        $cliente->save();

        $clienteId = $cliente->id;
        $id = $razonSocial->id;

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('DELETE', "api/clientes/$clienteId/razones/$id");

        $estructura = $this->getEstructuraRazonSocialConMensaje();
        $razonSocialArray = $razonSocial->toArray();
        unset($razonSocialArray['updated_at']);

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'razonSocial' => $razonSocialArray,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ELIMINADO',
                    'descripcion' => "La razón social {$razonSocialArray['denominacion']} ha sido eliminada"
                ]
            ]);

        $deletedAt = $respuesta->getData(true)['razonSocial']['deleted_at'];
        $this->assertNotNull($deletedAt);
    }

    public function test_deberia_restaurar_una_razon_social()
    {
        $cliente = factory(Cliente::class)->create();
        $razonSocial = factory(ClienteRazonSocial::class)->create();
        $cliente->razonesSociales()->attach($razonSocial);
        $cliente->save();

        $clienteId = $cliente->id;
        $id = $razonSocial->id;

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('DELETE', "api/clientes/$clienteId/razones/$id");

        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', "api/clientes/$clienteId/razones/$id/restaurar");

        $estructura = $this->getEstructuraRazonSocialConMensaje();
        $razonSocialArray = $razonSocial->toArray();
        unset($razonSocialArray['updated_at']);

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'razonSocial' => $razonSocialArray,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'RESTAURADO',
                    'descripcion' => "La razón social {$razonSocialArray['denominacion']} ha sido dada de alta"
                ]
            ]);

        $deletedAt = $respuesta->getData(true)['razonSocial']['deleted_at'];
        $this->assertNull($deletedAt);
    }

    public function test_deberia_asociar_cliente_con_razon_social()
    {
        $cliente = factory(Cliente::class)->create();
        $razonSocial = factory(ClienteRazonSocial::class)->create();

        $clienteId = $cliente->id;
        $id = $razonSocial->id;

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('POST', "api/clientes/$clienteId/razones/$id/asociar");

        $estructura = $this->getEstructuraRazonSocialConMensaje();
        $razonSocialArray = $razonSocial->toArray();
        unset($razonSocialArray['updated_at']);

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'razonSocial' => $razonSocialArray,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'ASOCIADOS',
                    'descripcion' => "Se asoció con éxito la razón social {$razonSocial->denominacion} al cliente {$cliente->nombre}"
                ]
            ]);
    }

    public function test_deberia_desasociar_cliente_de_razon_social()
    {
        $cliente = factory(Cliente::class)->create();
        $razonSocial = factory(ClienteRazonSocial::class)->create();

        $clienteId = $cliente->id;
        $id = $razonSocial->id;

        $cabeceras = $this->loguearseComo('defecto');
        $respuesta = $this
            ->withHeaders($cabeceras)
            ->json('DELETE', "api/clientes/$clienteId/razones/$id/desasociar");

        $estructura = $this->getEstructuraRazonSocialConMensaje();
        $razonSocialArray = $razonSocial->toArray();
        unset($razonSocialArray['updated_at']);

        $respuesta
            ->assertStatus(200)
            ->assertJsonStructure($estructura)
            ->assertJson([
                'razonSocial' => $razonSocialArray,
                'mensaje' => [
                    'tipo' => 'exito',
                    'codigo' => 'DESASOCIADOS',
                    'descripcion' => "Se ha desasociado la razón social {$razonSocial->denominacion} del cliente {$cliente->nombre}"
                ]
            ]);
    }
}
