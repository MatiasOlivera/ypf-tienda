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
import { paginacionPorDefecto } from '@/store/defaults/paginacion-por-defecto';
import usarParametros, {
  EstadoParametros,
  SET_CARGANDO
} from '@/store/mixins/parametros';
import { MensajeError } from '@/types/mensaje-tipos';

interface EstadoProductos extends EstadoParametros<ParametrosGetProductos> {
  productos: Array<Producto>;
  paginacion: Paginacion;
  validacion: ValidacionObtenerTodos;
  mensaje: MensajeError | null;
}

// Acciones
const RESETEAR_PRODUCTOS = 'resetearProductos';
const RESETEAR_PAGINACION = 'resetearPaginacion';
const RESETEAR_VALIDACION = 'resetearValidacion';
const RESETEAR_MENSAJE = 'resetearMensaje';

// Mutaciones
const SET_PRODUCTOS = 'setProductos';
const SET_PAGINACION = 'setPaginacion';
const SET_VALIDACION = 'setValidacion';
const SET_MENSAJE = 'setMensaje';

const parametros = usarParametros(OBTENER_PRODUCTOS);

const moduloProductos: Module<EstadoProductos, EstadoBase> = {
  namespaced: true,

  state: {
    cargando: false,
    parametros: {},
    productos: [],
    paginacion: paginacionPorDefecto,
    validacion: {},
    mensaje: null
  },

  actions: {
    ...parametros.actions,

    async [OBTENER_PRODUCTOS]({
      commit,
      dispatch,
      state
    }): Promise<RespuestaProductos> {
      try {
        commit(SET_CARGANDO, true);

        const respuesta = await getProductos(state.parametros);
        if (respuesta.ok) {
          commit(SET_PRODUCTOS, respuesta.datos.productos);
          commit(SET_PAGINACION, respuesta.datos.paginacion);

          // Resetear el estado de las validaciones y el mensaje
          dispatch(RESETEAR_VALIDACION);
          dispatch(RESETEAR_MENSAJE);
        } else {
          switch (respuesta.estado) {
            // Validación
            case 422:
              commit(SET_VALIDACION, respuesta.datos.errores);

              // Resetear el estado del mensaje
              dispatch(RESETEAR_MENSAJE);
              break;

            // Mensaje de error
            case 500:
              if (respuesta.datos && respuesta.datos.mensaje) {
                commit(SET_MENSAJE, respuesta.datos.mensaje);

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
        throw error;
      } finally {
        commit(SET_CARGANDO, false);
      }
    },

    [RESETEAR_PRODUCTOS]({ commit, state }): void {
      if (!isEqual(state.productos, [])) {
        commit(SET_PRODUCTOS, []);
      }
    },

    [RESETEAR_PAGINACION]({ commit, state }): void {
      if (!isEqual(state.paginacion, paginacionPorDefecto)) {
        commit(SET_PAGINACION, paginacionPorDefecto);
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

    [SET_PRODUCTOS](estado, productos: Array<Producto>): void {
      estado.productos = productos;
    },

    [SET_PAGINACION](estado, paginacion: Paginacion): void {
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
