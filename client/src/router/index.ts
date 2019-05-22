import Vue from 'vue';
import Router from 'vue-router';

import InicioView from '../views/InicioView.vue';
import { rutaInicio } from './rutas';

Vue.use(Router);

export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/',
      name: rutaInicio,
      component: InicioView
    }
  ]
});
