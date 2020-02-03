<?php

namespace App\Http\Resources;

use App\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
{
    public static $wrap = 'producto';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $estaAutenticado = Auth::check();
        $usuario = Auth::user();

        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'presentacion' => $this->presentacion,

            $this->mergeWhen(
                $estaAutenticado && $usuario->can('administrar_precios', Producto::class),
                [
                    'precio_por_mayor' => $this->precio_por_mayor,
                    'consumidor_final' => $this->consumidor_final,
                ]
            ),

            'imagen' => $this->imagen,

            'es_favorito' => $this->when(
                $estaAutenticado && $usuario->can('administrar_favoritos', Producto::class),
                $this->getEsFavorito()
            ),

            'id_categoria' => $this->id_categoria,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at
        ];
    }

    public function getEsFavorito()
    {
        $usuario = $this->whenLoaded('usuariosQueMarcaronComoFavorito');

        if ($usuario instanceof MissingValue) {
            $this->loadMissing([
                'usuariosQueMarcaronComoFavorito' => function ($consulta) {
                    $consulta->where('cliente_usuario_id', Auth::id());
                }
            ]);
        }

        return $this->usuariosQueMarcaronComoFavorito->isNotEmpty();
    }
}
