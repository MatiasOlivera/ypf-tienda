import { validarVariables } from '../variables-entorno';

describe('validarVariables', () => {
  test('debería pasar la validación cuando variables es un array vacío', () => {
    const resultado = validarVariables({}, []);
    expect(resultado).toEqual([]);
  });

  test('debería fallar la validación cuando la variable no existe', () => {
    const resultado = validarVariables({}, ['VUE_APP_API']);
    const esperado = [
      'La variable de entorno VUE_APP_API debe ser especificada o el valor es incorrecto"'
    ];
    expect(resultado).toEqual(esperado);
  });

  test('debería pasar la validación cuando la variable existe', () => {
    const resultado = validarVariables(
      { VUE_APP_API: 'https://servidor.com/api' },
      ['VUE_APP_API']
    );
    expect(resultado).toEqual([]);
  });
});
