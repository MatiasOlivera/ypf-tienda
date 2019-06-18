import { Almacen } from '../almacen-servicio';
import { productos, producto } from '../__mocks__/productos.mock';

describe('Servicio almacen', () => {
  let almacen: Almacen;
  beforeAll(() => {
    almacen = new Almacen();
  });

  test('debería guardar una cadena en el almacen', () => {
    const resultado = almacen.setItem('token', 'tok3n');
    const cadena = almacen.getItem('token');

    expect(resultado).toBe(true);
    expect(cadena).toBe('tok3n');
  });

  test('debería guardar un booleano en el almacen', () => {
    const resultado = almacen.setItem('logueado', true);
    const booleano = almacen.getItem('logueado');

    expect(resultado).toBe(true);
    expect(booleano).toBe(true);
  });

  test('debería guardar un numero en el almacen', () => {
    const resultado = almacen.setItem('total', 100);
    const numero = almacen.getItem('total');

    expect(resultado).toBe(true);
    expect(numero).toBe(100);
  });

  test('debería guardar un arreglo en el almacen', () => {
    const resultado = almacen.setItem('productos', productos);
    const arreglo = almacen.getItem('productos');

    expect(resultado).toBe(true);
    expect(arreglo).toEqual(productos);
  });

  test('debería guardar un objeto en el almacen', () => {
    const resultado = almacen.setItem('producto', producto);
    const objeto = almacen.getItem('producto');

    expect(resultado).toBe(true);
    expect(objeto).toEqual(producto);
  });

  test('debería obtener un item del almacen', () => {
    almacen.setItem('token', 'tok3n');
    const resultado = almacen.getItem('token');

    expect(resultado).toBe('tok3n');
  });

  test('debería obtener null cuando la clave no existe', () => {
    const resultado = almacen.getItem('noExiste');

    expect(resultado).toBeNull();
  });

  test('debería eliminar un item del almacen', () => {
    almacen.setItem('token', 'tok3n');
    const resultado = almacen.eliminarItem('token');

    expect(resultado).toBe(true);
  });

  test('debería eliminar todos los items del almacen', () => {
    almacen.setItem('token', 'tok3n');
    almacen.limpiar();
    const resultado = almacen.getItem('token');

    expect(resultado).toBeNull();
  });
});
