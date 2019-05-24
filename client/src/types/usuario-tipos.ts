import { ID, Modelo } from './comun-tipos';

interface ModeloUsuario {
  name: string;
  email: string;
  id_cliente: ID;
}

export type Usuario = Modelo & ModeloUsuario;
