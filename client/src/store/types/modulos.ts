export function obtenerEspacioDeNombres(modulos: Array<string>): string {
  return modulos.join('/');
}

export const MODULO_NOTIFICACIONES = 'notificaciones';
export const MODULO_AUTENTICACION = 'autenticacion';
export const MODULO_PRODUCTOS = 'productos';
export const MODULO_PRODUCTOS_FAVORITOS = 'favoritos';

export const PRODUCTOS_PRODUCTOS_FAVORITOS = obtenerEspacioDeNombres([
  MODULO_PRODUCTOS,
  MODULO_PRODUCTOS_FAVORITOS
]);
