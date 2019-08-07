<template>
  <b-form @submit.prevent="buscar">
    <b-input-group>
      <b-form-input
        id="busqueda"
        v-model="valorBuscado"
        type="search"
        placeholder="¿Qué producto estás buscando?"
        autofocus
      ></b-form-input>

      <b-input-group-append>
        <b-button
          :disabled="esIgual"
          type="submit"
          variant="outline-primary"
          class="d-flex align-items-center"
        >
          <feather type="search" size="1em" />
        </b-button>
      </b-input-group-append>
    </b-input-group>
  </b-form>
</template>

<script lang="ts">
import Vue from 'vue';
import { PropValidator } from 'vue/types/options';

// Props
export type PropValorBuscadoAnterior = string;

// Eventos
export type EventoBuscar = string;

interface Data {
  valorBuscado: string;
}

export default Vue.extend({
  name: 'BuscadorProducto',

  props: {
    valorBuscadoAnterior: {
      type: String,
      default: ''
    } as PropValidator<PropValorBuscadoAnterior>
  },

  data(): Data {
    return {
      valorBuscado: ''
    };
  },

  computed: {
    esIgual(): boolean {
      return this.valorBuscado === this.valorBuscadoAnterior;
    }
  },

  created() {
    this.valorBuscado = this.valorBuscadoAnterior;
  },

  methods: {
    buscar(): void {
      this.$emit('buscar', this.valorBuscado);
    }
  }
});
</script>

<style scoped></style>
