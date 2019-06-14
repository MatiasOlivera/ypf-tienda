import { Respuesta } from '@/services/cliente-http';
import { MODULO_NOTIFICACIONES } from '@/store/types/modulos';
import Vue from 'vue';
import Vuex from 'vuex';

import { notificacion } from '../__mocks__/notificaciones.mock';
import { crearNotificacion } from '../crear-notificacion';
import moduloNotificaciones from '../modulo-notificaciones';

describe('crearNotificacion', () => {
  test('debería crear una nueva notificación', () => {
    Vue.use(Vuex);

    const store = new Vuex.Store({
      modules: { [MODULO_NOTIFICACIONES]: moduloNotificaciones }
    });

    const vue = new Vue({ store });
    const respuesta: Respuesta = {
      ok: true,
      estado: 200,
      textoEstado: 'OK',
      datos: { mensaje: notificacion }
    };

    crearNotificacion(store.dispatch, respuesta);

    const actual = vue.$store.state.notificaciones.notificaciones;
    const esperado = [notificacion];

    expect(actual).toEqual(esperado);
  });
});
