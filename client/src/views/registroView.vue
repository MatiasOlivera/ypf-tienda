<template>
  <main>
    <h2>Registro</h2>
    <div>
      <ClienteFormulario></ClienteFormulario>
    </div>
  </main>
</template>
<script>
import ClienteFormulario from '../components/ClienteFormulario.vue';

export default {
  name: 'RegistroView',
  components: { ClienteFormulario },
  data() {
    return {
      datos: null
    };
  },
  methods: {
    cancelarRegistro() {
      this.$emit('cancelarRegistro');
    }
  }
};
</script>
<style>
.clase {
  color: aliceblue;
}
</style>
