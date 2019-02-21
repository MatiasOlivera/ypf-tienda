import Vue from 'vue';

import App from './App.vue';
import { BootstrapVue, VueFeather } from './plugins';
import router from './router';
import store from './store';

// Plugins
Vue.use(BootstrapVue.plugin);
Vue.use(VueFeather.plugin);

Vue.config.productionTip = false;

new Vue({
  router,
  store,
  render: (h) => h(App)
}).$mount('#app');
