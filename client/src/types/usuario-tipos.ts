import { Modelo } from './tipos-modelo';

// eslint-disable-next-line import/prefer-default-export
export interface Usuario extends Modelo {
  name: string;
  email: string;
  // eslint-disable-next-line camelcase
  id_cliente: number;
}
