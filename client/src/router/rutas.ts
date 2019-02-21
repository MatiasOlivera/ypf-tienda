import { RouteConfig } from 'vue-router';

import Inicio from '../views/Inicio.vue';

// Nombres
export const rutaInicio = 'inicio';

export const rutas: Array<RouteConfig> = [
  {
    path: '/',
    name: rutaInicio,
    component: Inicio
  }
];

export default rutas;
