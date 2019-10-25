import { clienteApiSinToken } from '@/services/cliente-api';
import { convertirPreciosANumero } from '../transformar-producto';
import { MensajeError } from '@/types/mensaje-tipos';
import {
  RespuestaProductoNoAutenticado,
  RespuestaProductoServidorNoAutenticado
} from './producto-tipo';

/**
 * Debería obtener un producto
 */
export default async function getProducto(
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
