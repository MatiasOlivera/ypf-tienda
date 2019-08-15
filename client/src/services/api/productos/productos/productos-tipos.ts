/* eslint-disable camelcase */
import {
  ParametrosObtenerTodos,
  Paginacion,
  RespuestaValidacion,
  RespuestaMensajeError,
  RespuestasComunesApiSinToken
} from '@/types/respuesta-tipos';
import { Respuesta } from '@/services/cliente-http';
import { Producto } from '@/types/tipos-producto';
import { ProductoServidor } from '../producto/producto-tipo';

export type ParametrosGetProductos = ParametrosObtenerTodos<CamposOrdenamiento>;

export type CamposOrdenamiento =
  | 'codigo'
  | 'nombre'
  | 'precio_por_mayor'
  | 'consumidor_final'
  | 'created_at'
  | 'updated_at'
  | 'deleted_at';

interface DatosProductos<TipoProducto> {
  productos: Array<TipoProducto>;
  paginacion: Paginacion;
}

type RespuestasProductos<TipoProducto> =
  | RespuestaValidacion<ParametrosObtenerTodos>
  | Respuesta<true, 200, DatosProductos<TipoProducto>>
  | RespuestaMensajeError;

// Respuesta del servidor
type RespuestaProductosServidor = RespuestasProductos<ProductoServidor>;

export type RespuestaProductosServidorNoAutenticado =
  | RespuestaProductosServidor
  | RespuestasComunesApiSinToken;

// Respuesta del cliente
export type RespuestaProductos = RespuestasProductos<Producto>;

export type RespuestaProductosNoAutenticado =
  | RespuestaProductos
  | RespuestasComunesApiSinToken;
