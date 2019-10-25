/* eslint-disable camelcase */
/* eslint-disable import/prefer-default-export */

import { Producto } from '@/types/tipos-producto';
import { ProductoServidor } from './producto/producto-tipo';

/**
 * Convierte los precios del producto de string a número
 *
 * @param producto
 */
export function convertirPreciosANumero(producto: ProductoServidor): Producto {
  const consumidor_final: number = Number(producto.consumidor_final);
  const precio_por_mayor: number = Number(producto.precio_por_mayor);

  return { ...producto, consumidor_final, precio_por_mayor };
}
