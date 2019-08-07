import isArray from 'lodash/isArray';
import isEmpty from 'lodash/isEmpty';
import isFinite from 'lodash/isFinite';
import isString from 'lodash/isString';

import Mixin from '../tipos-mixins';

interface Opciones {
  incluirNumero: boolean;
}

/**
 * Devuelve la palabra en singular o plural según el valor especificado
 * @param valor
 */
export function pluralizar(
  valor: number,
  palabra: string | Array<string>,
  opciones: Opciones = { incluirNumero: false }
): string {
  if (valor === undefined || valor === null || !isFinite(valor)) {
    return '';
  }

  if (
    palabra === undefined ||
    palabra === null ||
    (!isString(palabra) && !isArray(palabra)) ||
    (isArray(palabra) && isEmpty(palabra))
  ) {
    return '';
  }

  let salida: string = '';

  if (opciones.incluirNumero === true) {
    salida += `${valor} `;
  }

  if (isArray(palabra)) {
    salida += palabra[valor - 1] || palabra[palabra.length - 1];
  } else {
    salida += valor === 1 ? palabra : `${palabra}s`;
  }

  return salida;
}

const filtroPluralizarMixin: Mixin = {
  filters: {
    pluralizar
  }
};

export default filtroPluralizarMixin;
