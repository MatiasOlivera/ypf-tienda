<?php

namespace App;

use App\Empleado;
use Sofa\Eloquence\Mappable;
use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;

class Recurso extends Model
{
    use Eloquence, Mappable;

    protected $table = 'recurso';
    protected $primaryKey = 'ID_recurso';

    protected $maps = [
        'id' => 'ID_recurso'
    ];

    protected $appends = ['id'];

    protected $hidden = ['ID_recurso'];

    protected $fillable = ['nombre'];

    public $timestamps = false;
}
