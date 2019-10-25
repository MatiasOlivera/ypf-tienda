/* eslint-disable camelcase */

import { Respuesta } from '@/services/cliente-http';
import {
  RespuestaMensajeError,
  RespuestasComunesApiSinToken
} from '@/types/respuesta-tipos';
import { Producto, ProductoBase } from '@/types/tipos-producto';

export type RespuestasProducto<TipoProducto> =
  | Respuesta<true, 200, { producto: TipoProducto }>
  | RespuestaMensajeError;

// Respuesta del servidor
type RespuestaProductoServidor = RespuestasProducto<ProductoServidor>;

export type RespuestaProductoServidorNoAutenticado =
  | RespuestaProductoServidor
  | RespuestasComunesApiSinToken;

// Respuesta del cliente
export type RespuestaProducto = RespuestasProducto<Producto>;

export type RespuestaProductoNoAutenticado =
  | RespuestaProducto
  | RespuestasComunesApiSinToken;

export interface ProductoServidor extends ProductoBase {
  precio_por_mayor: string;
  consumidor_final: string;
}
