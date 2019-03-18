import { Almacen } from '../almacen-servicio';

describe('Servicio almacen', () => {
  test('guardar un item en el almacen', () => {
    const almacen = new Almacen();
    const resultado = almacen.setItem('token', 'tok3n');

    expect(resultado).toBe(true);
  });

  test('obtener un item del almacen', () => {
    const almacen = new Almacen();
    almacen.setItem('token', 'tok3n');
    const resultado = almacen.getItem('token');

    expect(resultado).toBe('tok3n');
  });

  test('eliminar un item del almacen', () => {
    const almacen = new Almacen();
    almacen.setItem('token', 'tok3n');
    const resultado = almacen.eliminarItem('token');

    expect(resultado).toBe(true);
  });

  test('eliminar todos los items del almacen', () => {
    const almacen = new Almacen();
    almacen.setItem('token', 'tok3n');
    almacen.limpiar();
    const resultado = almacen.getItem('token');

    expect(resultado).toBeNull();
  });
});
