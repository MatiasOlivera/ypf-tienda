/* eslint-disable no-param-reassign */
import { Module } from 'vuex';
import { OmniEvent, StateValue } from 'xstate';
import Vue from 'vue';
import { EstadoBase } from '@/store/tipos-store';
import {
  AGREGAR_A_FAVORITOS,
  QUITAR_DE_FAVORITOS,
  ACTUALIZAR_PRODUCTO,
  ESTABLECER_FAVORITOS
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
import {
  maquinaProductoFavorito,
  EventoFavorito,
  ContextoFavorito
} from './maquina-favoritos';
import { TipoProductos, ProductoFavorito } from '@/types/tipos-producto';

const actualizarProducto = obtenerEspacioDeNombres([
  MODULO_PRODUCTOS,
  ACTUALIZAR_PRODUCTO
]);

interface EstadoProductosFavoritos {
  favoritos: Array<ProductoFavorito>;
}

// Acciones
const SET_FAVORITOS = 'setFavoritos';

// Mutaciones
const MAQUINA_EVENTO = 'maquinaEvento';

const moduloProductosFavoritos: Module<EstadoProductosFavoritos, EstadoBase> = {
  namespaced: true,

  state: {
    favoritos: []
  },

  getters: {
    obtenerFavoritos: (estado) => estado.favoritos
  },

  actions: {
    [ESTABLECER_FAVORITOS]({ commit }, productos: TipoProductos): void {
      const productosFavoritos: Array<ProductoFavorito> = productos.map(
        (producto) => {
          const contexto: ContextoFavorito =
            // eslint-disable-next-line no-prototype-builtins
            producto.hasOwnProperty('es_favorito')
              ? {
                  // @ts-ignore
                  esFavorito: producto.es_favorito
                }
              : { esFavorito: false };

          const maquina = maquinaProductoFavorito.withContext(contexto);
          const estadoActual = maquina.initialState.value;

          return { id: producto.id, valor: contexto.esFavorito, estadoActual };
        }
      );

      commit(SET_FAVORITOS, productosFavoritos);
    },

    async [AGREGAR_A_FAVORITOS](
      { commit, dispatch },
      favorito: ProductoFavorito
    ): Promise<RespuestaProductoFavorito | undefined> {
      try {
        const invertirFavorito: MaquinaEvento = {
          evento: 'INVERTIR_FAVORITO',
          favorito
        };
        commit(MAQUINA_EVENTO, invertirFavorito);

        const respuesta = await agregarProductoAFavoritos(favorito.id);

        if (respuesta.ok) {
          const { producto: productoServidor } = respuesta.datos;

          const invertido: MaquinaEvento = {
            evento: 'INVERTIDO',
            favorito: { ...favorito, valor: productoServidor.es_favorito }
          };
          commit(MAQUINA_EVENTO, invertido);

          dispatch(actualizarProducto, productoServidor, {
            root: true
          });
        } else {
          const noInvertido: MaquinaEvento = {
            evento: 'NO_INVERTIDO',
            favorito
          };
          commit(MAQUINA_EVENTO, noInvertido);
        }

        crearNotificacion(dispatch, respuesta);

        return respuesta;
      } catch (error) {
        throw error;
      }
    },

    async [QUITAR_DE_FAVORITOS](
      { commit, dispatch },
      favorito: ProductoFavorito
    ): Promise<RespuestaProductoFavorito | undefined> {
      try {
        const invertirFavorito: MaquinaEvento = {
          evento: 'INVERTIR_FAVORITO',
          favorito
        };
        commit(MAQUINA_EVENTO, invertirFavorito);

        const respuesta = await quitarProductoDeFavoritos(favorito.id);

        if (respuesta.ok) {
          const { producto: productoServidor } = respuesta.datos;

          const invertido: MaquinaEvento = {
            evento: 'INVERTIDO',
            favorito: { ...favorito, valor: productoServidor.es_favorito }
          };
          commit(MAQUINA_EVENTO, invertido);

          dispatch(actualizarProducto, productoServidor, { root: true });
        } else {
          const noInvertido: MaquinaEvento = {
            evento: 'NO_INVERTIDO',
            favorito
          };
          commit(MAQUINA_EVENTO, noInvertido);
        }

        crearNotificacion(dispatch, respuesta);

        return respuesta;
      } catch (error) {
        throw error;
      }
    }
  },

  mutations: {
    [MAQUINA_EVENTO](estado, argumentos: MaquinaEvento): void {
      const { favorito, evento } = argumentos;

      const estadoActual = maquinaProductoFavorito
        .withContext({ esFavorito: favorito.valor })
        .transition(favorito.estadoActual, evento);

      const indice: number = estado.favoritos.findIndex(
        (actual) => actual.id === favorito.id
      );

      const nuevoProducto: ProductoFavorito = {
        id: favorito.id,
        valor: favorito.valor,
        estadoActual: estadoActual.value
      };

      Vue.set(estado.favoritos, indice, nuevoProducto);
    },

    [SET_FAVORITOS](estado, productos: Array<ProductoFavorito>): void {
      estado.favoritos = productos;
    }
  }
};

interface MaquinaEvento {
  evento: OmniEvent<EventoFavorito>;
  favorito: ProductoFavorito;
}

export default moduloProductosFavoritos;
