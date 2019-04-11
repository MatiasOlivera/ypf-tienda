import { Diccionario } from '@/types/utilidades';

export function respuestaFetchMock(
  datos: any,
  cabeceras: Diccionario<string> = { 'content-type': 'application/json' },
  status: number = 200
): Promise<Response> {
  const data = datos ? JSON.stringify(datos) : undefined;
  const statusText = status >= 200 && status < 300 ? 'OK' : 'Error';
  const respuesta = new Response(data, {
    status,
    statusText,
    headers: cabeceras
  });

  return Promise.resolve(respuesta);
}
