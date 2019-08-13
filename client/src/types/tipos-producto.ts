/* eslint-disable camelcase */
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
