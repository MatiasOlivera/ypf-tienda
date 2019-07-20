<template>
  <div id="contenedor">
    <loading
      :active.sync="estaCargando"
      :is-full-page="pantallaCompleta"
    ></loading>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { PropValidator } from 'vue/types/options';

// Componentes
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

// Props
export type PropEstaCargando = boolean;
export type PropPantallaCompleta = boolean;

export default Vue.extend({
  name: 'Cargando',

  components: { Loading },

  props: {
    estaCargando: {
      type: Boolean,
      default: false
    } as PropValidator<PropEstaCargando>,

    pantallaCompleta: {
      type: Boolean,
      default: false
    } as PropValidator<PropPantallaCompleta>
  }
});
</script>

<style scoped>
#contenedor {
  position: relative;
}
</style>
