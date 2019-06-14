import { MODULO_NOTIFICACIONES, MODULO_AUTENTICACION } from '../types/modulos';
import moduloNotificaciones from './notificaciones/modulo-notificaciones';
import moduloAutenticacion from './autenticacion';

export default {
  [MODULO_NOTIFICACIONES]: moduloNotificaciones,
  [MODULO_AUTENTICACION]: moduloAutenticacion
};
