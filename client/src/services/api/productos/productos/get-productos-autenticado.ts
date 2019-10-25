import { MensajeError } from '@/types/mensaje-tipos';
import {
  RespuestaProductosAutenticado,
  ParametrosGetProductosAutenticado
} from './productos-tipos';
import { clienteApi } from '@/services/cliente-api';

/**
 * Debería obtener un listado de productos
 */
export function getProductosAutenticado(
  parametros?: ParametrosGetProductosAutenticado
): Promise<RespuestaProductosAutenticado> {
  try {
    return clienteApi<RespuestaProductosAutenticado>({
      url: 'productos',
      metodo: 'GET',
      datos: parametros
    });
  } catch (error) {
    const mensaje: MensajeError = {
      tipo: 'error',
      codigo: 'NO_OBTENIDOS',
      descripcion: 'Hubo un error al consultar el listado de los productos'
    };
    throw mensaje;
  }
}

export default getProductosAutenticado;
