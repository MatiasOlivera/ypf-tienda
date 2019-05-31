import Vue from 'vue';
import Vuex from 'vuex';

import modulos from './modules';
import { EstadoBase } from './tipos-store';

Vue.use(Vuex);

const store = new Vuex.Store<EstadoBase>({
  modules: modulos
});

export default store;
