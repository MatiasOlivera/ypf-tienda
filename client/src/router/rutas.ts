import { RouteConfig } from 'vue-router';

import InicioView from '../views/InicioView.vue';

// Nombres
export const rutaInicio = 'inicio';
export const rutaRegistro = 'registro';

export const rutas: Array<RouteConfig> = [
  {
    path: '/',
    name: rutaInicio,
    component: InicioView
  },
  {
    path: '/registro',
    name: rutaRegistro,
    component: () => import('../views/registroView.vue')
  }
];

export default rutas;
