import { RouteConfig } from 'vue-router';

import InicioView from '../views/InicioView.vue';

// Nombres
export const rutaInicio = 'inicio';

export const rutas: Array<RouteConfig> = [
  {
    path: '/',
    name: rutaInicio,
    component: InicioView
  }
];

export default rutas;
