import { rutaInicio } from '@/router/rutas';

import router from '../router';
import { RespuestaToken } from './api/types/token-tipos';
import { Cabeceras, ClienteHttp, Metodo, Respuesta } from './cliente-http';
import { ServicioToken } from './token-servicio';

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

type RespuestaNoAutorizado = Respuesta<false, 401, null>;

export async function clienteApi<RespuestaApi extends Respuesta>(
  opciones: Opciones,
  _renovarToken: any = renovarToken
): Promise<RespuestaApi | RespuestaNoAutorizado> {
  const { url, datos, metodo, cabeceras } = getOpciones(opciones);

  const urlBase: string = process.env.VUE_APP_API_ENDPOINT;
  const clienteHttp = new ClienteHttp(urlBase);

  // Servicio token
  const servicioToken = new ServicioToken();
  let token = servicioToken.getToken();

  const renovacion = servicioToken.esPosibleRenovarToken();

  if (renovacion === 'RENOVAR') {
    try {
      token = await _renovarToken();
    } catch (error) {
      throw error;
    }
  }

  if (renovacion === 'LOGIN' || !token) {
    router.push({ name: rutaInicio });

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

    const servicioToken = new ServicioToken();
    const token = servicioToken.getToken() as string;
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

      const servicioToken = new ServicioToken();
      servicioToken.setToken(tipoToken, _token);
      servicioToken.setFechaExpiracion(fechaExpiracion);

      return servicioToken.getToken();
    }

    return null;
  } catch (error) {
    throw error;
  }
}

export async function clienteApiSinToken<RespuestaApi extends Respuesta>(
  opciones: Opciones
): Promise<RespuestaApi> {
  const { url, datos, metodo, cabeceras } = getOpciones(opciones);

  const urlBase: string = process.env.VUE_APP_API_ENDPOINT;
  const clienteHttp = new ClienteHttp(urlBase);

  const config = { url, metodo, cabeceras, datos };
  return clienteHttp.peticion<RespuestaApi>(config);
}

export default clienteApi;
