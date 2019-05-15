import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export interface EstadoBase {}

const store = new Vuex.Store<EstadoBase>({
  state: {},
  mutations: {},
  actions: {}
});

export default store;
