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

interface EstadoProductos {
  productos: Array<Producto>;
  paginacion: Paginacion;
}

// Mutaciones
const SET_PRODUCTOS = 'setProductos';
const SET_PAGINACION = 'setPaginacion';

const moduloProductos: Module<EstadoProductos, EstadoBase> = {
  namespaced: true,

  state: {
    productos: [],
    paginacion: paginacionPorDefecto
  },

  actions: {
    async [OBTENER_PRODUCTOS](
      { commit },
      parametros?: ParametrosGetProductos
    ): Promise<RespuestaProductos> {
      try {
        const respuesta = await getProductos(parametros);
        if (respuesta.ok) {
          commit(SET_PRODUCTOS, respuesta.datos.productos);
          commit(SET_PAGINACION, respuesta.datos.paginacion);
        }
        return respuesta;
      } catch (error) {
        throw error;
      }
    }
  },

  mutations: {
    [SET_PRODUCTOS](estado, productos: Array<Producto>): void {
      estado.productos = productos;
    },

    [SET_PAGINACION](estado, paginacion: Paginacion): void {
      estado.paginacion = paginacion;
    }
  }
};

export default moduloProductos;
