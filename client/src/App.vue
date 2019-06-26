<template>
  <div id="app">
    <LaCabecera
      :esta-logueado="estaLogueado"
      :nombre-usuario="nombreUsuario"
      @clickLogotipo="irAInicio"
      @clickLogin="irAIniciarSesion"
      @clickCerrarSesion="cerrarSesion"
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
import {
  LOGIN_CLIENTE,
  LOGOUT_CLIENTE,
  LOGOUT,
  CREAR_NOTIFICACION
} from './store/types/acciones';
import { NOMBRE_USUARIO, ULTIMA_NOTIFICACION } from './store/types/getters';
import { UltimaNotificacion } from './store/modules/notificaciones/modulo-notificaciones';

// Tipos
import { Notificacion } from './types/tipos-notificacion';

// Router
import { rutaInicio, rutaLogin } from './router/rutas';

// Otros
import { mostrarNotificacion } from './utils/notificaciones';
import { ServicioAutenticacion } from './services/servicio-autenticacion';

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

  created(): void {
    const autenticacion = new ServicioAutenticacion();
    const estado = autenticacion.getEstadoToken();

    if (estado === 'VALIDO') {
      const usuario = autenticacion.getUsuario();
      if (usuario) {
        this.loginCliente(usuario);
      }
      return;
    }

    if (estado === 'EXPIRO') {
      this.logoutCliente();

      const notificacion: Notificacion = {
        tipo: 'info',
        descripcion: 'La sesión ha expirado, por favor vuelva a iniciar sesión'
      };
      this.crearNotificacion(notificacion);

      this.irAIniciarSesion();
    }
  },

  methods: {
    ...mapActions(MODULO_AUTENTICACION, [
      LOGIN_CLIENTE,
      LOGOUT_CLIENTE,
      LOGOUT
    ]),
    ...mapActions(MODULO_NOTIFICACIONES, [CREAR_NOTIFICACION]),

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
