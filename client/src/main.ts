import Vue from 'vue';

import App from './App.vue';
import { validarVariables } from './config/variables-entorno';
import { BootstrapVue, VueFeather } from './plugins';
import router from './router';
import store from './store';

// Plugins
Vue.use(BootstrapVue.plugin);
Vue.use(VueFeather.plugin);

// Variables de entorno
const mensajes = validarVariables(process.env, ['VUE_APP_API_ENDPOINT']);

if (mensajes.length > 0) {
  mensajes.forEach((mensaje) => console.error(mensaje));
}

Vue.config.productionTip = false;

new Vue({
  router,
  store,
  render: (h) => h(App)
}).$mount('#app');
