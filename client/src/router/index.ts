import Vue from 'vue';
import Router, { RouteConfig } from 'vue-router';

import InicioView from '../views/InicioView.vue';
import { rutaInicio, rutaLogin, rutaProductos } from './rutas';

Vue.use(Router);

export const rutas: RouteConfig[] = [
  {
    path: '/',
    name: rutaInicio,
    component: InicioView
  },
  {
    path: '/iniciar-sesion',
    name: rutaLogin,
    component: () => import('@/views/LoginView.vue')
  },
  {
    path: '/productos',
    name: rutaProductos,
    component: () => import('@/views/ProductosView.vue')
  }
];

export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: rutas
});
