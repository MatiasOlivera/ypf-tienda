/* eslint-disable no-prototype-builtins */
import { Respuesta } from '@/services/cliente-http';
import { CREAR_NOTIFICACION } from '@/store/types/acciones';
import { MODULO_NOTIFICACIONES } from '@/store/types/modulos';
import { Dispatch } from 'vuex';

// eslint-disable-next-line import/prefer-default-export
export function crearNotificacion(
  dispatch: Dispatch,
  respuesta: Respuesta
): void {
  if (respuesta.datos.hasOwnProperty('mensaje')) {
    const { mensaje } = respuesta.datos;

    if (
      mensaje.hasOwnProperty('tipo') &&
      mensaje.hasOwnProperty('descripcion')
    ) {
      dispatch(
        `${MODULO_NOTIFICACIONES}/${CREAR_NOTIFICACION}`,
        respuesta.datos.mensaje,
        { root: true }
      );
    }
  }
}
