import {
  Cliente,
  Direccion,
  Mail,
  Telefono,
  RazonSocial,
  StateCliente
} from '../../../types/cliente';

export default {
  getClientes(state: StateCliente): any {
    return state.clientes;
  },

  getPerfilCliente(state: StateCliente): any {
    return state.perfil;
  }
};
