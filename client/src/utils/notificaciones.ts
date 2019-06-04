import iziToast, { IziToastSettings } from 'izitoast';
import { Notificacion } from '@/types/tipos-notificacion';

// eslint-disable-next-line import/prefer-default-export
export function mostrarNotificacion(notificacion: Notificacion) {
  const { tipo, descripcion } = notificacion;

  const configuracion: IziToastSettings = { message: descripcion };

  switch (tipo) {
    case 'exito':
      return iziToast.success(configuracion);

    case 'error':
      return iziToast.error(configuracion);

    case 'info':
      return iziToast.info(configuracion);

    case 'advertencia':
      return iziToast.warning(configuracion);

    case 'pregunta':
      return iziToast.question(configuracion);

    default:
      return iziToast.info(configuracion);
  }
}
