<template>
  <div id="app">
    <LaCabecera
      :esta-logueado="estaLogueado"
      :nombre-usuario="nombreUsuario"
      @click-logotipo="irAInicio"
      @click-login="irAIniciarSesion"
      @click-cerrar-sesion="cerrarSesion"
    />

    <RouterView id="contenido" />

    <ElPieDePagina />
  </div>
</template>

<script lang="ts">
/* eslint-disable no-prototype-builtins */
import Vue from 'vue';
import { mapState, mapGetters, mapActions } from 'vuex';

// Componentes
import LaCabecera from './components/LaCabecera.vue';
import ElPieDePagina from './components/ElPieDePagina.vue';

// Store
import {
  MODULO_AUTENTICACION,
  MODULO_NOTIFICACIONES
} from './store/types/modulos';
import { LOGOUT } from './store/types/acciones';
import { NOMBRE_USUARIO, ULTIMA_NOTIFICACION } from './store/types/getters';
import { UltimaNotificacion } from './store/modules/notificaciones/modulo-notificaciones';

// Tipos
import { Notificacion } from './types/tipos-notificacion';

// Router
import { rutaInicio, rutaLogin } from './router/rutas';

// Otros
import { mostrarNotificacion } from './utils/notificaciones';

export default Vue.extend({
  name: 'App',
  components: { LaCabecera, ElPieDePagina },

  computed: {
    ...mapState(MODULO_AUTENTICACION, ['estaLogueado']),
    ...mapGetters(MODULO_AUTENTICACION, [NOMBRE_USUARIO]),
    ...mapGetters(MODULO_NOTIFICACIONES, [ULTIMA_NOTIFICACION])
  },

  watch: {
    [ULTIMA_NOTIFICACION]: {
      /* eslint-disable object-shorthand, func-names */
      handler: function(notificacion: UltimaNotificacion) {
        if (notificacion) {
          mostrarNotificacion(notificacion);
        }
      },
      deep: true
    }
  },

  methods: {
    ...mapActions(MODULO_AUTENTICACION, [LOGOUT]),

    irAInicio(): void {
      this.$router.push({ name: rutaInicio });
    },

    irAIniciarSesion(): void {
      this.$router.push({ name: rutaLogin });
    },

    cerrarSesion(): void {
      this.logout().then(() => {
        this.$router.push({ name: rutaInicio });
      });
    }
  }
});
</script>

<style lang="scss" scoped>
#app {
  display: flex;
  min-height: 100vh;
  flex-direction: column;
}

#contenido {
  flex: 1;
}
</style>
