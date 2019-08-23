/* eslint-disable no-param-reassign */
import isEmpty from 'lodash/isEmpty';
import {
  getProductos,
  getProductosAutenticado
} from '@/services/api/productos';
import { EstadoBase } from '@/store/tipos-store';
import {
  Producto,
  TipoProductos,
  ProductoConRelaciones,
  ProductoFavorito
} from '@/types/tipos-producto';
import { Module } from 'vuex';
import {
  OBTENER_PRODUCTOS,
  ACTUALIZAR_PRODUCTO,
  ESTABLECER_SOLO_FAVORITOS,
  ESTABLECER_FAVORITOS
} from '@/store/types/acciones';
import { Paginacion, ValidacionObtenerTodos } from '@/types/respuesta-tipos';
import usarParametros, {
  EstadoParametros,
  RESETEAR_PAGINA
} from '@/store/mixins/parametros';
import { MensajeError } from '@/types/mensaje-tipos';
import { maquinaProductos, Estado, Evento } from './maquina-productos';
import { OmniEvent } from 'xstate/lib/types';
import moduloProductosFavoritos from './favoritos';
import {
  MODULO_PRODUCTOS_FAVORITOS,
  obtenerEspacioDeNombres
} from '@/store/types/modulos';
import Vue from 'vue';
import {
  ParametrosGetProductosNoAutenticado,
  RespuestaProductosNoAutenticado,
  ParametrosGetProductosAutenticado,
  RespuestaProductosAutenticado,
  SoloFavoritos
} from '@/services/api/productos/productos/productos-tipos';

export type ParametrosObtenerProductos =
  | ParametrosGetProductosNoAutenticado
  | ParametrosGetProductosAutenticado;

type RespuestaGetProductos =
  | RespuestaProductosAutenticado
  | RespuestaProductosNoAutenticado;

export type RespuestaObtenerProductos = Promise<
  RespuestaGetProductos | undefined
>;

interface EstadoProductos extends EstadoParametros<ParametrosObtenerProductos> {
  estadoActual: Estado;
  productos: TipoProductos;
  paginacion: Paginacion | null;
  validacion: ValidacionObtenerTodos;
  mensaje: MensajeError | null;
}

const establecerFavoritos = obtenerEspacioDeNombres([
  MODULO_PRODUCTOS_FAVORITOS,
  ESTABLECER_FAVORITOS
]);

// Mutaciones
const SET_SOLO_FAVORITOS = 'setSoloFavoritos';
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
    },

    productosConRelaciones(estado, getters): Array<ProductoConRelaciones> {
      if (isEmpty(estado.productos)) {
        return [];
      }

      const favoritos: Array<ProductoFavorito> =
        getters['favoritos/obtenerFavoritos'];

      if (isEmpty(favoritos)) {
        return [];
      }

      return estado.productos.map((producto) => {
        const favorito = favoritos.find((actual) => actual.id === producto.id);

        if (!favorito) {
          return {
            ...producto,
            esFavorito: {
              id: producto.id,
              valor: false,
              estadoActual: 'noEsFavorito'
            }
          };
        }

        return {
          ...producto,
          esFavorito: { ...favorito }
        };
      });
    }
  },

  actions: {
    ...parametros.actions,

    [ESTABLECER_SOLO_FAVORITOS](
      { commit, dispatch },
      soloFavoritos: SoloFavoritos
    ): void {
      commit(SET_SOLO_FAVORITOS, soloFavoritos);
      dispatch(RESETEAR_PAGINA);
      dispatch(OBTENER_PRODUCTOS);
    },

    async [OBTENER_PRODUCTOS]({
      commit,
      dispatch,
      getters,
      state,
      rootState
    }): RespuestaObtenerProductos {
      try {
        if (getters.estadoEsPendiente) {
          return;
        }

        commit(MAQUINA_EVENTO, 'OBTENER');

        let respuesta: RespuestaGetProductos;

        // @ts-ignore
        if (rootState.autenticacion.estaLogueado) {
          respuesta = await getProductosAutenticado(state.parametros);
        } else {
          // @ts-ignore
          respuesta = await getProductos(state.parametros);
        }
        if (respuesta.ok) {
          commit(MAQUINA_EVENTO, 'OBTUVO_PRODUCTOS');

          if (getters.estadoEsProductos) {
            const { productos, paginacion } = respuesta.datos;

            commit(SET_PRODUCTOS, productos);
            await dispatch(establecerFavoritos, productos);
            commit(SET_PAGINACION, paginacion);
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

    [SET_SOLO_FAVORITOS](estado, soloFavoritos: SoloFavoritos): void {
      // @ts-ignore
      estado.parametros.soloFavoritos = soloFavoritos;
    },

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

      if (indice >= 0) {
        Vue.set(estado.productos, indice, producto);
      }
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
