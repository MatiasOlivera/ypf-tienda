<template>
  <b-navbar
    variant="white"
    type="light"
    :sticky="true"
    toggleable
    toggle-breakpoint="md"
    class="shadow"
  >
    <b-container>
      <b-navbar-brand>
        <img src="../assets/logotipo.png" alt="Logotipo" />
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
          <b-button size="sm" variant="link">
            Iniciar sesión
          </b-button>

          <b-button size="sm" variant="link">
            Registrarse
          </b-button>
        </b-navbar-nav>
      </b-collapse>
    </b-container>
  </b-navbar>
</template>

<script lang="ts">
import Vue from 'vue';

export default Vue.extend({
  name: 'LaCabecera'
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
