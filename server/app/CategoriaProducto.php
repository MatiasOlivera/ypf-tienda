<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Eloquence\{Eloquence, Mappable};

class CategoriaProducto extends Model
{
    use SoftDeletes, Eloquence, Mappable;

    protected $table = 'categorias';
    protected $primaryKey = 'ID_CAT_prod';

    protected $fillable = ['descripcion'];

    protected $maps = [
        'id' => 'ID_CAT_prod',
        'descripcion' => 'desc_cat'
    ];

    protected $appends = ['id', 'descripcion'];

    protected $visibble = ['id', 'descripcion'];

    protected $hidden = ['ID_CAT_prod', 'desc_cat', 'estado'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function productos()
    {
        return $this->hasMany('App\Producto', 'ID_CAT', 'ID_CAT_prod');
    }
}
