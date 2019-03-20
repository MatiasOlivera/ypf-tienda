import { ServicioToken } from '../token-servicio';

describe('Servicio token', () => {
  beforeEach(() => {
    const servicio = new ServicioToken();
    servicio.limpiar();
  });

  test('debería guardar el token y la fecha de expiración', () => {
    const fecha = new Date().toISOString();

    const servicio = new ServicioToken();
    const tokenGuardado = servicio.setToken('bearer', 'tok3n');
    const fechaGuardada = servicio.setFechaExpiracion(fecha);

    expect(tokenGuardado).toBe(true);
    expect(fechaGuardada).toBe(true);
  });

  test('debería borrar el token y la fecha de expiración', () => {
    const fecha = new Date().toISOString();

    const servicio = new ServicioToken();
    servicio.setToken('bearer', 'tok3n');
    servicio.setFechaExpiracion(fecha);
    servicio.limpiar();

    const token = servicio.getToken();
    expect(token).toBeNull();
    const renovar = servicio.esPosibleRenovarToken();
    expect(renovar).toBe(false);
  });

  test('debería obtener el token', () => {
    const servicio = new ServicioToken();
    servicio.setToken('bearer', 'tok3n');
    const resultado = servicio.getToken();

    expect(resultado).toBe('bearer tok3n');
  });

  test('debería devolver true si es posible renovar el token', () => {
    const fecha = new Date();
    fecha.setMinutes(fecha.getMinutes() + 5);
    const fechaExpiracion = fecha.toISOString();

    const servicio = new ServicioToken();
    servicio.setToken('bearer', 'tok3n');
    servicio.setFechaExpiracion(fechaExpiracion);
    const resultado = servicio.esPosibleRenovarToken();

    expect(resultado).toBe(true);
  });

  test('debería devolver false si no es posible renovar el token', () => {
    const fecha = new Date();
    fecha.setMinutes(fecha.getMinutes() - 5);
    const fechaExpiracion = fecha.toISOString();

    const servicio = new ServicioToken();
    servicio.setToken('bearer', 'tok3n');
    servicio.setFechaExpiracion(fechaExpiracion);
    const resultado = servicio.esPosibleRenovarToken();

    expect(resultado).toBe(false);
  });

  test('debería devolver false si no existe la fecha de expiración', () => {
    const servicio = new ServicioToken();
    const resultado = servicio.esPosibleRenovarToken();

    expect(resultado).toBe(false);
  });
});
