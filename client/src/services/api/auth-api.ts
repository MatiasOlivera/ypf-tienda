import { MensajeError } from '../../types/mensaje-tipos';
import {
  RespuestaMensajeError,
  RespuestaMensajeExito,
  RespuestaValidacion,
  RespuestasComunesApi,
  RespuestasComunesApiSinToken
} from '../../types/respuesta-tipos';
import { CredencialesUsuario } from '../../types/tipos-auth';
import { RespuestaToken } from '../../types/token-tipos';
import { Usuario } from '../../types/usuario-tipos';
import { clienteApi, clienteApiSinToken } from '../cliente-api';
import { Respuesta } from '../cliente-http';
import { ServicioToken } from '../token-servicio';

type CredencialesInvalidas = Respuesta<false, 401, MensajeError>;
export type RespuestaLogin =
  | RespuestaValidacion<CredencialesUsuario>
  | RespuestaToken
  | CredencialesInvalidas
  | RespuestaMensajeError
  | RespuestasComunesApiSinToken;

export async function login(
  credenciales: CredencialesUsuario
): Promise<RespuestaLogin> {
  const respuesta = await clienteApiSinToken<RespuestaLogin>({
    url: 'auth/login',
    metodo: 'POST',
    datos: credenciales
  });

  if (respuesta.ok) {
    const { token, tipoToken, fechaExpiracion } = respuesta.datos.autenticacion;

    const servicioToken = new ServicioToken();
    servicioToken.setToken(tipoToken, token);
    servicioToken.setFechaExpiracion(fechaExpiracion);
  }

  return respuesta;
}

export type RespuestaUsuario =
  | Respuesta<true, 200, { usuario: Usuario }>
  | RespuestasComunesApi;

export function getUsuario(): Promise<RespuestaUsuario> {
  return clienteApi<RespuestaUsuario>({
    url: 'auth/usuario',
    metodo: 'GET'
  });
}

export type RespuestaLogout =
  | RespuestaMensajeExito
  | RespuestaMensajeError
  | RespuestasComunesApi;

export async function logout(): Promise<RespuestaLogout> {
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