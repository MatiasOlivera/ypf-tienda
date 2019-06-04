<template>
  <div id="app">
    <LaCabecera />

    <RouterView id="contenido" />

    <ElPieDePagina />
  </div>
</template>

<script lang="ts">
/* eslint-disable no-prototype-builtins */
import Vue from 'vue';
import { mapGetters } from 'vuex';

import LaCabecera from './components/LaCabecera.vue';
import ElPieDePagina from './components/ElPieDePagina.vue';
import { MODULO_NOTIFICACIONES } from './store/types/modulos';
import { Notificacion } from './types/tipos-notificacion';
import { mostrarNotificacion } from './utils/notificaciones';
import { UltimaNotificacion } from './store/modules/notificaciones/modulo-notificaciones';
import { ULTIMA_NOTIFICACION } from './store/types/getters';

export default Vue.extend({
  name: 'App',
  components: { LaCabecera, ElPieDePagina },

  computed: {
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
