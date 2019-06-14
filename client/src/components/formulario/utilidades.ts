import { Diccionario } from '@/types/utilidades';
import { ErroresValidacion } from '@/types/respuesta-tipos';

export type EstadoValidacion<Formulario> = {
  [Clave in keyof Formulario]: 'invalid' | null
};

/**
 * Obtener el estado de validación para cada uno de los campos de un formulario
 *
 * @see https://bootstrap-vue.js.org/docs/components/form/#validation
 * @returns Devuelve un objeto con el estado correspondiente a cada campo,
 * si el campo es inválido el valor es 'invalid' sino es `null`
 */
// eslint-disable-next-line import/prefer-default-export
export function obtenerEstadoValidacion<Formulario extends Diccionario<any>>(
  errores: ErroresValidacion<Formulario>
): EstadoValidacion<Formulario> {
  const propiedades = Object.entries(errores);
  const estados = propiedades.map(([clave, valor]) => {
    const estado = valor !== null ? 'invalid' : null;
    return [clave, estado];
  });
  // Property 'fromEntries' does not exist on type 'ObjectConstructor'.
  // @ts-ignore
  return Object.fromEntries(estados);
}
