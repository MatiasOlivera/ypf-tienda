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
          <b-nav-item id="link-productos" @click="clickProductos">
            Productos
          </b-nav-item>

          <b-nav-item href="#">
            Quienes somos
          </b-nav-item>
        </b-navbar-nav>

        <b-navbar-nav class="ml-auto">
          <b-nav-item-dropdown
            v-if="estaLogueado"
            id="nombre-usuario"
            :text="nombreUsuario"
            right
          >
            <b-dropdown-item id="link-perfil" @click="clickPerfil">
              Perfil
            </b-dropdown-item>
            <b-dropdown-item id="link-cerrar-sesion" @click="clickCerrarSesion">
              Cerrar sesión
            </b-dropdown-item>
          </b-nav-item-dropdown>

          <template v-else>
            <b-button
              id="boton-iniciar-sesion"
              size="sm"
              variant="link"
              @click="clickLogin"
            >
              Iniciar sesión
            </b-button>

            <b-button
              id="boton-registro"
              size="sm"
              variant="link"
              @click="clickRegistro"
            >
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
      this.$emit('clickLogotipo');
    },

    clickProductos(): void {
      this.$emit('clickProductos');
    },

    clickLogin(): void {
      this.$emit('clickLogin');
    },

    clickRegistro(): void {
      this.$emit('clickRegistro');
    },

    clickPerfil(): void {
      this.$emit('clickPerfil');
    },

    clickCerrarSesion(): void {
      this.$emit('clickCerrarSesion');
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
