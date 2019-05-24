import { Notificacion } from '@/types/tipos-notificacion';

export const notificaciones: Notificacion[] = [
  {
    tipo: 'exito',
    descripcion: 'El producto se ha guardado'
  },
  {
    tipo: 'exito',
    descripcion: 'El producto se ha actualizado'
  },
  {
    tipo: 'error',
    descripcion: 'El producto no se ha eliminado'
  },
  {
    tipo: 'exito',
    descripcion: 'El producto se ha eliminado'
  },
  {
    tipo: 'exito',
    descripcion: 'El producto se ha restaurado'
  }
];

export const notificacion: Notificacion = notificaciones[0];
