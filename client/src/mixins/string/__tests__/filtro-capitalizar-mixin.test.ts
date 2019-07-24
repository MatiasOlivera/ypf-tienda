import { shallowMount } from '@vue/test-utils';
import { Component } from 'vue';

import filtroCapitalizarMixin from '../filtro-capitalizar-mixin';

describe('filtroCapitalizarMixin', () => {
  const MiComponente: Component = {
    mixins: [filtroCapitalizarMixin],
    props: {
      nombre: {
        type: String,
        default: ''
      }
    },
    template: `<p>{{ nombre | capitalizar }}</p>`
  };

  test('debería devolver un string vacío cuando la prop es null', () => {
    const wrapper = shallowMount(MiComponente, {
      propsData: { nombre: null }
    });
    expect(wrapper.text()).toBe('');
  });

  test('debería devolver un string vacío cuando la prop es un string vacío', () => {
    const wrapper = shallowMount(MiComponente, {
      propsData: { nombre: '' }
    });
    expect(wrapper.text()).toBe('');
  });

  test('debería capitalizar la cadena', () => {
    const wrapper = shallowMount(MiComponente, {
      propsData: { nombre: 'COMBUSTIBLE' }
    });
    expect(wrapper.text()).toBe('Combustible');
  });
});
