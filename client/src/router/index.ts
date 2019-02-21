import Vue from 'vue';
import Router from 'vue-router';

import { rutas } from './rutas';

Vue.use(Router);

export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: rutas
});
