<?php

namespace Tests\Unit;

use Mockery;
use Exception;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Factory;
use Illuminate\Container\Container;
use App\Http\Traits\CastFormRequest;
use PHPUnit\Framework\Constraint\IsType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Translation\Translator;

class CastFormRequestTest extends TestCase
{
    public function test_deberia_lanzar_una_excepcion_cuando_el_tipo_no_es_valido()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("El tipo binary no es válido");

        $parametros = ['binario' => '1'];
        $request = $this->peticionFactory($parametros);
    }

    public function test_deberia_convertir_a_null()
    {
        $parametros = ['cadena' => 'null'];
        $request = $this->peticionFactory($parametros);

        $this->assertNull($request->input('cadena'));
    }

    public function test_deberia_convertir_a_integer()
    {
        $parametros = ['entero' => '10'];
        $request = $this->peticionFactory($parametros);

        $this->assertInternalType(IsType::TYPE_INT, $request->input('entero'));
    }

    public function test_no_deberia_convertir_a_integer()
    {
        $parametros = ['entero' => 'diez'];
        $request = $this->peticionFactory($parametros);

        $this->assertInternalType(IsType::TYPE_STRING, $request->input('entero'));
    }

    public function test_deberia_convertir_a_float()
    {
        $parametros = ['flotante' => '10.5'];
        $request = $this->peticionFactory($parametros);

        $this->assertInternalType(IsType::TYPE_FLOAT, $request->input('flotante'));
    }

    public function test_no_deberia_convertir_a_float()
    {
        $parametros = ['flotante' => 'diez.cinco'];
        $request = $this->peticionFactory($parametros);

        $this->assertInternalType(IsType::TYPE_STRING, $request->input('flotante'));
    }

    public function test_deberia_convertir_a_string()
    {
        $parametros = ['cadena' => true];
        $request = $this->peticionFactory($parametros);

        $this->assertInternalType(IsType::TYPE_STRING, $request->input('cadena'));
    }

    public function test_deberia_convertir_a_boolean_verdadero()
    {
        $booleanos = ["true", "1", 1];

        foreach ($booleanos as $booleano) {
            $parametros = ['booleano' => $booleano];
            $request = $this->peticionFactory($parametros);

            $input = $request->input('booleano');
            $this->assertInternalType(IsType::TYPE_BOOL, $input);
            $this->assertTrue($input);
        }
    }

    public function test_deberia_convertir_a_boolean_falso()
    {
        $booleanos = ["false", "0", 0];

        foreach ($booleanos as $booleano) {
            $parametros = ['booleano' => $booleano];
            $request = $this->peticionFactory($parametros);

            $input = $request->input('booleano');
            $this->assertInternalType(IsType::TYPE_BOOL, $input);
            $this->assertFalse($input);
        }
    }

    public function test_no_deberia_convertir_a_boolean()
    {
        $parametros = ['booleano' => 'verdadero'];
        $request = $this->peticionFactory($parametros);

        $this->assertInternalType(IsType::TYPE_STRING, $request->input('booleano'));
    }

    public function test_deberia_convertir_a_array()
    {
        $parametros = ['arreglo' => '1,2,3'];
        $request = $this->peticionFactory($parametros);

        $input = $request->input('arreglo');
        $this->assertInternalType(IsType::TYPE_ARRAY, $input);
        $this->assertEquals(['1', '2', '3'], $input);
    }

    public function test_deberia_convertir_a_array_simple()
    {
        $parametros = ['arreglo' => '123'];
        $request = $this->peticionFactory($parametros);

        $input = $request->input('arreglo');
        $this->assertInternalType(IsType::TYPE_ARRAY, $input);
        $this->assertEquals(['123'], $input);
    }

    protected function peticionFactory($parametros)
    {
        $query = http_build_query($parametros);
        $request = $this->createRequest('?'.$query);
        $request->validateResolved();
        return $request;
    }

    /**
     * Create a new request of the given type.
     *
     * @param string $url
     *
     * @return \Illuminate\Foundation\Http\FormRequest
     */
    protected function createRequest(string $url): FormRequest
    {
        $container = tap(new Container, function (Container $container) {
            $container->instance(
                \Illuminate\Contracts\Validation\Factory::class,
                $this->createValidationFactory($container)
            );
        });
        $request = MockFormRequest::create($url);
        return $request->setContainer($container);
    }

    /**
     * Create a new validation factory.
     *
     * @param  \Illuminate\Container\Container $container
     *
     * @return \Illuminate\Validation\Factory
     */
    protected function createValidationFactory($container): Factory
    {
        $translator = Mockery::mock(Translator::class)->shouldReceive('trans')
            ->zeroOrMoreTimes()->andReturn('error')->getMock();
        return new Factory($translator, $container);
    }
}

class MockFormRequest extends FormRequest
{
    use CastFormRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function casts(): array
    {
        return [
            'entero' => 'integer',
            'flotante' => 'float',
            'cadena' => 'string',
            'booleano' => 'boolean',
            'arreglo' => 'array',
            'fecha' => 'date',
            'fechahora' => 'datetime',
            'marcadetiempo' => 'timestamp',
            'binario' => 'binary'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
