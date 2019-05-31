import { EstadoBase } from '@/store/tipos-store';
import { Notificacion } from '@/types/tipos-notificacion';
import { Module } from 'vuex';

import { CREAR_NOTIFICACION } from '../../types/acciones';
import {
  AGREGAR_NOTIFICACION,
  ELIMINAR_ULTIMA_NOTIFICACION
} from '../../types/mutaciones';

interface EstadoNotificaciones {
  notificaciones: Notificacion[];
}

export const state: EstadoNotificaciones = {
  notificaciones: []
};

const moduloNotificaciones: Module<EstadoNotificaciones, EstadoBase> = {
  namespaced: true,

  state,

  getters: {
    cantidad: (estado): number => estado.notificaciones.length,

    ultimaNotificacion: (estado, { cantidad }): Notificacion | null => {
      return cantidad !== 0 ? estado.notificaciones[cantidad - 1] : null;
    }
  },

  actions: {
    [CREAR_NOTIFICACION](contexto, notificacion: Notificacion): void {
      contexto.commit(AGREGAR_NOTIFICACION, notificacion);

      if (contexto.getters.cantidad > 3) {
        contexto.commit(ELIMINAR_ULTIMA_NOTIFICACION);
      }
    }
  },

  mutations: {
    [AGREGAR_NOTIFICACION](estado, notificacion: Notificacion): void {
      estado.notificaciones.push(notificacion);
    },

    [ELIMINAR_ULTIMA_NOTIFICACION](estado): void {
      estado.notificaciones.shift();
    }
  }
};

export default moduloNotificaciones;
