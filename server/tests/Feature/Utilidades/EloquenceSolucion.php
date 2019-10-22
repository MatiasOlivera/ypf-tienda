<?php

namespace Tests\Feature\Utilidades;

use ReflectionClass;
use Illuminate\Database\Eloquent\Model;

trait EloquenceSolucion
{
    /**
     * Eloquence agrega un atributo $hooks en los modelos en donde es usado.
     * Cada vez que se crea una nueva instancia se agrega un valor a $hooks
     * pero en ningún momento es reseteado
     *
     * Error: "Maximum function nesting level of '256' reached, aborting!""
     *
     * @see https://github.com/jarektkaczyk/eloquence/issues/117#issuecomment-286877952
     * @return void
     */
    protected function tearDown()
    {
        parent::tearDown();

        foreach (get_declared_classes() as $clase) {
            $refleccion = new ReflectionClass($clase);
            if (!$refleccion->isAbstract()
                && is_subclass_of($clase, Model::class)
                && $refleccion->hasMethod('flushHooks')
            ) {
                $clase::flushHooks();
            }
        }
    }
}
