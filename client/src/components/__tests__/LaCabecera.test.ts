import { BootstrapVue } from '@/plugins';
import { createLocalVue, mount, Wrapper } from '@vue/test-utils';
import Vue from 'vue';
import VueRouter from 'vue-router';

import LaCabecera from '../LaCabecera.vue';

describe('LaCabecera.vue', () => {
  let wrapper: Wrapper<Vue>;

  beforeEach(() => {
    const vue = createLocalVue();
    vue.use(VueRouter);
    vue.use(BootstrapVue.plugin);

    const router = new VueRouter({
      routes: [
        {
          path: '/',
          name: 'inicio',
          component: new Vue({ template: '<p>Vista inicio!</p>' })
        }
      ]
    });

    wrapper = mount(LaCabecera, { localVue: vue, router });
  });

  test('debería ir a la ruta inicio cuando se hace click sobre el logotipo', () => {
    wrapper.find('a.navbar-brand').trigger('click');
    expect(wrapper.vm.$route.name).toBe('inicio');
  });
});
