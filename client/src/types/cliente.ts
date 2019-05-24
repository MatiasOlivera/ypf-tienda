export interface Cliente {
  id?: number;
  nombre: string;
  documento?: number;
  observacion?: string;
}

//TODO mover localidad a un archivo de tipados general
export interface Localidad {
  id: number;
  nombre: string;
  provincia_id: number;
}

export interface Direccion {
  id: number;
  cliente_id: number;
  localidad_id: number;
  calle: string;
  numero: number;
  aclaracion: string;
  localidad: Localidad;
}

export interface Mail {
  id: number;
  cliente_id: number;
  mail: string;
}

export interface Telefono {
  id: number;
  cliente_id: number;
  area: number;
  telefono: number;
  nombreContacto: string;
}

export interface RazonSocial {
  id: number;
  denominacion: string;
  cuit: string;
  localidad_id: number;
  calle: string;
  numero: number;
  area: number;
  telefono: number;
  mail: string;
  localidad: Localidad;
}

export interface StateCliente {
  perfil: {
    cliente?: Cliente | {};
    direcciones?: Array<Direccion> | [];
    telefonos?: Array<Telefono> | [];
    mails?: Array<Mail> | [];
    razonesSociales?: Array<RazonSocial> | [];
  };
  clientes?: Array<Cliente>;
}
