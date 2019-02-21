<template>
  <div id="app">
    <LaCabecera />

    <RouterView id="contenido" />

    <ElPieDePagina />
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import LaCabecera from './components/LaCabecera.vue';
import ElPieDePagina from './components/ElPieDePagina.vue';

export default Vue.extend({
  name: 'App',
  components: { LaCabecera, ElPieDePagina }
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
