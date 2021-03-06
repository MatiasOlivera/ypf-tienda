<?php

namespace App\Http\Resources;

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
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'presentacion' => $this->presentacion,
            // FIXME: debería mostrar los campos precio_por_mayor y consumidor_final
            // solo cuando el usuario logueado es el vendedor
            'precio_por_mayor' => $this->precio_por_mayor,
            'consumidor_final' => $this->consumidor_final,
            'imagen' => $this->imagen,
            // FIXME: debería mostrar el campo es_favorito solo cuando el
            // usuario logueado es un cliente
            'es_favorito' => $this->when(Auth::check(), $this->getEsFavorito()),
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
