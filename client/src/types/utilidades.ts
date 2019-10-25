// eslint-disable-next-line import/prefer-default-export
export interface Diccionario<Tipo> {
  [clave: string]: Tipo;
}

/**
 * Tomar todas las propiedades del tipo excepto el conjunto de propiedades especificado
 */
export type Omitir<T, K extends keyof T> = Pick<T, Exclude<keyof T, K>>;
