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
    const renovar = servicio.esPosibleRenovarToken();
    expect(renovar).toBe('LOGIN');
  });

  test('debería obtener el token', () => {
    const servicio = new ServicioToken();
    servicio.setToken('bearer', 'tok3n');
    const resultado = servicio.getToken();

    expect(resultado).toBe('bearer tok3n');
  });

  test('debería devolver "renovar" si es posible renovar el token', () => {
    const fechaExpiracion = dayjs()
      .add(20, 'minute')
      .toISOString();

    const servicio = new ServicioToken();
    servicio.setToken('bearer', 'tok3n');
    servicio.setFechaExpiracion(fechaExpiracion);
    const resultado = servicio.esPosibleRenovarToken();

    expect(resultado).toBe('RENOVAR');
  });

  test('debería devolver "no renovar" si la fecha actual es anterior a la fecha a partir de la cual se puede renovar', () => {
    const fechaExpiracion = dayjs()
      .add(31, 'minute')
      .toISOString();

    const servicio = new ServicioToken();
    servicio.setToken('bearer', 'tok3n');
    servicio.setFechaExpiracion(fechaExpiracion);
    const resultado = servicio.esPosibleRenovarToken();

    expect(resultado).toBe('NO_RENOVAR');
  });

  test('debería devolver "login" si la fecha actual es posterior que la fecha de expiración', () => {
    const fechaExpiracion = dayjs()
      .subtract(60, 'minute')
      .toISOString();

    const servicio = new ServicioToken();
    servicio.setToken('bearer', 'tok3n');
    servicio.setFechaExpiracion(fechaExpiracion);
    const resultado = servicio.esPosibleRenovarToken();

    expect(resultado).toBe('LOGIN');
  });

  test('debería devolver "login" si no existe la fecha de expiración', () => {
    const servicio = new ServicioToken();
    const resultado = servicio.esPosibleRenovarToken();

    expect(resultado).toBe('LOGIN');
  });
});
