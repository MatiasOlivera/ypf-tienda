<template>
  <b-pagination
    :value="paginaActual"
    :limit="5"
    :total-rows="total"
    :per-page="porPagina"
    :disabled="pocosRegistros"
    label-first-page="Ir a la primera página"
    label-prev-page="Ir a la página anterior"
    label-next-page="Ir a la siguiente página"
    label-last-page="Ir a la última página"
    label-page="Ir a la página"
    aria-label="Paginación"
    @change="cambio"
  >
  </b-pagination>
</template>

<script lang="ts">
import Vue from 'vue';
import { PropValidator } from 'vue/types/options';

// Props
export type PropPaginaActual = number;
export type PropTotal = number;
export type PropPorPagina = number;

export default Vue.extend({
  name: 'VPaginacion',

  props: {
    paginaActual: {
      type: Number,
      required: true
    } as PropValidator<PropPaginaActual>,

    total: {
      type: Number,
      required: true
    } as PropValidator<PropTotal>,

    porPagina: {
      type: Number,
      required: true
    } as PropValidator<PropPorPagina>
  },

  computed: {
    pocosRegistros(): boolean {
      return this.total <= this.porPagina;
    }
  },

  methods: {
    cambio(pagina: number): void {
      this.$emit('cambio', pagina);
    }
  }
});
</script>

<style scoped></style>
