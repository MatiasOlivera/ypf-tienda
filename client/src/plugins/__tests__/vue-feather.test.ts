import { createLocalVue, mount } from '@vue/test-utils';
import { Component } from 'vue';

import VueFeather from '../vue-feather';

describe('VueFeather', () => {
  test('debería renderizar el icono de la pluma', () => {
    const vue = createLocalVue();
    vue.use(VueFeather.plugin);

    const MiComponente: Component = {
      template: `<feather type="feather"></feather>`
    };

    const wrapper = mount(MiComponente, { localVue: vue });
    expect(wrapper.is('i.feather.feather--feather')).toBe(true);
  });
});
