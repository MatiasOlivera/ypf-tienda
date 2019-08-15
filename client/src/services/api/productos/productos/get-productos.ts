import { MensajeError } from '@/types/mensaje-tipos';
import { convertirPreciosANumero } from '../transformar-producto';
import {
  ParametrosGetProductos,
  RespuestaProductosNoAutenticado,
  RespuestaProductosServidorNoAutenticado
} from './productos-tipos';
import { clienteApiSinToken } from '@/services/cliente-api';

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

export default getProductos;
