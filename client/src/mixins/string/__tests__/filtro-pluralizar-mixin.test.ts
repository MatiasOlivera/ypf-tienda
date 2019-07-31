import { pluralizar } from '../filtro-pluralizar-mixin';

describe('filtroPluralizarMixin', () => {
  function probarSinOpciones({ valor, palabra, esperado }: any): void {
    const actual = pluralizar(valor, palabra);
    expect(actual).toBe(esperado);
  }

  function probarConOpciones(argumentos: any): void {
    const { valor, palabra, opciones, esperado } = argumentos;
    const actual = pluralizar(valor, palabra, opciones);
    expect(actual).toBe(esperado);
  }

  describe('valor inválido', () => {
    test.each`
      valor        | palabra       | esperado
      ${undefined} | ${'producto'} | ${''}
      ${null}      | ${'producto'} | ${''}
      ${NaN}       | ${'producto'} | ${''}
      ${Infinity}  | ${'producto'} | ${''}
      ${'cadena'}  | ${'producto'} | ${''}
    `(
      'debería devolver $esperado cuando el valor es $valor',
      probarSinOpciones
    );
  });

  describe('palabra inválida', () => {
    test.each`
      valor | palabra               | esperado
      ${1}  | ${undefined}          | ${''}
      ${1}  | ${null}               | ${''}
      ${1}  | ${1}                  | ${''}
      ${1}  | ${[]}                 | ${''}
      ${1}  | ${{ clave: 'valor' }} | ${''}
    `(
      'debería devolver $esperado cuando la palabra es $palabra',
      probarSinOpciones
    );
  });

  describe('una palabra', () => {
    test.each`
      valor  | palabra       | esperado
      ${0}   | ${'producto'} | ${'productos'}
      ${1}   | ${'producto'} | ${'producto'}
      ${2}   | ${'producto'} | ${'productos'}
      ${100} | ${'producto'} | ${'productos'}
    `(
      'debería devolver $esperado cuando el valor es $valor',
      probarSinOpciones
    );
  });

  describe('dos palabras', () => {
    const palabras = ['razón social', 'razones sociales'];

    test.each`
      valor  | palabra     | esperado
      ${0}   | ${palabras} | ${'razones sociales'}
      ${1}   | ${palabras} | ${'razón social'}
      ${2}   | ${palabras} | ${'razones sociales'}
      ${100} | ${palabras} | ${'razones sociales'}
    `(
      'debería devolver $esperado cuando el valor es $valor',
      probarSinOpciones
    );
  });

  describe('varias palabras', () => {
    const palabras = ['primero', 'segundo', 'tercero', 'no está en el podio'];

    test.each`
      valor | palabra     | esperado
      ${0}  | ${palabras} | ${'no está en el podio'}
      ${1}  | ${palabras} | ${'primero'}
      ${2}  | ${palabras} | ${'segundo'}
      ${3}  | ${palabras} | ${'tercero'}
      ${4}  | ${palabras} | ${'no está en el podio'}
    `(
      'debería devolver $esperado cuando el valor es $valor',
      probarSinOpciones
    );
  });

  describe('no debería incluir el número', () => {
    test.each`
      valor | palabra                      | opciones                    | esperado
      ${1}  | ${'producto'}                | ${{ incluirNumero: false }} | ${'producto'}
      ${1}  | ${['producto', 'productos']} | ${{ incluirNumero: false }} | ${'producto'}
    `(
      'debería devolver $esperado cuando la palabra es $palabra',
      probarConOpciones
    );
  });

  describe('debería incluir el número', () => {
    test.each`
      valor | palabra                      | opciones                   | esperado
      ${1}  | ${'producto'}                | ${{ incluirNumero: true }} | ${'1 producto'}
      ${1}  | ${['producto', 'productos']} | ${{ incluirNumero: true }} | ${'1 producto'}
    `(
      'debería devolver $esperado cuando la palabra es $palabra',
      probarConOpciones
    );
  });
});
