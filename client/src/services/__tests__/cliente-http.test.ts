import { Diccionario } from '@/types/utilidades';

import { producto, productos } from '../__mocks__/productos.mock';
import { ClienteHttp } from '../cliente-http';

const fetch = require('whatwg-fetch');

function mockFetch(
  datos: any,
  cabeceras: Diccionario<string> = { 'content-type': 'application/json' },
  status: number = 200
) {
  return jest.fn(async () => {
    const data = datos ? JSON.stringify(datos) : undefined;
    const statusText = status >= 200 && status < 300 ? 'OK' : 'Error';

    return new Response(data, {
      status,
      statusText,
      headers: cabeceras
    });
  });
}

function mockFetchError(error: string) {
  return jest.fn(async () => {
    throw new Error(error);
  });
}

const baseUrl: string = 'https://servidor.com/api/';

const cabeceras: Diccionario<string> = {
  accept: 'application/json',
  'content-type': 'application/json'
};

describe('Cliente HTTP', () => {
  test('debería obtener un listado de elementos usando el método GET', async () => {
    window.fetch = mockFetch(productos);

    const cliente = new ClienteHttp(baseUrl);
    const respuesta = await cliente.peticion({
      url: 'productos',
      datos: { paginacion: 10, ordenar_por: 'nombre', direccion: 'ASC' },
      cabeceras
    });

    expect(respuesta).toEqual({
      datos: productos,
      estado: 200,
      ok: true,
      textoEstado: 'OK'
    });
    expect(window.fetch).toHaveBeenCalledTimes(1);
    expect(window.fetch).toHaveBeenCalledWith(
      'https://servidor.com/api/productos?paginacion=10&ordenar_por=nombre&direccion=ASC',
      {
        _bodyInit: undefined,
        _bodyText: '',
        credentials: 'include',
        headers: {
          map: {
            accept: 'application/json',
            'content-type': 'application/json'
          }
        },
        method: 'GET',
        mode: 'cors',
        referrer: null,
        signal: undefined,
        url:
          'https://servidor.com/api/productos?paginacion=10&ordenar_por=nombre&direccion=ASC'
      }
    );
  });

  test('debería obtener un elemento usando el método GET', async () => {
    window.fetch = mockFetch(producto);

    const cliente = new ClienteHttp(baseUrl);
    const respuesta = await cliente.peticion({
      url: 'productos/1',
      cabeceras
    });

    expect(respuesta).toEqual({
      datos: producto,
      estado: 200,
      ok: true,
      textoEstado: 'OK'
    });
    expect(window.fetch).toHaveBeenCalledTimes(1);
    expect(window.fetch).toHaveBeenCalledWith(
      'https://servidor.com/api/productos/1',
      {
        _bodyInit: undefined,
        _bodyText: '',
        credentials: 'include',
        headers: {
          map: {
            accept: 'application/json',
            'content-type': 'application/json'
          }
        },
        method: 'GET',
        mode: 'cors',
        referrer: null,
        signal: undefined,
        url: 'https://servidor.com/api/productos/1'
      }
    );
  });

  test('debería crear un nuevo elemento usando el método POST', async () => {
    window.fetch = mockFetch(producto);

    const cliente = new ClienteHttp(baseUrl);
    const respuesta = await cliente.peticion({
      metodo: 'POST',
      url: 'productos',
      datos: producto,
      cabeceras
    });

    expect(respuesta).toEqual({
      datos: producto,
      estado: 200,
      ok: true,
      textoEstado: 'OK'
    });
    expect(window.fetch).toHaveBeenCalledTimes(1);
    expect(window.fetch).toHaveBeenCalledWith(
      'https://servidor.com/api/productos',
      {
        _bodyInit: undefined,
        _bodyText: '',
        body: '{"id":1,"nombre":"Notebook"}',
        credentials: 'include',
        headers: {
          map: {
            accept: 'application/json',
            'content-type': 'application/json'
          }
        },
        method: 'POST',
        mode: 'cors',
        referrer: null,
        signal: undefined,
        url: 'https://servidor.com/api/productos'
      }
    );
  });

  test('debería actualizar un elemento usando el método PUT', async () => {
    const productoActualizado = { id: 1, nombre: 'Ultrabook' };
    window.fetch = mockFetch(productoActualizado);

    const cliente = new ClienteHttp(baseUrl);
    const respuesta = await cliente.peticion({
      metodo: 'PUT',
      url: 'productos/1',
      datos: productoActualizado,
      cabeceras
    });

    expect(respuesta).toEqual({
      datos: productoActualizado,
      estado: 200,
      ok: true,
      textoEstado: 'OK'
    });
    expect(window.fetch).toHaveBeenCalledTimes(1);
    expect(window.fetch).toHaveBeenCalledWith(
      'https://servidor.com/api/productos/1',
      {
        _bodyInit: undefined,
        _bodyText: '',
        body: '{"id":1,"nombre":"Ultrabook"}',
        credentials: 'include',
        headers: {
          map: {
            accept: 'application/json',
            'content-type': 'application/json'
          }
        },
        method: 'PUT',
        mode: 'cors',
        referrer: null,
        signal: undefined,
        url: 'https://servidor.com/api/productos/1'
      }
    );
  });

  test('debería eliminar un elemento usando el método DELETE', async () => {
    window.fetch = mockFetch(producto);

    const cliente = new ClienteHttp(baseUrl);
    const respuesta = await cliente.peticion({
      metodo: 'DELETE',
      url: 'productos/1',
      cabeceras
    });

    expect(respuesta).toEqual({
      datos: producto,
      estado: 200,
      ok: true,
      textoEstado: 'OK'
    });
    expect(window.fetch).toHaveBeenCalledTimes(1);
    expect(window.fetch).toHaveBeenCalledWith(
      'https://servidor.com/api/productos/1',
      {
        _bodyInit: undefined,
        _bodyText: '',
        credentials: 'include',
        headers: {
          map: {
            accept: 'application/json',
            'content-type': 'application/json'
          }
        },
        method: 'DELETE',
        mode: 'cors',
        referrer: null,
        signal: undefined,
        url: 'https://servidor.com/api/productos/1'
      }
    );
  });

  test('debería hacer una petición cuando se le pase la URL completa', async () => {
    window.fetch = mockFetch(productos);

    const cliente = new ClienteHttp();
    const respuesta = await cliente.peticion({
      url: 'https://otro-servidor.com/api/v1/productos',
      cabeceras
    });

    expect(respuesta).toEqual({
      datos: productos,
      estado: 200,
      ok: true,
      textoEstado: 'OK'
    });
    expect(window.fetch).toHaveBeenCalledTimes(1);
    expect(window.fetch).toHaveBeenCalledWith(
      'https://otro-servidor.com/api/v1/productos',
      {
        _bodyInit: undefined,
        _bodyText: '',
        credentials: 'include',
        headers: {
          map: {
            accept: 'application/json',
            'content-type': 'application/json'
          }
        },
        method: 'GET',
        mode: 'cors',
        referrer: null,
        signal: undefined,
        url: 'https://otro-servidor.com/api/v1/productos'
      }
    );
  });

  test('debería hacer una petición a un URL con caracteres especiales', async () => {
    window.fetch = mockFetch(undefined, {});

    const cliente = new ClienteHttp(baseUrl);
    await cliente.peticion({
      url: 'ciguëñas',
      cabeceras
    });

    expect(window.fetch).toHaveBeenCalledTimes(1);
    expect(window.fetch).toHaveBeenCalledWith(
      'https://servidor.com/api/cigu%C3%AB%C3%B1as',
      {
        _bodyInit: undefined,
        _bodyText: '',
        credentials: 'include',
        headers: {
          map: {
            accept: 'application/json',
            'content-type': 'application/json'
          }
        },
        method: 'GET',
        mode: 'cors',
        referrer: null,
        signal: undefined,
        url: 'https://servidor.com/api/cigu%C3%AB%C3%B1as'
      }
    );
  });

  test('debería hacer una petición a un URL que no existe', async () => {
    window.fetch = mockFetch(undefined, {}, 404);

    const cliente = new ClienteHttp();
    const respuesta = await cliente.peticion({
      url: 'https://otro-servidor.com/api/v1/productos',
      cabeceras
    });

    expect(respuesta).toEqual({
      datos: null,
      estado: 404,
      ok: false,
      textoEstado: 'Error'
    });
  });

  test('debería hacer la petición incluso si no existen cabeceras', async () => {
    window.fetch = mockFetch('Test', {});

    const cliente = new ClienteHttp();
    const respuesta = await cliente.peticion({
      metodo: 'POST',
      url: 'https://otro-servidor.com/api/v1/test',
      cabeceras: {}
    });

    expect(respuesta).toEqual({
      datos: '"Test"',
      estado: 200,
      ok: true,
      textoEstado: 'OK'
    });
    expect(window.fetch).toHaveBeenCalledTimes(1);
    expect(window.fetch).toHaveBeenCalledWith(
      'https://otro-servidor.com/api/v1/test',
      {
        _bodyInit: undefined,
        _bodyText: '',
        credentials: 'include',
        headers: { map: {} },
        method: 'POST',
        mode: 'cors',
        referrer: null,
        signal: undefined,
        url: 'https://otro-servidor.com/api/v1/test'
      }
    );
  });

  test('debería envíar texto en la petición y recibir texto en la respuesta', async () => {
    window.fetch = mockFetch('Test', { 'content-type': 'text/plain' });

    const cliente = new ClienteHttp();
    const respuesta = await cliente.peticion({
      metodo: 'POST',
      url: 'https://otro-servidor.com/api/v1/test',
      cabeceras: { 'content-type': 'text/plain' },
      datos: 'Test'
    });

    expect(respuesta).toEqual({
      datos: '"Test"',
      estado: 200,
      ok: true,
      textoEstado: 'OK'
    });
    expect(window.fetch).toHaveBeenCalledTimes(1);
    expect(window.fetch).toHaveBeenCalledWith(
      'https://otro-servidor.com/api/v1/test',
      {
        _bodyInit: undefined,
        _bodyText: '',
        body: 'Test',
        credentials: 'include',
        headers: { map: { 'content-type': 'text/plain' } },
        method: 'POST',
        mode: 'cors',
        referrer: null,
        signal: undefined,
        url: 'https://otro-servidor.com/api/v1/test'
      }
    );
  });

  test('debería manejar un error en la petición', async () => {
    expect.assertions(1);

    window.fetch = mockFetchError('Problema de red');

    const cliente = new ClienteHttp();
    try {
      await cliente.peticion({
        url: 'https://otro-servidor.com/api/v1/productos',
        cabeceras
      });
    } catch (error) {
      expect(error).toEqual(Error('Problema de red'));
    }
  });
});
