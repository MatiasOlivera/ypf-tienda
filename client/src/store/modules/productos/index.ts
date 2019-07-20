/* eslint-disable no-param-reassign */
import isEqual from 'lodash/isEqual';
import {
  getProductos,
  RespuestaProductos,
  ParametrosGetProductos
} from '@/services/api/productos-api';
import { EstadoBase } from '@/store/tipos-store';
import { Producto } from '@/types/tipos-producto';
import { Module } from 'vuex';
import { OBTENER_PRODUCTOS } from '@/store/types/acciones';
import { Paginacion, ValidacionObtenerTodos } from '@/types/respuesta-tipos';
import usarParametros, { EstadoParametros } from '@/store/mixins/parametros';
import { MensajeError } from '@/types/mensaje-tipos';

type EstadoActual =
  | 'inicial'
  | 'cargando'
  | 'productos'
  | 'validacion'
  | 'mensaje';

interface EstadoProductos extends EstadoParametros<ParametrosGetProductos> {
  estadoActual: EstadoActual;
  productos: Array<Producto>;
  paginacion: Paginacion | null;
  validacion: ValidacionObtenerTodos;
  mensaje: MensajeError | null;
}

// Acciones
const RESETEAR_PRODUCTOS = 'resetearProductos';
const RESETEAR_PAGINACION = 'resetearPaginacion';
const RESETEAR_VALIDACION = 'resetearValidacion';
const RESETEAR_MENSAJE = 'resetearMensaje';

// Mutaciones
const SET_ESTADO_ACTUAL = 'setEstadoActual';
const SET_PRODUCTOS = 'setProductos';
const SET_PAGINACION = 'setPaginacion';
const SET_VALIDACION = 'setValidacion';
const SET_MENSAJE = 'setMensaje';

const parametros = usarParametros(OBTENER_PRODUCTOS);

const moduloProductos: Module<EstadoProductos, EstadoBase> = {
  namespaced: true,

  state: {
    estadoActual: 'inicial',
    parametros: {},
    productos: [],
    paginacion: null,
    validacion: {},
    mensaje: null
  },

  getters: {
    estadoEsInicial(estado): boolean {
      return estado.estadoActual === 'inicial';
    },

    estadoEsCargando(estado): boolean {
      return estado.estadoActual === 'cargando';
    },

    estadoEsProductos(estado): boolean {
      return estado.estadoActual === 'productos';
    },

    estadoEsValidacion(estado): boolean {
      return estado.estadoActual === 'validacion';
    },

    estadoEsMensaje(estado): boolean {
      return estado.estadoActual === 'mensaje';
    }
  },

  actions: {
    ...parametros.actions,

    async [OBTENER_PRODUCTOS]({
      commit,
      dispatch,
      state
    }): Promise<RespuestaProductos> {
      try {
        commit(SET_ESTADO_ACTUAL, 'cargando' as EstadoActual);

        const respuesta = await getProductos(state.parametros);
        if (respuesta.ok) {
          commit(SET_PRODUCTOS, respuesta.datos.productos);
          commit(SET_PAGINACION, respuesta.datos.paginacion);
          commit(SET_ESTADO_ACTUAL, 'productos' as EstadoActual);

          // Resetear el estado de las validaciones y el mensaje
          dispatch(RESETEAR_VALIDACION);
          dispatch(RESETEAR_MENSAJE);
        } else {
          switch (respuesta.estado) {
            // Validación
            case 422:
              commit(SET_VALIDACION, respuesta.datos.errores);
              commit(SET_ESTADO_ACTUAL, 'validacion' as EstadoActual);

              // Resetear el estado del mensaje
              dispatch(RESETEAR_MENSAJE);
              break;

            // Mensaje de error
            case 500:
              if (respuesta.datos && respuesta.datos.mensaje) {
                commit(SET_MENSAJE, respuesta.datos.mensaje);
                commit(SET_ESTADO_ACTUAL, 'mensaje' as EstadoActual);

                // Resetear el estado de productos, la paginación y las validaciones
                dispatch(RESETEAR_PRODUCTOS);
                dispatch(RESETEAR_PAGINACION);
                dispatch(RESETEAR_VALIDACION);
              }
              break;

            default:
          }
        }

        return respuesta;
      } catch (error) {
        commit(SET_MENSAJE, error as MensajeError);
        commit(SET_ESTADO_ACTUAL, 'mensaje' as EstadoActual);
        throw error;
      }
    },

    [RESETEAR_PRODUCTOS]({ commit, state }): void {
      if (!isEqual(state.productos, [])) {
        commit(SET_PRODUCTOS, []);
      }
    },

    [RESETEAR_PAGINACION]({ commit, state }): void {
      if (state.paginacion !== null) {
        commit(SET_PAGINACION, null);
      }
    },

    [RESETEAR_VALIDACION]({ commit, state }): void {
      if (!isEqual(state.validacion, {})) {
        commit(SET_VALIDACION, {});
      }
    },

    [RESETEAR_MENSAJE]({ commit, state }): void {
      if (state.mensaje !== null) {
        commit(SET_MENSAJE, null);
      }
    }
  },

  mutations: {
    ...parametros.mutations,

    [SET_ESTADO_ACTUAL](estado, estadoActual: EstadoActual): void {
      estado.estadoActual = estadoActual;
    },

    [SET_PRODUCTOS](estado, productos: Array<Producto>): void {
      estado.productos = productos;
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
  }
};

export default moduloProductos;
