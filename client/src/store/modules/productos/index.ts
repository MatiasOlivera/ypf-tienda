/* eslint-disable no-param-reassign */
import {
  getProductos,
  RespuestaProductosNoAutenticado,
  ParametrosGetProductos
} from '@/services/api/productos/productos-api';
import { EstadoBase } from '@/store/tipos-store';
import { Producto } from '@/types/tipos-producto';
import { Module } from 'vuex';
import { OBTENER_PRODUCTOS, ACTUALIZAR_PRODUCTO } from '@/store/types/acciones';
import { Paginacion, ValidacionObtenerTodos } from '@/types/respuesta-tipos';
import usarParametros, { EstadoParametros } from '@/store/mixins/parametros';
import { MensajeError } from '@/types/mensaje-tipos';
import { maquinaProductos, Estado, Evento } from './maquina-productos';
import { OmniEvent } from 'xstate/lib/types';
import moduloProductosFavoritos from './favoritos';
import { MODULO_PRODUCTOS_FAVORITOS } from '@/store/types/modulos';
import Vue from 'vue';

interface EstadoProductos extends EstadoParametros<ParametrosGetProductos> {
  estadoActual: Estado;
  productos: Array<Producto>;
  paginacion: Paginacion | null;
  validacion: ValidacionObtenerTodos;
  mensaje: MensajeError | null;
}

// Mutaciones
const MAQUINA_EVENTO = 'maquinaEvento';
const SET_PRODUCTOS = 'setProductos';
const SET_PAGINACION = 'setPaginacion';
const SET_VALIDACION = 'setValidacion';
const SET_MENSAJE = 'setMensaje';
const SET_PRODUCTO = 'setProducto';

const parametros = usarParametros(OBTENER_PRODUCTOS);

const moduloProductos: Module<EstadoProductos, EstadoBase> = {
  namespaced: true,

  state: {
    estadoActual: maquinaProductos.initialState,
    parametros: {},
    productos: [],
    paginacion: null,
    validacion: {},
    mensaje: null
  },

  getters: {
    estadoEsInactivo(estado): boolean {
      return estado.estadoActual.matches('inactivo');
    },

    estadoEsPendiente(estado): boolean {
      return estado.estadoActual.matches('pendiente');
    },

    estadoEsProductos(estado): boolean {
      return estado.estadoActual.matches('productos');
    },

    estadoEsValidacion(estado): boolean {
      return estado.estadoActual.matches('validacion');
    },

    estadoEsMensaje(estado): boolean {
      return estado.estadoActual.matches('mensaje');
    }
  },

  actions: {
    ...parametros.actions,

    async [OBTENER_PRODUCTOS]({
      commit,
      getters,
      state
    }): Promise<RespuestaProductosNoAutenticado | undefined> {
      try {
        if (getters.estadoEsPendiente) {
          return;
        }

        commit(MAQUINA_EVENTO, 'OBTENER');

        const respuesta = await getProductos(state.parametros);
        if (respuesta.ok) {
          commit(MAQUINA_EVENTO, 'OBTUVO_PRODUCTOS');

          if (getters.estadoEsProductos) {
            commit(SET_PRODUCTOS, respuesta.datos.productos);
            commit(SET_PAGINACION, respuesta.datos.paginacion);
          }
        } else {
          switch (respuesta.estado) {
            // Validación
            case 422:
              commit(MAQUINA_EVENTO, 'OBTUVO_VALIDACION');

              if (getters.estadoEsValidacion) {
                commit(SET_VALIDACION, respuesta.datos.errores);
              }
              break;

            // Mensaje de error
            case 500:
              if (respuesta.datos && respuesta.datos.mensaje) {
                commit(MAQUINA_EVENTO, 'OBTUVO_MENSAJE');

                if (getters.estadoEsMensaje) {
                  commit(SET_MENSAJE, respuesta.datos.mensaje);
                }
              }
              break;

            default:
          }
        }

        // eslint-disable-next-line consistent-return
        return respuesta;
      } catch (error) {
        commit(MAQUINA_EVENTO, 'OBTUVO_MENSAJE');

        if (getters.estadoEsMensaje) {
          commit(SET_MENSAJE, error as MensajeError);
        }
        throw error;
      }
    },

    [ACTUALIZAR_PRODUCTO]({ commit }, producto: Producto): void {
      commit(SET_PRODUCTO, producto as Producto);
    }
  },

  mutations: {
    ...parametros.mutations,

    [MAQUINA_EVENTO](estado, evento: OmniEvent<Evento>): void {
      const { estadoActual } = estado;
      estado.estadoActual = maquinaProductos.transition(estadoActual, evento);
    },

    [SET_PRODUCTOS](estado, productos: Array<Producto>): void {
      estado.productos = productos;
    },

    [SET_PRODUCTO](estado, producto: Producto): void {
      const indice: number = estado.productos.findIndex(
        (productoActual) => productoActual.id === producto.id
      );

      Vue.set(estado.productos, indice, producto);
    },

    [SET_PAGINACION](estado, paginacion: Paginacion | null): void {
      estado.paginacion = paginacion;
    },

    [SET_VALIDACION](estado, validacion: ValidacionObtenerTodos): void {
      estado.validacion = validacion;
    },

    [SET_MENSAJE](estado, mensaje: MensajeError | null): void {
      estado.mensaje = mensaje;
    }
  },

  modules: {
    [MODULO_PRODUCTOS_FAVORITOS]: moduloProductosFavoritos
  }
};

export default moduloProductos;
