import { rutaLogin } from '@/router/rutas';

import router from '../router';
import { RespuestaToken } from '../types/token-tipos';
import { Cabeceras, ClienteHttp, Metodo, Respuesta } from './cliente-http';
import { ServicioAutenticacion } from './servicio-autenticacion';
import {
  RespuestaNoAutorizado,
  RespuestasComunesApi,
  RespuestasComunesApiSinToken
} from '@/types/respuesta-tipos';

const cabecerasPorDefecto: Cabeceras = {
  accept: 'application/json',
  'content-type': 'application/json'
};

interface Opciones {
  url: string;
  metodo?: Metodo;
  datos?: any;
  cabeceras?: Cabeceras;
}

interface OpcionesInternas {
  url: string;
  metodo: Metodo;
  datos?: any;
  cabeceras: Cabeceras;
}

function getOpciones(opciones: Opciones): OpcionesInternas {
  const { url, datos } = opciones;
  const metodo = opciones.metodo || 'GET';
  const cabeceras: Cabeceras = {
    ...cabecerasPorDefecto,
    ...opciones.cabeceras
  };

  return {
    url,
    datos,
    metodo,
    cabeceras
  };
}

export async function clienteApi<RespuestaApi extends Respuesta>(
  opciones: Opciones,
  _renovarToken: any = renovarToken
): Promise<RespuestaApi | RespuestasComunesApi> {
  const { url, datos, metodo, cabeceras } = getOpciones(opciones);

  const urlBase: string = process.env.VUE_APP_API_ENDPOINT;
  const clienteHttp = new ClienteHttp(urlBase);

  // Servicio token
  const autenticacion = new ServicioAutenticacion();
  let token = autenticacion.getToken();

  const estado = autenticacion.getEstadoToken();

  if (estado === 'POSIBLE_RENOVAR') {
    try {
      token = await _renovarToken();
    } catch (error) {
      throw error;
    }
  }

  if (estado === 'EXPIRO' || !token) {
    router.push({ name: rutaLogin });

    const respuesta: RespuestaNoAutorizado = {
      ok: false,
      estado: 401,
      textoEstado: 'Unauthorized',
      datos: null
    };

    return respuesta;
  }

  cabeceras.authorization = token;

  // Peticion
  const config = { url, metodo, cabeceras, datos };
  return clienteHttp.peticion<RespuestaApi>(config);
}

async function renovarToken(): Promise<string | null> {
  try {
    const urlBase: string = process.env.VUE_APP_API_ENDPOINT;
    const clienteHttp = new ClienteHttp(urlBase);

    const autenticacion = new ServicioAutenticacion();
    const token = autenticacion.getToken() as string;
    const cabeceras = { ...cabecerasPorDefecto, authorization: token };

    const respuesta = await clienteHttp.peticion<RespuestaToken>({
      url: 'auth/renovar',
      metodo: 'POST',
      cabeceras
    });

    if (respuesta.ok) {
      const {
        token: _token,
        tipoToken,
        fechaExpiracion
      } = respuesta.datos.autenticacion;

      autenticacion.setToken(tipoToken, _token);
      autenticacion.setFechaExpiracion(fechaExpiracion);

      return autenticacion.getToken();
    }

    return null;
  } catch (error) {
    throw error;
  }
}

export async function clienteApiSinToken<RespuestaApi extends Respuesta>(
  opciones: Opciones
): Promise<RespuestaApi | RespuestasComunesApiSinToken> {
  const { url, datos, metodo, cabeceras } = getOpciones(opciones);

  const urlBase: string = process.env.VUE_APP_API_ENDPOINT;
  const clienteHttp = new ClienteHttp(urlBase);

  const config = {
    url,
    metodo,
    cabeceras,
    datos
  };

  return clienteHttp.peticion<RespuestaApi>(config);
}

export default clienteApi;
