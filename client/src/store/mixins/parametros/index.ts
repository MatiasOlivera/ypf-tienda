/* eslint-disable no-param-reassign */
import { Module } from 'vuex';
import {
  ParametrosObtenerTodos,
  Buscar,
  Eliminados,
  Pagina,
  PorPagina,
  DireccionOrden
} from '@/types/respuesta-tipos';
import { EstadoBase } from '../../tipos-store';
import {
  ESTABLECER_BUSCAR,
  ESTABLECER_ELIMINADOS,
  ESTABLECER_PAGINA,
  ESTABLECER_POR_PAGINA,
  ESTABLECER_ORDENAR_POR,
  ESTABLECER_ORDEN
} from '../../types/acciones';

export interface EstadoParametros<
  Parametros extends ParametrosObtenerTodos = ParametrosObtenerTodos
> {
  cargando: boolean;
  parametros: Parametros;
}

// Mutaciones
export const SET_CARGANDO = 'setCargando';
const SET_BUSCAR = 'setBuscar';
const SET_ELIMINADOS = 'setEliminados';
const SET_PAGINA = 'setPagina';
const SET_POR_PAGINA = 'setPorPagina';
const SET_ORDENAR_POR = 'setOrdenarPor';
const SET_ORDEN = 'setOrden';

type ModuloParametros = Module<EstadoParametros, EstadoBase>;

function usarParametros(accionObtenerTodos: string): ModuloParametros {
  return {
    state: {
      cargando: false,
      parametros: {}
    },

    actions: {
      [ESTABLECER_BUSCAR]({ commit, dispatch }, buscar?: Buscar): void {
        commit(SET_BUSCAR, buscar);
        dispatch(accionObtenerTodos);
      },

      [ESTABLECER_ELIMINADOS](
        { commit, dispatch },
        eliminados?: Eliminados
      ): void {
        commit(SET_ELIMINADOS, eliminados);
        dispatch(accionObtenerTodos);
      },

      [ESTABLECER_PAGINA]({ commit, dispatch }, pagina?: Pagina): void {
        commit(SET_PAGINA, pagina);
        dispatch(accionObtenerTodos);
      },

      [ESTABLECER_POR_PAGINA](
        { commit, dispatch },
        porPagina?: PorPagina
      ): void {
        commit(SET_POR_PAGINA, porPagina);
        dispatch(accionObtenerTodos);
      },

      [ESTABLECER_ORDENAR_POR](
        { commit, dispatch },
        ordenarPor?: string
      ): void {
        commit(SET_ORDENAR_POR, ordenarPor);
        dispatch(accionObtenerTodos);
      },

      [ESTABLECER_ORDEN]({ commit, dispatch }, orden?: DireccionOrden): void {
        commit(SET_ORDEN, orden);
        dispatch(accionObtenerTodos);
      }
    },

    mutations: {
      [SET_CARGANDO](estado, cargando: boolean): void {
        estado.cargando = cargando;
      },

      [SET_BUSCAR](estado, buscar: Buscar): void {
        estado.parametros.buscar = buscar;
      },

      [SET_ELIMINADOS](estado, eliminados: Eliminados): void {
        estado.parametros.eliminados = eliminados;
      },

      [SET_PAGINA](estado, pagina: Pagina): void {
        estado.parametros.pagina = pagina;
      },

      [SET_POR_PAGINA](estado, porPagina: PorPagina): void {
        estado.parametros.porPagina = porPagina;
      },

      [SET_ORDENAR_POR](estado, ordenarPor: string): void {
        estado.parametros.ordenarPor = ordenarPor;
      },

      [SET_ORDEN](estado, orden: DireccionOrden): void {
        estado.parametros.orden = orden;
      }
    }
  };
}

export default usarParametros;