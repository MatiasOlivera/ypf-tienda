import { BootstrapVue } from '@/plugins';
import { createLocalVue, mount, Wrapper } from '@vue/test-utils';
import Vue from 'vue';

import ElPieDePagina from '../ElPieDePagina.vue';

describe('ElPieDePagina.vue', () => {
  let wrapper: Wrapper<Vue>;

  beforeEach(() => {
    const vue = createLocalVue();
    vue.use(BootstrapVue.plugin);

    wrapper = mount(ElPieDePagina, { localVue: vue });
  });

  test('debería renderizar un footer', () => {
    expect(wrapper.is('footer')).toBe(true);
  });

  test('debería mostrar el logotipo', () => {
    expect(wrapper.contains('img')).toBe(true);
  });
});
