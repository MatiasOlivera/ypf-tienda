import dayjs from 'dayjs';
import utc from 'dayjs/plugin/utc';

import { ServicioAutenticacion } from '../servicio-autenticacion';

dayjs.extend(utc);

describe('Servicio autenticación', () => {
  beforeEach(() => {
    const autenticacion = new ServicioAutenticacion();
    autenticacion.limpiar();
  });

  test('debería guardar el token y la fecha de expiración', () => {
    const fecha = dayjs().toISOString();

    const autenticacion = new ServicioAutenticacion();
    const tokenGuardado = autenticacion.setToken('bearer', 'tok3n');
    const fechaGuardada = autenticacion.setFechaExpiracion(fecha);

    expect(tokenGuardado).toBe(true);
    expect(fechaGuardada).toBe(true);
  });

  test('debería borrar el token y la fecha de expiración', () => {
    const fecha = dayjs().toISOString();

    const autenticacion = new ServicioAutenticacion();
    autenticacion.setToken('bearer', 'tok3n');
    autenticacion.setFechaExpiracion(fecha);
    autenticacion.limpiar();

    const token = autenticacion.getToken();
    expect(token).toBeNull();
    const renovar = autenticacion.getEstadoToken();
    expect(renovar).toBe('NO_TOKEN');
  });

  test('debería obtener el token', () => {
    const autenticacion = new ServicioAutenticacion();
    autenticacion.setToken('bearer', 'tok3n');
    const resultado = autenticacion.getToken();

    expect(resultado).toBe('bearer tok3n');
  });

  test('debería devolver "POSIBLE_RENOVAR" si es posible renovar el token', () => {
    const fechaExpiracion = dayjs()
      .add(20, 'minute')
      .toISOString();

    const autenticacion = new ServicioAutenticacion();
    autenticacion.setToken('bearer', 'tok3n');
    autenticacion.setFechaExpiracion(fechaExpiracion);
    const resultado = autenticacion.getEstadoToken();

    expect(resultado).toBe('POSIBLE_RENOVAR');
  });

  test('debería devolver "VALIDO" si el token es válido y todavía no es posible renovarlo', () => {
    const fechaExpiracion = dayjs()
      .add(31, 'minute')
      .toISOString();

    const autenticacion = new ServicioAutenticacion();
    autenticacion.setToken('bearer', 'tok3n');
    autenticacion.setFechaExpiracion(fechaExpiracion);
    const resultado = autenticacion.getEstadoToken();

    expect(resultado).toBe('VALIDO');
  });

  test('debería devolver "EXPIRO" si el token expiró', () => {
    const fechaExpiracion = dayjs()
      .subtract(60, 'minute')
      .toISOString();

    const autenticacion = new ServicioAutenticacion();
    autenticacion.setToken('bearer', 'tok3n');
    autenticacion.setFechaExpiracion(fechaExpiracion);
    const resultado = autenticacion.getEstadoToken();

    expect(resultado).toBe('EXPIRO');
  });

  test('debería devolver "NO_TOKEN" si no existe la fecha de expiración', () => {
    const autenticacion = new ServicioAutenticacion();
    const resultado = autenticacion.getEstadoToken();

    expect(resultado).toBe('NO_TOKEN');
  });
});
