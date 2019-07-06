import {
  MODULO_NOTIFICACIONES,
  MODULO_AUTENTICACION,
  MODULO_PRODUCTOS
} from '../types/modulos';
import moduloNotificaciones from './notificaciones/modulo-notificaciones';
import moduloAutenticacion from './autenticacion';
import moduloProductos from './productos';

export default {
  [MODULO_NOTIFICACIONES]: moduloNotificaciones,
  [MODULO_AUTENTICACION]: moduloAutenticacion,
  [MODULO_PRODUCTOS]: moduloProductos
};
