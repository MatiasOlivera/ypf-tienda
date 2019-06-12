import dayjs from 'dayjs';
import utc from 'dayjs/plugin/utc';

import { ServicioToken } from '../token-servicio';

dayjs.extend(utc);

describe('Servicio token', () => {
  beforeEach(() => {
    const servicio = new ServicioToken();
    servicio.limpiar();
  });

  test('debería guardar el token y la fecha de expiración', () => {
    const fecha = dayjs().toISOString();

    const servicio = new ServicioToken();
    const tokenGuardado = servicio.setToken('bearer', 'tok3n');
    const fechaGuardada = servicio.setFechaExpiracion(fecha);

    expect(tokenGuardado).toBe(true);
    expect(fechaGuardada).toBe(true);
  });

  test('debería borrar el token y la fecha de expiración', () => {
    const fecha = dayjs().toISOString();

    const servicio = new ServicioToken();
    servicio.setToken('bearer', 'tok3n');
    servicio.setFechaExpiracion(fecha);
    servicio.limpiar();

    const token = servicio.getToken();
    expect(token).toBeNull();
    const renovar = servicio.getEstadoToken();
    expect(renovar).toBe('NO_TOKEN');
  });

  test('debería obtener el token', () => {
    const servicio = new ServicioToken();
    servicio.setToken('bearer', 'tok3n');
    const resultado = servicio.getToken();

    expect(resultado).toBe('bearer tok3n');
  });

  test('debería devolver "POSIBLE_RENOVAR" si es posible renovar el token', () => {
    const fechaExpiracion = dayjs()
      .add(20, 'minute')
      .toISOString();

    const servicio = new ServicioToken();
    servicio.setToken('bearer', 'tok3n');
    servicio.setFechaExpiracion(fechaExpiracion);
    const resultado = servicio.getEstadoToken();

    expect(resultado).toBe('POSIBLE_RENOVAR');
  });

  test('debería devolver "VALIDO" si el token es válido y todavía no es posible renovarlo', () => {
    const fechaExpiracion = dayjs()
      .add(31, 'minute')
      .toISOString();

    const servicio = new ServicioToken();
    servicio.setToken('bearer', 'tok3n');
    servicio.setFechaExpiracion(fechaExpiracion);
    const resultado = servicio.getEstadoToken();

    expect(resultado).toBe('VALIDO');
  });

  test('debería devolver "EXPIRO" si el token expiró', () => {
    const fechaExpiracion = dayjs()
      .subtract(60, 'minute')
      .toISOString();

    const servicio = new ServicioToken();
    servicio.setToken('bearer', 'tok3n');
    servicio.setFechaExpiracion(fechaExpiracion);
    const resultado = servicio.getEstadoToken();

    expect(resultado).toBe('EXPIRO');
  });

  test('debería devolver "NO_TOKEN" si no existe la fecha de expiración', () => {
    const servicio = new ServicioToken();
    const resultado = servicio.getEstadoToken();

    expect(resultado).toBe('NO_TOKEN');
  });
});
