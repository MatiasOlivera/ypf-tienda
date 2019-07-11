<template>
  <b-form @submit.prevent="buscar">
    <b-input-group>
      <b-form-input
        id="busqueda"
        v-model="valorBuscado"
        :disabled="cargando"
        type="search"
        placeholder="¿Qué producto estás buscando?"
        autofocus
      ></b-form-input>

      <b-input-group-append>
        <b-button
          :disabled="cargando"
          type="submit"
          variant="outline-primary"
          class="d-flex align-items-center"
        >
          <template v-if="cargando">
            <b-spinner small variant="primary" label="Cargando"></b-spinner>
          </template>

          <template v-else>
            <feather type="search" size="1em" />
          </template>
        </b-button>
      </b-input-group-append>
    </b-input-group>
  </b-form>
</template>

<script lang="ts">
/* eslint-disable object-shorthand */
/* eslint-disable func-names */
import Vue from 'vue';
import { PropValidator } from 'vue/types/options';

// Props
export type PropValorBuscadoAnterior = string;
export type PropCargando = boolean;

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
    } as PropValidator<PropValorBuscadoAnterior>,

    cargando: {
      type: Boolean,
      default: false
    } as PropValidator<PropCargando>
  },

  data(): Data {
    return {
      valorBuscado: ''
    };
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
