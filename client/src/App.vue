<template>
  <div id="app">
    <LaCabecera />

    <RouterView />
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import LaCabecera from './components/LaCabecera.vue';

export default Vue.extend({
  name: 'App',
  components: { LaCabecera }
});
</script>
