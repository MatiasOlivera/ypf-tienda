import { Diccionario } from '@/types/utilidades';

export class ClienteHttp {
  constructor(private urlBase?: string) {
    this.urlBase = urlBase;
  }

  public async peticion<RespuestaApi extends Respuesta>(
    config: Configuracion
  ): Promise<RespuestaApi> {
    const metodo: Metodo = config.metodo || 'GET';
    const urlInterna: string = this.getUrl(config.url, metodo, config.datos);
    const request = this.getOpciones(urlInterna, {
      ...config,
      metodo
    });

    return fetch(request)
      .then((respuesta: Response) => {
        // La promesa será resuelta si se ha podido realizar la petición,
        // sin importar cuál es el código de estado de la respuesta
        return (this.getRespuesta(respuesta) as unknown) as RespuestaApi;
      })
      .catch((error) => {
        // La promesa será rechazada si hubo un fallo de red
        // o si algo impidio completar la petición
        throw error;
      });
  }

  private getUrl(urlEspecifica: string, metodo: Metodo, datos?: any): string {
    const url = new URL(urlEspecifica, this.urlBase);

    if (metodo === 'GET' && datos) {
      const parametros = new URLSearchParams(datos);
      url.search = parametros.toString();
    }

    return url.toString();
  }

  private getOpciones(
    urlInterna: string,
    config: ConfiguracionInterna
  ): Request {
    const cabeceras = new Headers(config.cabeceras);

    const request = new Request(urlInterna, {
      method: config.metodo,
      headers: cabeceras,
      mode: 'cors',
      // Por defecto fetch solo envia y recibe cookies del servidor
      // del mismo origen
      credentials: 'include'
    });

    if ((config.metodo === 'POST' || config.metodo === 'PUT') && config.datos) {
      const datos = this.getCuerpo(request, config.datos);
      return { ...request, body: datos };
    }

    return request;
  }

  // eslint-disable-next-line class-methods-use-this
  private getCuerpo(peticion: Request, datos: any): any {
    if (peticion.headers.has('content-type')) {
      const tipoContenido = peticion.headers.get('content-type');

      if (tipoContenido) {
        if (tipoContenido.includes('application/json')) {
          return JSON.stringify(datos);
        }
      }
    }

    return datos;
  }

  // eslint-disable-next-line class-methods-use-this
  private async getRespuesta(respuesta: Response): Promise<Respuesta> {
    let datos = null;

    if (respuesta.headers.has('content-type')) {
      const tipoContenido = respuesta.headers.get('content-type');

      if (tipoContenido) {
        if (tipoContenido.includes('application/json')) {
          datos = await respuesta.json();
        }

        if (tipoContenido.includes('text/plain')) {
          datos = await respuesta.text();
        }
      }
    }

    return {
      ok: respuesta.ok,
      estado: respuesta.status,
      textoEstado: respuesta.statusText,
      datos
    };
  }
}

export interface Configuracion {
  url: string;
  cabeceras?: Cabeceras;
  metodo?: Metodo;
  datos?: any;
}

interface ConfiguracionInterna {
  metodo: Metodo;
  cabeceras?: Cabeceras;
  datos?: any;
}

export type Cabeceras = Diccionario<string>;

export type Metodo = 'GET' | 'POST' | 'PUT' | 'DELETE';

export interface Respuesta<
  Ok extends boolean = boolean,
  Estado extends number = number,
  Datos extends any = any
> {
  ok: Ok;
  estado: Estado;
  textoEstado: string;
  datos: Datos;
}

export default ClienteHttp;
