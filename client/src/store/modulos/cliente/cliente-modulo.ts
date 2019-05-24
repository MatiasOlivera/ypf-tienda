import mutations from './mutations';
import actions from './actions';
import getters from './getters';

import { StateCliente, Cliente } from '../../../types/cliente';

export default {
  state: <StateCliente>{
    perfil: {
      cliente: {
        nombre: 'Cirel',
        documento: 34380729,
        observacion: 'soy YO'
      },
      direcciones: {},
      telefonos: {},
      mails: {},
      razonesSociales: {}
    },
    clientes: {}
  },
  mutations,
  actions,
  getters
};
