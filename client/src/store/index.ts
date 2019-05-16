import Vue from 'vue';
import Vuex from 'vuex';

import modulos from './modules';

Vue.use(Vuex);

export interface EstadoBase {}

const store = new Vuex.Store<EstadoBase>({
  modules: modulos
});

export default store;
