<template>
  <b-button :disabled="cargando" type="submit" variant="outline-primary">
    <template v-if="cargando">
      <b-spinner small variant="primary" label="Cargando"></b-spinner>
      Cargando
    </template>

    <template v-else>
      {{ texto }}
    </template>
  </b-button>
</template>

<script lang="ts">
import Vue from 'vue';
import { PropValidator } from 'vue/types/options';

export type SubmitPropCargando = boolean;
export type SubmitPropTexto =
  | 'Iniciar sesión'
  | 'Guardar'
  | 'Actualizar'
  | 'Enviar';

const texto: SubmitPropTexto[] = [
  'Iniciar sesión',
  'Guardar',
  'Actualizar',
  'Enviar'
];

export default Vue.extend({
  name: 'BotonSubmit',

  props: {
    cargando: {
      type: Boolean,
      default: false
    } as PropValidator<SubmitPropCargando>,

    texto: {
      type: String,
      default: 'Guardar',
      validator: (valor) => texto.includes(valor)
    } as PropValidator<SubmitPropTexto>
  }
});
</script>
