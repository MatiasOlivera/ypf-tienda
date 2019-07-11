/* eslint-disable no-param-reassign */
import {
  getProductos,
  RespuestaProductos,
  ParametrosGetProductos
} from '@/services/api/productos-api';
import { EstadoBase } from '@/store/tipos-store';
import { Producto } from '@/types/tipos-producto';
import { Module } from 'vuex';
import { OBTENER_PRODUCTOS } from '@/store/types/acciones';
import { Paginacion } from '@/types/respuesta-tipos';
import { paginacionPorDefecto } from '@/store/defaults/paginacion-por-defecto';
import usarParametros, {
  EstadoParametros,
  SET_CARGANDO
} from '@/store/mixins/parametros';

interface EstadoProductos extends EstadoParametros<ParametrosGetProductos> {
  productos: Array<Producto>;
  paginacion: Paginacion;
}

// Mutaciones
const SET_PRODUCTOS = 'setProductos';
const SET_PAGINACION = 'setPaginacion';

const parametros = usarParametros(OBTENER_PRODUCTOS);

const moduloProductos: Module<EstadoProductos, EstadoBase> = {
  namespaced: true,

  state: {
    cargando: false,
    parametros: {},
    productos: [],
    paginacion: paginacionPorDefecto
  },

  actions: {
    ...parametros.actions,

    async [OBTENER_PRODUCTOS]({ commit, state }): Promise<RespuestaProductos> {
      try {
        commit(SET_CARGANDO, true);

        const respuesta = await getProductos(state.parametros);
        if (respuesta.ok) {
          commit(SET_PRODUCTOS, respuesta.datos.productos);
          commit(SET_PAGINACION, respuesta.datos.paginacion);
        }

        commit(SET_CARGANDO, false);

        return respuesta;
      } catch (error) {
        throw error;
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
    }
  }
};

export default moduloProductos;
