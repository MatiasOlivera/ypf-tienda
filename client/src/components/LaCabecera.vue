<template>
  <b-navbar
    variant="white"
    type="light"
    :sticky="true"
    toggleable="md"
    class="shadow"
  >
    <b-container>
      <b-navbar-brand @click="clickLogotipo">
        <img src="../static/images/logotipo.png" alt="Logotipo" />
      </b-navbar-brand>

      <b-navbar-toggle target="nav_collapse" label="Cambiar navegación" />

      <b-collapse id="nav_collapse" is-nav>
        <b-navbar-nav>
          <b-nav-item href="#">
            Productos
          </b-nav-item>

          <b-nav-item href="#">
            Quienes somos
          </b-nav-item>
        </b-navbar-nav>

        <b-navbar-nav class="ml-auto">
          <b-nav-item-dropdown v-if="estaLogueado" :text="nombreUsuario" right>
            <b-dropdown-item @click="clickPerfil">
              Perfil
            </b-dropdown-item>
            <b-dropdown-item @click="clickCerrarSesion">
              Cerrar sesión
            </b-dropdown-item>
          </b-nav-item-dropdown>

          <template v-else>
            <b-button size="sm" variant="link" @click="clickLogin">
              Iniciar sesión
            </b-button>

            <b-button size="sm" variant="link" @click="clickRegistro">
              Registrarse
            </b-button>
          </template>
        </b-navbar-nav>
      </b-collapse>
    </b-container>
  </b-navbar>
</template>

<script lang="ts">
import Vue from 'vue';
import { PropValidator } from 'vue/types/options';
import { rutaInicio, rutaLogin } from '../router/rutas';

// Props
export type PropEstaLogueado = boolean;
export type PropNombreUsuario = string;

export default Vue.extend({
  name: 'LaCabecera',

  props: {
    estaLogueado: {
      type: Boolean,
      default: false
    } as PropValidator<PropEstaLogueado>,

    nombreUsuario: {
      type: String,
      default: ''
    } as PropValidator<PropNombreUsuario>
  },

  methods: {
    clickLogotipo(): void {
      this.$emit('click-logotipo');
    },

    clickLogin(): void {
      this.$emit('click-login');
    },

    clickRegistro(): void {
      this.$emit('click-registro');
    },

    clickPerfil(): void {
      this.$emit('click-perfil');
    },

    clickCerrarSesion(): void {
      this.$emit('click-cerrar-sesion');
    }
  }
});
</script>

<style lang="scss">
.navbar-brand {
  padding: 0px 15px;
}

.navbar-brand > img {
  height: 40px;
}

.nav-link,
.dropdown-item {
  text-align: center;
}
</style>
