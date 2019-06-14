import { ID, Modelo } from './tipos-modelo';

interface ModeloUsuario {
  name: string;
  email: string;
  id_cliente: ID;
}

export type Usuario = Modelo & ModeloUsuario;
