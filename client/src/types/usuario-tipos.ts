import { ID, Modelo } from './tipos-modelo';

interface ModeloUsuario {
  name: string;
  email: string;
  // eslint-disable-next-line camelcase
  id_cliente: ID;
}

// eslint-disable-next-line import/prefer-default-export
export type Usuario = Modelo & ModeloUsuario;
