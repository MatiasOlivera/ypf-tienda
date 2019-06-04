import { Respuesta } from '@/services/cliente-http';

import { MensajeError, MensajeExito } from './mensaje-tipos';

export type RespuestaMensajeExito = Respuesta<true, 200, MensajeExito>;
export type RespuestaMensajeError = Respuesta<false, 500, MensajeError>;

export type ErroresValidacion<Modelo> = {
  [Clave in keyof Modelo]: string | null
};

export type RespuestaValidacion<Modelo> = Respuesta<
  false,
  422,
  { errores: ErroresValidacion<Modelo> }
>;
