/* eslint-disable camelcase */

import { clienteApi } from '@/services/cliente-api';
import { MensajeError } from '@/types/mensaje-tipos';
import { RespuestasComunesApi } from '@/types/respuesta-tipos';
import { ProductoCliente } from '@/types/tipos-producto';
import { RespuestasProducto } from './productos-api';

export type RespuestaProductoFavorito =
  | RespuestasProducto<ProductoCliente>
  | RespuestasComunesApi;

/**
 * Guardar el producto como favorito
 */
export function agregarProductoAFavoritos(
  id: number
): Promise<RespuestaProductoFavorito> {
  try {
    return clienteApi<RespuestaProductoFavorito>({
      url: `productos/${id}/favorito`,
      metodo: 'POST'
    });
  } catch (error) {
    const mensaje: MensajeError = {
      tipo: 'error',
      codigo: 'NO_ASOCIADOS',
      descripcion: 'No se puedo guardar el producto como favorito'
    };
    throw mensaje;
  }
}

/**
 * Quitar el producto de la lista de favoritos
 */
export function quitarProductoDeFavoritos(
  id: number
): Promise<RespuestaProductoFavorito> {
  try {
    return clienteApi<RespuestaProductoFavorito>({
      url: `productos/${id}/favorito`,
      metodo: 'DELETE'
    });
  } catch (error) {
    const mensaje: MensajeError = {
      tipo: 'error',
      codigo: 'NO_DESASOCIADOS',
      descripcion: 'No se puedo quitar el producto de la lista de favoritos'
    };
    throw mensaje;
  }
}
