import { shallowMount } from '@vue/test-utils';
import { Component } from 'vue';

import formatoTelefonoMixin from '../formato-telefono-mixin';

describe('formatoTelefonoMixin', () => {
  const MiComponente: Component = {
    mixins: [formatoTelefonoMixin],
    props: {
      telefono: {
        type: Number,
        default: ''
      }
    },
    template: `<p>{{ telefono | formatoTelefono }}</p>`
  };

  test('debería devolver un string vacío cuando la prop es null', () => {
    const wrapper = shallowMount(MiComponente, {
      propsData: { telefono: null }
    });
    expect(wrapper.text()).toBe('');
  });

  test('debería formatear el teléfono cuando la prop es válida', () => {
    const wrapper = shallowMount(MiComponente, {
      propsData: { telefono: 541234567890 }
    });
    expect(wrapper.text()).toBe('1234 567890');
  });
});
