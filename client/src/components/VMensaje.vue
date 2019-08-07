<template>
  <div id="mensaje" class="d-flex flex-row align-items-center">
    <div class="container">
      <div class="row justify-content-center text-center">
        <div>
          <feather :type="icono" size="3em" :class="color" />

          <p class="lead">
            {{ descripcion }}
          </p>

          <b-button
            variant="outline-primary"
            class="mt-4"
            @click="clickVolverIntentar"
          >
            <feather type="refresh-cw" size="1em" class="icono-intentar" />
            Volver a intentar
          </b-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { PropValidator } from 'vue/types/options';

// Props
export type PropTipo = 'advertencia' | 'error';
export type PropDescripcion = string;

type MapeoTipos<Tipo> = { [Clave in PropTipo]: Tipo };

type Color = 'text-warning' | 'text-danger';

/**
 * Obtener un color de Bootstrap a partir de un tipo
 */
function tipoAColor(tipo: PropTipo): Color {
  const colores: MapeoTipos<Color> = {
    advertencia: 'text-warning',
    error: 'text-danger'
  };
  return colores[tipo];
}

type Icono = 'alert-triangle' | 'x-circle';

/**
 * Obtener un ícono de Feather a partir de un tipo
 */
function tipoAIcono(tipo: PropTipo): Icono {
  const iconos: MapeoTipos<Icono> = {
    advertencia: 'alert-triangle',
    error: 'x-circle'
  };
  return iconos[tipo];
}

export default Vue.extend({
  name: 'VMensaje',

  props: {
    tipo: {
      type: String,
      required: true
    } as PropValidator<PropTipo>,

    descripcion: {
      type: String,
      required: true
    } as PropValidator<PropDescripcion>
  },

  computed: {
    color(): Color {
      return tipoAColor(this.tipo);
    },

    icono(): Icono {
      return tipoAIcono(this.tipo);
    }
  },

  methods: {
    clickVolverIntentar(): void {
      this.$emit('clickVolverIntentar');
    }
  }
});
</script>

<style scoped>
#mensaje {
  min-height: 80vh;
}

.icono-intentar {
  vertical-align: middle;
  margin-right: 0.5em;
}
</style>
