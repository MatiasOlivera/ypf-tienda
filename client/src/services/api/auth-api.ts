import { clienteApi, clienteApiSinToken } from '../cliente-api';
import { Respuesta } from '../cliente-http';
import { ServicioToken } from '../token-servicio';
import { MensajeError } from './types/mensaje-tipos';
import { RespuestaMensajeError, RespuestaMensajeExito } from './types/respuesta-tipos';
import { RespuestaToken } from './types/token-tipos';
import { Usuario } from './types/usuario-tipos';

interface CredencialesUsuario {
  email: string;
  password: string;
}

export async function login(credenciales: CredencialesUsuario) {
  type CredencialesInvalidas = Respuesta<false, 401, MensajeError>;
  type RespuestaLogin =
    | RespuestaToken
    | CredencialesInvalidas
    | RespuestaMensajeError;

  const respuesta = await clienteApiSinToken<RespuestaLogin>({
    url: 'auth/login',
    metodo: 'POST',
    datos: credenciales
  });

  if (respuesta.ok) {
    const { token, tipoToken, fechaExpiracion } = respuesta.datos.autenticacion;

    const servicioToken = new ServicioToken();
    servicioToken.setToken(token, tipoToken);
    servicioToken.setFechaExpiracion(fechaExpiracion);
  }

  return respuesta;
}

export function getUsuario() {
  type RespuestaUsuario = Respuesta<true, 200, { usuario: Usuario }>;

  return clienteApi<RespuestaUsuario>({
    url: 'auth/usuario',
    metodo: 'GET'
  });
}

export async function logout() {
  type RespuestaLogout = RespuestaMensajeExito | RespuestaMensajeError;

  const respuesta = await clienteApi<RespuestaLogout>({
    url: 'auth/logout',
    metodo: 'POST'
  });

  const servicioToken = new ServicioToken();
  servicioToken.limpiar();

  return respuesta;
}

export default {
  login,
  getUsuario,
  logout
};
