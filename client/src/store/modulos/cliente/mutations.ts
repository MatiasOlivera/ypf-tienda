import {
  borrarObjetoEnArrayPorId,
  remplazarObjetoEnArrayPorId,
  agregarElementoAlArray
} from '../../../mixins/utilidades';
import {
  Cliente,
  Direccion,
  Mail,
  Telefono,
  RazonSocial,
  StateCliente
} from '../../../types/cliente';

export default {
  //Clientes
  setClientes(state: StateCliente, clientes: Array<Cliente>): void {
    state.clientes = { ...clientes };
  },

  modificarClientePorId(state: StateCliente, cliente: Cliente) {
    let lista = state.clientes;
    state.clientes = remplazarObjetoEnArrayPorId(lista, cliente);
  },

  borrarClientePorId(state: StateCliente, cliente: Cliente): void {
    let lista = state.clientes;
    state.clientes = borrarObjetoEnArrayPorId(lista, cliente);
  },

  limpiarClientes(state: StateCliente): void {
    state.clientes = [];
  },

  //perfil cliente
  setCliente(state: StateCliente, cliente: Cliente): void {
    state.perfil.cliente = { ...cliente };
  },

  limpiarCliente(state: StateCliente): void {
    state.perfil.cliente = {};
  },

  //Direcciones
  setDirecciones(state: StateCliente, direcciones: Array<Direccion>): void {
    state.perfil.direcciones = { ...direcciones };
  },

  agregarDireccion(state: StateCliente, direccion: Direccion): void {
    let direcciones = state.perfil.direcciones;
    state.perfil.direcciones = agregarElementoAlArray(direcciones, direccion);
  },

  modificarDireccion(state: StateCliente, direccion: Direccion): void {
    let direcciones = state.perfil.direcciones;
    state.perfil.direcciones = remplazarObjetoEnArrayPorId(
      direcciones,
      direccion
    );
  },

  borrarDireccion(state: StateCliente, direccion: Direccion): void {
    let direcciones = state.perfil.direcciones;
    state.perfil.direcciones = borrarObjetoEnArrayPorId(direcciones, direccion);
  },

  limpiarDirecciones(state: StateCliente): void {
    state.perfil.direcciones = [];
  },

  //Telefonos
  setTelefonos(state: StateCliente, telefonos: Array<Telefono>): void {
    state.perfil.telefonos = { ...telefonos };
  },

  agregarTelefono(state: StateCliente, telefono: Telefono): void {
    let telefonos = state.perfil.telefonos;
    state.perfil.telefonos = agregarElementoAlArray(telefonos, telefono);
  },

  modificarTelefono(state: StateCliente, telefono: Telefono): void {
    let telefonos = state.perfil.telefonos;
    state.perfil.direcciones = remplazarObjetoEnArrayPorId(telefonos, telefono);
  },

  borrarTelefono(state: StateCliente, telefono: Telefono): void {
    let telefonos = state.perfil.telefonos;
    state.perfil.direcciones = borrarObjetoEnArrayPorId(telefonos, telefono);
  },

  limpiarTelefonos(state: StateCliente): void {
    state.perfil.telefonos = [];
  },

  //Mails
  setMails(state: StateCliente, mails: Array<Mail>): void {
    state.perfil.mails = { ...mails };
  },

  agregarMail(state: StateCliente, mail: Mail): void {
    let mails = state.perfil.mails;
    state.perfil.mails = agregarElementoAlArray(mails, mail);
  },

  modificarMail(state: StateCliente, mail: Mail): void {
    let mails = state.perfil.mails;
    state.perfil.mails = remplazarObjetoEnArrayPorId(mails, mail);
  },

  borrarMail(state: StateCliente, mail: Mail): void {
    let mails = state.perfil.mails;
    state.perfil.mails = borrarObjetoEnArrayPorId(mails, mail);
  },

  limpiarMails(state: StateCliente): void {
    state.perfil.mails = [];
  },

  //Razones
  setRazonesSociales(
    state: StateCliente,
    razonesSociales: Array<RazonSocial>
  ): void {
    state.perfil.razonesSociales = { ...razonesSociales };
  },

  agregarRazonSocial(state: StateCliente, razon: RazonSocial): void {
    let razones = state.perfil.razonesSociales;
    state.perfil.mails = agregarElementoAlArray(razones, razon);
  },

  modificarRazonSocial(state: StateCliente, razon: RazonSocial): void {
    let razones = state.perfil.razonesSociales;
    state.perfil.razonesSociales = remplazarObjetoEnArrayPorId(razones, razon);
  },

  borrarRazonSocial(state: StateCliente, razon: RazonSocial): void {
    let razones = state.perfil.razonesSociales;
    state.perfil.razonesSociales = borrarObjetoEnArrayPorId(razones, razon);
  },

  limpiarRazonesSociales(state: StateCliente): void {
    state.perfil.razonesSociales = [];
  }
};
