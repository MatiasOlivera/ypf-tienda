import { EstadoBase } from '@/store';
import { CREAR_NOTIFICACION } from '@/store/types/acciones';
import { MODULO_NOTIFICACIONES } from '@/store/types/modulos';
import { Notificacion } from '@/types/tipos-notificacion';
import { Store } from 'vuex';

export async function crearNotificacion(
  notificacion: Notificacion,
  _store: Store<EstadoBase>
): Promise<any> {
  const accion = `${MODULO_NOTIFICACIONES}/${CREAR_NOTIFICACION}`;
  return _store.dispatch(accion, notificacion);
}
