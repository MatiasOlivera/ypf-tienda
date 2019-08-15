import { getProductos as _getProductos } from './productos/get-productos';
import { getProductosAutenticado as _getProductosAutenticado } from './productos/get-productos-autenticado';

export const getProductos = _getProductos;
export const getProductosAutenticado = _getProductosAutenticado;

export default {
  getProductos,
  getProductosAutenticado
};
