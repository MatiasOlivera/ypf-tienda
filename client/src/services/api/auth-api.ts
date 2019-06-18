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
import { ServicioAutenticacion } from '../servicio-autenticacion';

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

    const autenticacion = new ServicioAutenticacion();
    autenticacion.setToken(tipoToken, token);
    autenticacion.setFechaExpiracion(fechaExpiracion);
  }

  return respuesta;
}

export type RespuestaUsuario =
  | Respuesta<true, 200, { usuario: Usuario }>
  | RespuestasComunesApi;

export async function getUsuario(): Promise<RespuestaUsuario> {
  const respuesta = await clienteApi<RespuestaUsuario>({
    url: 'auth/usuario',
    metodo: 'GET'
  });

  if (respuesta.ok) {
    const { usuario } = respuesta.datos;
    const autenticacion = new ServicioAutenticacion();
    autenticacion.setUsuario(usuario);
  }

  return respuesta;
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

  const autenticacion = new ServicioAutenticacion();
  autenticacion.limpiar();

  return respuesta;
}

export default {
  login,
  getUsuario,
  logout
};
