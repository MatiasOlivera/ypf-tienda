import { BootstrapVue } from '@/plugins';
import { createLocalVue, mount, Wrapper } from '@vue/test-utils';
import Vue from 'vue';
import VueRouter from 'vue-router';

import LaCabecera, {
  // @ts-ignore
  PropEstaLogueado,
  // @ts-ignore
  PropNombreUsuario
} from '../LaCabecera.vue';
import { rutas } from '@/router';

interface Props {
  estaLogueado?: PropEstaLogueado;
  nombreUsuario?: PropNombreUsuario;
}

describe('LaCabecera.vue', () => {
  let wrapper: Wrapper<Vue>;

  beforeEach(() => {
    const vue = createLocalVue();
    vue.use(VueRouter);
    vue.use(BootstrapVue.plugin);

    const router = new VueRouter({ routes: rutas });
    wrapper = mount(LaCabecera, { localVue: vue, router });
  });

  describe('no logueado', () => {
    test('debería emitir el evento clickLogotipo cuando se hace click sobre el logotipo', () => {
      wrapper.find('.navbar-brand').trigger('click');
      expect(wrapper.emitted().clickLogotipo).toBeTruthy();
    });

    test('debería emitir el evento clickProductos cuando se hace click sobre Productos', () => {
      wrapper.find('#link-productos a').trigger('click');
      expect(wrapper.emitted().clickProductos).toBeTruthy();
    });

    test('debería emitir el evento clickLogin cuando se hace click sobre Iniciar sesión', () => {
      wrapper.find('#boton-iniciar-sesion').trigger('click');
      expect(wrapper.emitted().clickLogin).toBeTruthy();
    });

    test('debería emitir el evento clickRegistro cuando se hace click sobre Registrarse', () => {
      wrapper.find('#boton-registro').trigger('click');
      expect(wrapper.emitted().clickRegistro).toBeTruthy();
    });
  });

  describe('logueado', () => {
    beforeEach(() => {
      const props: Props = { estaLogueado: true, nombreUsuario: 'Juan' };
      wrapper.setProps(props);
    });

    test('debería mostrar el nombre del usuario', () => {
      const nombre = wrapper.find('#nombre-usuario a span').text();
      expect(nombre).toBe('Juan');
    });

    test('debería emitir el evento clickPerfil cuando se hace click sobre Perfil', () => {
      wrapper.find('#link-perfil').trigger('click');
      expect(wrapper.emitted().clickPerfil).toBeTruthy();
    });

    test('debería emitir el evento clickCerrarSesion cuando se hace click sobre Cerrar sesión', () => {
      wrapper.find('#link-cerrar-sesion').trigger('click');
      expect(wrapper.emitted().clickCerrarSesion).toBeTruthy();
    });
  });
});
