import { Machine, StateSchema, State } from 'xstate';

export const maquinaProductos = Machine<Contexto, EsquemaEstados, Evento>({
  id: 'productos',
  initial: 'inactivo',

  states: {
    inactivo: {
      on: { OBTENER: 'pendiente' }
    },

    pendiente: {
      on: {
        OBTUVO_PRODUCTOS: 'productos',
        OBTUVO_VALIDACION: 'validacion',
        OBTUVO_MENSAJE: 'mensaje'
      }
    },

    productos: {
      on: { OBTENER: 'pendiente' }
    },

    validacion: {
      on: { OBTENER: 'pendiente' }
    },

    mensaje: {
      on: { OBTENER: 'pendiente' }
    }
  }
});

interface Contexto {}

interface EsquemaEstados extends StateSchema {
  states: {
    inactivo: {};
    pendiente: {};
    productos: {};
    validacion: {};
    mensaje: {};
  };
}

export type Evento =
  | { type: 'OBTENER' }
  | { type: 'OBTUVO_PRODUCTOS' }
  | { type: 'OBTUVO_VALIDACION' }
  | { type: 'OBTUVO_MENSAJE' }
  | { type: 'CAMBIAR_PARAMETRO' };

export type Estado = State<Contexto, Evento>;

export default maquinaProductos;
