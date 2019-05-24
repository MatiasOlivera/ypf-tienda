export interface Notificacion {
  tipo: TipoNotificacion;
  descripcion: string;
}

export type TipoNotificacion = 'exito' | 'error';
