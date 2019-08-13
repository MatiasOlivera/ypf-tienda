import { Module } from 'vuex';
import { EstadoBase } from '@/store/tipos-store';
import {
  AGREGAR_A_FAVORITOS,
  QUITAR_DE_FAVORITOS,
  ACTUALIZAR_PRODUCTO
} from '@/store/types/acciones';
import {
  agregarProductoAFavoritos,
  quitarProductoDeFavoritos,
  RespuestaProductoFavorito
} from '@/services/api/productos/productos-favoritos-api';
import {
  MODULO_PRODUCTOS,
  obtenerEspacioDeNombres
} from '@/store/types/modulos';
import { crearNotificacion } from '../../notificaciones/crear-notificacion';

const actualizarProducto = obtenerEspacioDeNombres([
  MODULO_PRODUCTOS,
  ACTUALIZAR_PRODUCTO
]);

type EstadoProductosFavoritos = null;

const moduloProductosFavoritos: Module<EstadoProductosFavoritos, EstadoBase> = {
  namespaced: true,

  actions: {
    async [AGREGAR_A_FAVORITOS](
      { dispatch },
      id: number
    ): Promise<RespuestaProductoFavorito> {
      try {
        const respuesta = await agregarProductoAFavoritos(id);

        if (respuesta.ok) {
          const { producto } = respuesta.datos;
          dispatch(actualizarProducto, producto, { root: true });
        }

        crearNotificacion(dispatch, respuesta);

        return respuesta;
      } catch (error) {
        throw error;
      }
    },

    async [QUITAR_DE_FAVORITOS](
      { dispatch },
      id: number
    ): Promise<RespuestaProductoFavorito> {
      try {
        const respuesta = await quitarProductoDeFavoritos(id);

        if (respuesta.ok) {
          const { producto } = respuesta.datos;
          dispatch(actualizarProducto, producto, { root: true });
        }

        crearNotificacion(dispatch, respuesta);

        return respuesta;
      } catch (error) {
        throw error;
      }
    }
  }
};

export default moduloProductosFavoritos;
