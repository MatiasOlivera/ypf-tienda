import { Respuesta } from '@/services/cliente-http';

import { MensajeError, MensajeExito } from './mensaje-tipos';

export type RespuestaMensajeExito = Respuesta<true, 200, MensajeExito>;
export type RespuestaMensajeError = Respuesta<false, 500, MensajeError>;

/**
 * Cuando se crea un formulario, el estado de la validación de los inputs es
 * desconocido, es decir, no se sabe si es válido o inválido hasta que se
 * realize la petición.
 *
 * @see https://bootstrap-vue.js.org/docs/components/form/#validation
 */
export type ErroresValidacionInicial<Modelo> = {
  [Clave in keyof Modelo]: { esValido: null; error: null }
};

export type ErroresValidacion<Modelo> = {
  [Clave in keyof Modelo]:
    | { esValido: true; error: null }
    | { esValido: false; error: string }
};

export type RespuestaValidacion<Modelo> = Respuesta<
  false,
  422,
  { errores: ErroresValidacion<Modelo> }
>;

export type RespuestaNoAutorizado = Respuesta<false, 401, null>;
export type RespuestaErrorInterno = Respuesta<false, 500, null>;
export type RespuestasComunesApi =
  | RespuestaNoAutorizado
  | RespuestaErrorInterno;
export type RespuestasComunesApiSinToken = RespuestaErrorInterno;
