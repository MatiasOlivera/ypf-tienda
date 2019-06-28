<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\{Eloquence, Mappable};

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

    protected $appends = ['codigo', 'nombre', 'id_categoria', 'precio_por_mayor', 'consumidor_final'];

    protected $hidden = [
        'codigo_prod',
        'nom_prod',
        'ID_CAT',
        'por_mayor',
        'cons_fin',
        'estado',

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

    public function categoria()
    {
        return $this->belongsTo('App\CategoriaProducto', 'ID_CAT', 'ID_CAT_prod');
    }
}
