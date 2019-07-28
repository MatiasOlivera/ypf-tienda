import { Respuesta } from '@/services/cliente-http';

import { MensajeError, MensajeExito } from './mensaje-tipos';

export type RespuestaMensajeExito = Respuesta<
  true,
  200,
  { mensaje: MensajeExito }
>;
export type RespuestaMensajeError = Respuesta<
  false,
  500,
  { mensaje: MensajeError }
>;

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
export type ValidacionObtenerTodos = ErroresValidacion<ParametrosObtenerTodos>;

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

export type Buscar = string;
export type Eliminados = boolean;
export type Pagina = number;
export type PorPagina = number;
export type DireccionOrden = 'asc' | 'desc';

export interface ParametrosObtenerTodos<CamposOrden extends string = string> {
  buscar?: Buscar;
  eliminados?: Eliminados;
  pagina?: Pagina;
  porPagina?: PorPagina;
  ordenarPor?: CamposOrden;
  orden?: DireccionOrden;
}

// Paginación
export interface Paginacion {
  total: number;
  porPagina: number;
  paginaActual: number;
  ultimaPagina: number;
  desde: number | null;
  hasta: number | null;
  rutas: Rutas;
}

interface Rutas {
  primeraPagina: string;
  ultimaPagina: string;
  siguientePagina: string | null;
  paginaAnterior: string | null;
  base: string;
}
