import { shallowMount } from '@vue/test-utils';
import { Component } from 'vue';

import formatoCuitMixin from '../formato-cuit-mixin';

describe('formatoCuitMixin', () => {
  const MiComponente: Component = {
    mixins: [formatoCuitMixin],
    props: {
      cuit: {
        type: [Number, String],
        default: ''
      }
    },
    template: `<p>{{ cuit | formatoCuit }}</p>`
  };

  test('debería devolver un string vacío cuando la prop es null', () => {
    const wrapper = shallowMount(MiComponente, { propsData: { cuit: null } });
    expect(wrapper.text()).toBe('');
  });

  test('debería devolver un string vacío cuando la prop es un string vacío', () => {
    const wrapper = shallowMount(MiComponente, { propsData: { cuit: '' } });
    expect(wrapper.text()).toBe('');
  });

  test('debería rellenar el cuit con caracteres cuando la prop tiene menos de 11 digitos', () => {
    const wrapper = shallowMount(MiComponente, { propsData: { cuit: 1234 } });
    expect(wrapper.text()).toBe('12-34xxxxxx-x');
  });

  test('debería formatear el cuit cuando la prop es válida', () => {
    const wrapper = shallowMount(MiComponente, {
      propsData: { cuit: 12345678901 }
    });
    expect(wrapper.text()).toBe('12-34567890-1');
  });

  test('debería truncar el cuit cuando la prop tiene más de 11 digitos', () => {
    const wrapper = shallowMount(MiComponente, {
      propsData: { cuit: 12345678901234 }
    });
    expect(wrapper.text()).toBe('12-34567890-1');
  });
});
