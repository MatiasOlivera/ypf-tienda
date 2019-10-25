/* eslint-disable camelcase */
import { StateValue } from 'xstate';
import { Modelo } from './tipos-modelo';

export interface ProductoBase extends Modelo {
  codigo: string;
  nombre: string;
  presentacion: string;
  id_categoria: number;
  imagen: string | null;
}

export interface Producto extends ProductoBase {
  precio_por_mayor: number;
  consumidor_final: number;
}

export interface ProductoCliente extends ProductoBase {
  es_favorito: boolean;
}

export interface ProductoFavorito {
  id: number;
  valor: boolean;
  estadoActual: StateValue;
}

export interface ProductoConRelaciones extends ProductoBase {
  esFavorito: ProductoFavorito;
}

export type TipoProducto = ProductoBase | ProductoCliente;
export type TipoProductos = Array<ProductoBase | ProductoCliente>;
