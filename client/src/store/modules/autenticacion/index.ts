/* eslint-disable no-param-reassign */
import {
  getUsuario,
  login,
  logout,
  RespuestaLogin,
  RespuestaUsuario,
  RespuestaLogout
} from '@/services/api/auth-api';
import { EstadoBase } from '@/store/tipos-store';
import { LOGIN, LOGOUT, OBTENER_USUARIO } from '@/store/types/acciones';
import { NOMBRE_USUARIO } from '@/store/types/getters';
import { SET_USUARIO, SET_ESTA_LOGUEADO } from '@/store/types/mutaciones';
import { CredencialesUsuario } from '@/types/tipos-auth';
import { Usuario } from '@/types/usuario-tipos';
import { Module } from 'vuex';

import { crearNotificacion } from '../notificaciones/crear-notificacion';

interface EstadoAutenticacion {
  estaLogueado: boolean;
  usuario: Usuario | null;
}

const moduloAutenticacion: Module<EstadoAutenticacion, EstadoBase> = {
  namespaced: true,

  state: {
    estaLogueado: false,
    usuario: null
  },

  getters: {
    [NOMBRE_USUARIO]: (estado): string => {
      return estado.usuario ? estado.usuario.name : '';
    }
  },

  actions: {
    async [LOGIN](
      { dispatch, commit },
      credenciales: CredencialesUsuario
    ): Promise<RespuestaLogin> {
      try {
        const respuesta = await login(credenciales);
        if (respuesta.ok) {
          await dispatch(OBTENER_USUARIO);
          commit(SET_ESTA_LOGUEADO, true);
        }
        return respuesta;
      } catch (error) {
        throw error;
      }
    },

    async [OBTENER_USUARIO]({ commit }): Promise<RespuestaUsuario> {
      try {
        const respuesta = await getUsuario();
        if (respuesta.ok) {
          commit(SET_USUARIO, respuesta.datos.usuario);
        }
        return respuesta;
      } catch (error) {
        throw error;
      }
    },

    async [LOGOUT]({ commit, dispatch }): Promise<RespuestaLogout> {
      try {
        const respuesta = await logout();
        if (respuesta.ok) {
          commit(SET_USUARIO, null);
          commit(SET_ESTA_LOGUEADO, false);
        }

        crearNotificacion(dispatch, respuesta);

        return respuesta;
      } catch (error) {
        throw error;
      }
    }
  },

  mutations: {
    [SET_USUARIO](estado, usuario: Usuario | null): void {
      estado.usuario = usuario;
    },

    [SET_ESTA_LOGUEADO](estado, estaLogueado: boolean): void {
      estado.estaLogueado = estaLogueado;
    }
  }
};

export default moduloAutenticacion;
