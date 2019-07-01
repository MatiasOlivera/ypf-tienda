/* eslint-disable no-plusplus */
import { CREAR_NOTIFICACION } from '@/store/types/acciones';
import { MODULO_NOTIFICACIONES } from '@/store/types/modulos';
import {
  AGREGAR_NOTIFICACION,
  ELIMINAR_ULTIMA_NOTIFICACION
} from '@/store/types/mutaciones';
import { Notificacion } from '@/types/tipos-notificacion';
import Vue from 'vue';
import Vuex from 'vuex';

import { notificacion, notificaciones } from '../__mocks__/notificaciones.mock';
import moduloNotificaciones from '../modulo-notificaciones';

describe('Módulo notificaciones', () => {
  let vue: Vue;
  let getNotificaciones: () => Notificacion[];
  const agregarNotificacion = `${MODULO_NOTIFICACIONES}/${AGREGAR_NOTIFICACION}`;

  beforeEach(() => {
    Vue.use(Vuex);

    const store = new Vuex.Store({
      modules: { [MODULO_NOTIFICACIONES]: moduloNotificaciones }
    });

    vue = new Vue({ store });

    getNotificaciones = () => vue.$store.state.notificaciones.notificaciones;
  });

  afterEach(() => {
    const cantidad: number = vue.$store.getters['notificaciones/cantidad'];

    for (let i = 0; i < cantidad; i++) {
      vue.$store.commit(
        `${MODULO_NOTIFICACIONES}/${ELIMINAR_ULTIMA_NOTIFICACION}`
      );
    }
  });

  describe('state', () => {
    test('debería comprobar si el estado inicial es un array vacío', () => {
      const actual = vue.$store.state.notificaciones.notificaciones;
      expect(actual).toEqual([]);
    });
  });

  describe('getters', () => {
    test('debería obtener la cantidad de notificaciones', () => {
      vue.$store.commit(agregarNotificacion, notificaciones[0]);
      vue.$store.commit(agregarNotificacion, notificaciones[1]);

      const cantidad: number = vue.$store.getters['notificaciones/cantidad'];

      expect(cantidad).toBe(2);
    });

    test('debería obtener null cuando no haya ningún notificacion', () => {
      const ultima: Notificacion | null =
        vue.$store.getters['notificaciones/ultimaNotificacion'];

      expect(ultima).toBeNull();
    });

    test('debería obtener la última notificación', () => {
      vue.$store.commit(agregarNotificacion, notificaciones[0]);
      vue.$store.commit(agregarNotificacion, notificaciones[1]);

      const ultima: Notificacion =
        vue.$store.getters['notificaciones/ultimaNotificacion'];

      expect(ultima).toEqual(notificaciones[1]);
    });
  });

  describe('actions', () => {
    describe('crearNotificacion', () => {
      const crearNotificacion = `${MODULO_NOTIFICACIONES}/${CREAR_NOTIFICACION}`;

      test('debería agregar una notificación', () => {
        vue.$store.dispatch(crearNotificacion, notificacion);
        expect(getNotificaciones()).toEqual([notificacion]);
      });

      test('debería eliminar la última notificación cuando se exceda el límite', () => {
        for (let i = 0; i < 5; i++) {
          vue.$store.dispatch(crearNotificacion, notificaciones[0]);
        }

        expect(getNotificaciones()).toHaveLength(3);
      });
    });
  });

  describe('mutations', () => {
    test('agregarNotificacion: debería agregar una notificación a la cola', () => {
      vue.$store.commit(agregarNotificacion, notificacion);

      expect(getNotificaciones()).toEqual([notificacion]);
    });

    test('eliminarUltimaNotificacion: debería eliminar la última notificación', () => {
      const eliminarNotificacion = `${MODULO_NOTIFICACIONES}/${ELIMINAR_ULTIMA_NOTIFICACION}`;

      for (let i = 0; i < 3; i++) {
        vue.$store.commit(agregarNotificacion, notificaciones[0]);
      }

      vue.$store.commit(eliminarNotificacion);

      expect(getNotificaciones()).toHaveLength(2);
    });
  });
});
