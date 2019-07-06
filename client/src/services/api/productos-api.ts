/* eslint-disable camelcase */
import { Respuesta } from '../cliente-http';
import {
  RespuestasComunesApi,
  RespuestasComunesApiSinToken,
  Paginacion,
  ParametrosObtenerTodos,
  RespuestaValidacion,
  RespuestaMensajeError
} from '@/types/respuesta-tipos';
import { Producto, ProductoBase } from '@/types/tipos-producto';
import { clienteApi } from '../cliente-api';

/**
 * Debería obtener un listado de productos
 */
export async function getProductos(
  parametros?: ParametrosObtenerTodos<CamposOrdenamiento>
): Promise<RespuestaProductos> {
  const respuesta = await clienteApi<RespuestaProductosServidor>({
    url: 'productos',
    metodo: 'GET',
    datos: parametros
  });

  if (respuesta.ok) {
    const productos = respuesta.datos.productos.map(convertirPreciosANumero);
    const { paginacion } = respuesta.datos;

    return { ...respuesta, datos: { productos, paginacion } };
  }

  return respuesta;
}

export type CamposOrdenamiento =
  | 'codigo'
  | 'nombre'
  | 'precio_por_mayor'
  | 'consumidor_final'
  | 'created_at'
  | 'updated_at'
  | 'deleted_at';

type RespuestasProductos<DatosEstado200> =
  | RespuestaValidacion<ParametrosObtenerTodos>
  | Respuesta<true, 200, DatosEstado200>
  | RespuestaMensajeError
  | RespuestasComunesApi
  | RespuestasComunesApiSinToken;

// Respuesta del servidor
type RespuestaProductosServidor = RespuestasProductos<DatosGetProductos>;

interface DatosGetProductos {
  productos: Array<ProductoServidor>;
  paginacion: Paginacion;
}

interface ProductoServidor extends ProductoBase {
  precio_por_mayor: string;
  consumidor_final: string;
}

// Respuesta del cliente
export type RespuestaProductos = RespuestasProductos<DatosProductos>;

interface DatosProductos {
  productos: Array<Producto>;
  paginacion: Paginacion;
}

/**
 * Debería obtener un producto
 */
export async function getProducto(id: number): Promise<RespuestaProducto> {
  const respuesta = await clienteApi<RespuestaProductoServidor>({
    url: `productos/${id}`,
    metodo: 'GET'
  });

  if (respuesta.ok) {
    const producto = convertirPreciosANumero(respuesta.datos.producto);
    return { ...respuesta, datos: { producto } };
  }

  return respuesta;
}

type RespuestasProducto<DatosEstado200> =
  | Respuesta<true, 200, DatosEstado200>
  | RespuestaMensajeError
  | RespuestasComunesApi
  | RespuestasComunesApiSinToken;

// Respuesta del servidor
type RespuestaProductoServidor = RespuestasProducto<DatosGetProducto>;

interface DatosGetProducto {
  producto: ProductoServidor;
}

// Respuesta del cliente
export type RespuestaProducto = RespuestasProducto<{ producto: Producto }>;

/**
 * Convierte los precios del producto de string a número
 *
 * @param producto
 */
export function convertirPreciosANumero(producto: ProductoServidor): Producto {
  const consumidor_final: number = Number(producto.consumidor_final);
  const precio_por_mayor: number = Number(producto.precio_por_mayor);

  return { ...producto, consumidor_final, precio_por_mayor };
}

export default {
  getProductos,
  getProducto
};
