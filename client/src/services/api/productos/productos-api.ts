/* eslint-disable camelcase */
import { Respuesta } from '../../cliente-http';
import {
  RespuestasComunesApiSinToken,
  Paginacion,
  ParametrosObtenerTodos,
  RespuestaValidacion,
  RespuestaMensajeError
} from '@/types/respuesta-tipos';
import { Producto, ProductoBase } from '@/types/tipos-producto';
import { clienteApiSinToken } from '../../cliente-api';
import { MensajeError } from '@/types/mensaje-tipos';

/**
 * Debería obtener un listado de productos
 */
export async function getProductos(
  parametros?: ParametrosGetProductos
): Promise<RespuestaProductosNoAutenticado> {
  try {
    const respuesta = await clienteApiSinToken<
      RespuestaProductosServidorNoAutenticado
    >({
      url: 'productos',
      metodo: 'GET',
      datos: parametros
    });

    if (respuesta.ok) {
      const productos = respuesta.datos.productos.map(convertirPreciosANumero);
      const { paginacion } = respuesta.datos;

      return {
        ...respuesta,
        datos: { productos, paginacion }
      };
    }

    return respuesta;
  } catch (error) {
    const mensaje: MensajeError = {
      tipo: 'error',
      codigo: 'NO_OBTENIDOS',
      descripcion: 'Hubo un error al consultar el listado de los productos'
    };
    throw mensaje;
  }
}

export type ParametrosGetProductos = ParametrosObtenerTodos<CamposOrdenamiento>;

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
  | RespuestaMensajeError;

// Respuesta del servidor
type RespuestaProductosServidor = RespuestasProductos<DatosGetProductos>;

type RespuestaProductosServidorNoAutenticado =
  | RespuestaProductosServidor
  | RespuestasComunesApiSinToken;

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

export type RespuestaProductosNoAutenticado =
  | RespuestaProductos
  | RespuestasComunesApiSinToken;

interface DatosProductos {
  productos: Array<Producto>;
  paginacion: Paginacion;
}

/**
 * Debería obtener un producto
 */
export async function getProducto(
  id: number
): Promise<RespuestaProductoNoAutenticado> {
  try {
    const respuesta = await clienteApiSinToken<
      RespuestaProductoServidorNoAutenticado
    >({
      url: `productos/${id}`,
      metodo: 'GET'
    });

    if (respuesta.ok) {
      const producto = convertirPreciosANumero(respuesta.datos.producto);
      return { ...respuesta, datos: { producto } };
    }

    return respuesta;
  } catch (error) {
    const mensaje: MensajeError = {
      tipo: 'error',
      codigo: 'NO_OBTENIDO',
      descripcion: 'Hubo un error al consultar los datos del producto'
    };
    throw mensaje;
  }
}

type RespuestasProducto<DatosEstado200> =
  | Respuesta<true, 200, DatosEstado200>
  | RespuestaMensajeError;

// Respuesta del servidor
type RespuestaProductoServidor = RespuestasProducto<DatosGetProducto>;

type RespuestaProductoServidorNoAutenticado =
  | RespuestaProductoServidor
  | RespuestasComunesApiSinToken;

interface DatosGetProducto {
  producto: ProductoServidor;
}

// Respuesta del cliente
export type RespuestaProducto = RespuestasProducto<{ producto: Producto }>;

type RespuestaProductoNoAutenticado =
  | RespuestaProducto
  | RespuestasComunesApiSinToken;

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
