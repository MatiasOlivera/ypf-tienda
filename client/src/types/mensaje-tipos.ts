export interface Mensaje {
  tipo: 'exito' | 'error';
  codigo: string;
  descripcion: string;
}

export interface MensajeExito extends Mensaje {
  tipo: 'exito';
}

export interface MensajeError extends Mensaje {
  tipo: 'error';
}
