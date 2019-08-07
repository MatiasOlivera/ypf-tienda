import { shallowMount } from '@vue/test-utils';
import { Component } from 'vue';

import filtroPlaceholderMixin from '../filtro-placeholder-mixin';

describe('filtroPlaceholderMixin', () => {
  const MiComponente: Component = {
    mixins: [filtroPlaceholderMixin],
    data() {
      return { valor: '' };
    },
    template: `<p>{{ valor | placeholder('Texto alternativo') }}</p>`
  };

  describe('debería devolver el placeholder', () => {
    test('cuando el valor es null', () => {
      const wrapper = shallowMount(MiComponente, {
        data() {
          return { valor: null };
        }
      });
      expect(wrapper.text()).toBe('Texto alternativo');
    });

    test('cuando el valor es undefined', () => {
      const wrapper = shallowMount(MiComponente, {
        data() {
          return { valor: undefined };
        }
      });
      expect(wrapper.text()).toBe('Texto alternativo');
    });

    test('cuando el valor es un string vacío', () => {
      const wrapper = shallowMount(MiComponente, {
        data() {
          return { valor: '' };
        }
      });
      expect(wrapper.text()).toBe('Texto alternativo');
    });
  });

  describe('debería devolver el valor', () => {
    test('cuando el valor es una cadena', () => {
      const wrapper = shallowMount(MiComponente, {
        data() {
          return { valor: 'Juan' };
        }
      });
      expect(wrapper.text()).toBe('Juan');
    });

    test('cuando el valor es un número', () => {
      const wrapper = shallowMount(MiComponente, {
        data() {
          return { valor: 30 };
        }
      });
      expect(wrapper.text()).toBe('30');
    });
  });
});
