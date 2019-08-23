import { Machine, StateSchema, State } from 'xstate';

export const maquinaProductoFavorito = Machine<
  ContextoFavorito,
  EsquemaEstados,
  EventoFavorito
>(
  {
    id: 'producto-favorito',
    initial: 'desconocido',

    context: { esFavorito: false },

    states: {
      desconocido: {
        on: {
          '': [
            {
              cond: 'productoEsFavorito',
              target: 'esFavorito'
            },
            {
              target: 'noEsFavorito'
            }
          ]
        }
      },

      noEsFavorito: {
        on: { INVERTIR_FAVORITO: 'pendiente' }
      },

      pendiente: {
        on: {
          INVERTIDO: [
            {
              cond: 'productoEsFavorito',
              target: 'esFavorito'
            },
            {
              target: 'noEsFavorito'
            }
          ],

          NO_INVERTIDO: [
            {
              cond: 'productoEsFavorito',
              target: 'esFavorito'
            },
            {
              target: 'noEsFavorito'
            }
          ]
        }
      },

      esFavorito: {
        on: { INVERTIR_FAVORITO: 'pendiente' }
      }
    }
  },
  {
    guards: {
      productoEsFavorito: (contexto) => contexto.esFavorito === true
    }
  }
);

export interface ContextoFavorito {
  esFavorito: boolean;
}

interface EsquemaEstados extends StateSchema {
  states: {
    desconocido: {};
    noEsFavorito: {};
    pendiente: {};
    esFavorito: {};
  };
}

export type EventoFavorito =
  | { type: 'INVERTIR_FAVORITO' }
  | { type: 'INVERTIDO' }
  | { type: 'NO_INVERTIDO' };

export type EstadoFavorito = State<ContextoFavorito, EventoFavorito>;

export default maquinaProductoFavorito;
