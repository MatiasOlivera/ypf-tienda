/* eslint-disable camelcase */
import {
  ParametrosObtenerTodos,
  Paginacion,
  RespuestaValidacion,
  RespuestaMensajeError,
  RespuestasComunesApiSinToken,
  RespuestasComunesApi
} from '@/types/respuesta-tipos';
import { Respuesta } from '@/services/cliente-http';
import { Producto, ProductoCliente } from '@/types/tipos-producto';
import { ProductoServidor } from '../producto/producto-tipo';
import { Omitir } from '@/types/utilidades';

export type ParametrosGetProductosNoAutenticado = Omitir<
  ParametrosObtenerTodos<CamposOrdenamientoNoAutenticado>,
  'eliminados'
>;

export type CamposOrdenamientoNoAutenticado =
  | 'codigo'
  | 'nombre'
  | 'created_at'
  | 'updated_at'
  | 'deleted_at';

export interface ParametrosGetProductosAutenticado
  extends ParametrosObtenerTodos<CamposOrdenamientoAutenticado> {
  soloEliminados?: boolean;
}

export type CamposOrdenamientoAutenticado =
  | CamposOrdenamientoNoAutenticado
  | 'precio_por_mayor'
  | 'consumidor_final';

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

export type RespuestaProductosAutenticado =
  | RespuestasProductos<ProductoCliente>
  | RespuestasComunesApi;
