import 'whatwg-fetch';

import dayjs from 'dayjs';

import router from '../../router';
import { respuestaFetchMock } from '../__mocks__/fetch.mock';
import { productos } from '../__mocks__/productos.mock';
import { TokenDatos } from '../api/types/token-tipos';
import { clienteApi, clienteApiSinToken } from '../cliente-api';
import { ServicioToken } from '../token-servicio';

describe('Cliente API', () => {
  beforeAll(() => {
    process.env = { VUE_APP_API_ENDPOINT: 'https://servidor.com/api/' };
  });

  beforeEach(() => {
    const servicioToken = new ServicioToken();
    servicioToken.limpiar();
  });

  test('debería hacer una petición sin renovar el token', async () => {
    window.fetch = jest.fn(() => respuestaFetchMock(productos));
    const fechaExpiracion = dayjs()
      .add(31, 'minute')
      .toISOString();

    const servicioToken = new ServicioToken();
    servicioToken.setToken('bearer', 'token');
    servicioToken.setFechaExpiracion(fechaExpiracion);

    const respuesta = await clienteApi({
      url: 'productos',
      datos: { paginacion: 10, ordenar_por: 'nombre', direccion: 'ASC' }
    });

    expect(respuesta).toEqual({
      datos: productos,
      estado: 200,
      ok: true,
      textoEstado: 'OK'
    });
    expect(window.fetch).toHaveBeenCalledTimes(1);
    expect(window.fetch).toBeCalledWith(
      'https://servidor.com/api/productos?paginacion=10&ordenar_por=nombre&direccion=ASC',
      {
        _bodyInit: undefined,
        _bodyText: '',
        credentials: 'include',
        headers: {
          map: {
            accept: 'application/json',
            authorization: 'bearer token',
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

  test('debería hacer una petición después de renovar el token', async () => {
    const fechaExpiracion = dayjs()
      .add(20, 'minute')
      .toISOString();

    const servicioToken = new ServicioToken();
    servicioToken.setToken('bearer', 'token');
    servicioToken.setFechaExpiracion(fechaExpiracion);

    const respuestaToken: TokenDatos = {
      token: 'nuevo.token',
      tipoToken: 'bearer',
      fechaExpiracion: dayjs().toISOString()
    };

    window.fetch = jest
      .fn()
      .mockReturnValueOnce(respuestaFetchMock(respuestaToken))
      .mockReturnValueOnce(respuestaFetchMock(productos));

    const respuesta = await clienteApi({
      url: 'productos',
      datos: { paginacion: 10, ordenar_por: 'nombre', direccion: 'ASC' }
    });

    expect(window.fetch).toHaveBeenNthCalledWith(
      1,
      'https://servidor.com/api/auth/renovar',
      {
        _bodyInit: undefined,
        _bodyText: '',
        credentials: 'include',
        headers: {
          map: {
            accept: 'application/json',
            authorization: 'bearer token',
            'content-type': 'application/json'
          }
        },
        method: 'POST',
        mode: 'cors',
        referrer: null,
        signal: undefined,
        url: 'https://servidor.com/api/auth/renovar'
      }
    );

    expect(window.fetch).toHaveBeenNthCalledWith(
      2,
      'https://servidor.com/api/productos?paginacion=10&ordenar_por=nombre&direccion=ASC',
      {
        _bodyInit: undefined,
        _bodyText: '',
        credentials: 'include',
        headers: {
          map: {
            accept: 'application/json',
            authorization: 'bearer nuevo.token',
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
    expect(window.fetch).toHaveBeenCalledTimes(2);

    expect(respuesta).toEqual({
      datos: productos,
      estado: 200,
      ok: true,
      textoEstado: 'OK'
    });
  });

  test('debería ir a la ruta login si el token ya expiró', async () => {
    window.fetch = jest.fn(() => respuestaFetchMock(productos));
    const fechaExpiracion = dayjs()
      .subtract(31, 'minute')
      .toISOString();

    const servicioToken = new ServicioToken();
    servicioToken.setToken('bearer', 'token');
    servicioToken.setFechaExpiracion(fechaExpiracion);

    const respuesta = await clienteApi({
      url: 'productos',
      datos: { paginacion: 10, ordenar_por: 'nombre', direccion: 'ASC' }
    });

    expect(respuesta).toEqual({
      ok: false,
      estado: 401,
      textoEstado: 'Unauthorized',
      datos: null
    });
    expect(window.fetch).toHaveBeenCalledTimes(0);
    expect(router.currentRoute.name).toBe('inicio');
  });
});

describe('Cliente API sin token', () => {
  beforeAll(() => {
    process.env = { VUE_APP_API_ENDPOINT: 'https://servidor.com/api/' };
  });

  test('debería hacer una petición sin token', async () => {
    const login: TokenDatos = {
      token: 'primer.token',
      tipoToken: 'bearer',
      fechaExpiracion: dayjs().toISOString()
    };

    window.fetch = jest.fn(() => respuestaFetchMock(login));

    const respuesta = await clienteApiSinToken({
      url: 'login',
      metodo: 'POST',
      datos: { usuario: 'Dev', password: '1234' }
    });

    expect(respuesta).toEqual({
      datos: login,
      estado: 200,
      ok: true,
      textoEstado: 'OK'
    });
    expect(window.fetch).toHaveBeenCalledTimes(1);
    expect(window.fetch).toBeCalledWith('https://servidor.com/api/login', {
      _bodyInit: undefined,
      _bodyText: '',
      body: '{"usuario":"Dev","password":"1234"}',
      credentials: 'include',
      headers: {
        map: { accept: 'application/json', 'content-type': 'application/json' }
      },
      method: 'POST',
      mode: 'cors',
      referrer: null,
      signal: undefined,
      url: 'https://servidor.com/api/login'
    });
  });
});
