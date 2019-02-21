import { createLocalVue, mount } from '@vue/test-utils';
import { Component } from 'vue';

import BootstrapVue from '../bootstrap-vue';

describe('BootstrapVue', () => {
  test('debería renderizar el componente botón', () => {
    const vue = createLocalVue();
    vue.use(BootstrapVue.plugin);

    const MiComponente: Component = {
      template: `<b-button>Guardar</b-button>`
    };

    const wrapper = mount(MiComponente, { localVue: vue });
    expect(wrapper.is('button.btn')).toBe(true);
  });
});
