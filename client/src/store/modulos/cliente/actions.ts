import { Cliente } from '../../../types/cliente';
import { clienteApi, clienteApiSinToken } from '../../../services/cliente-api';

export default {
  limpiarPerfilCliente(commit: any) {
    commit('limpiarCliente');
    commit('limpiarTelefonos');
    commit('limpiarDirecciones');
    commit('limpiarMails');
    commit('limpiarRazonesSociales');
  },

  getClientes(commit: any): void {},

  guardarCliente(commit: any, cliente: Cliente) {
    commit('setCliente', cliente);
  }
};
