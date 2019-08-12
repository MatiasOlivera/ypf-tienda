<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Sofa\Eloquence\{Eloquence, Mappable};
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use SoftDeletes, Eloquence, Mappable;

    protected $table = 'productos';
    protected $primaryKey = 'id';

    protected $maps = [
        'codigo' => 'codigo_prod',
        'nombre' => 'nom_prod',
        'id_categoria' => 'ID_CAT',
        'precio_por_mayor' => 'por_mayor',
        'consumidor_final' => 'cons_fin'
    ];

    protected $appends = [
        'codigo',
        'nombre',
        'id_categoria',
        'precio_por_mayor',
        'consumidor_final',
        'imagen'
    ];

    protected $hidden = [
        'codigo_prod',
        'nom_prod',
        'ID_CAT',
        'por_mayor',
        'cons_fin',
        'estado',
        'imagen_ruta',

        /**
         * Estos campos no son usados
         */
        'id_marca',
        'costo',
        'iva'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'codigo',
        'nombre',
        'presentacion',
        'id_categoria',
        'costo',
        'precio_por_mayor',
        'iva',
        'consumidor_final'
    ];

    public function getImagenAttribute(): ?string
    {
        return $this->imagen_ruta
            ? Storage::disk('productos')->url($this->imagen_ruta)
            : null;
    }

    /**
     * Obtener todos los usuarios que marcaron este producto como favorito
     */
    public function usuariosQueMarcaronComoFavorito()
    {
        return $this->belongsToMany('App\User', 'productos_favoritos', 'producto_id', 'cliente_usuario_id')
            ->as('favorito')
            ->withTimestamps();
    }

    public function categoria()
    {
        return $this->belongsTo('App\CategoriaProducto', 'ID_CAT', 'ID_CAT_prod');
    }
}
