<template>
  <b-card
    id="tarjeta-producto"
    :img-src="imagen"
    :img-alt="nombre"
    img-height="256"
    img-width="256"
    @mouseover="hoverInicio"
    @mouseleave="hoverFin"
  >
    <h2 class="h6 text-dark">
      {{ nombre }}
    </h2>

    <b-card-text v-if="presentacion" class="text-muted">
      {{ presentacion | capitalizar }}
    </b-card-text>

    <div class="d-flex justify-content-between">
      <b-button
        id="agregar-favorito"
        title="Agregar a favoritos"
        variant="outline-secondary"
        class="d-flex align-items-center"
        :class="[hover ? 'mostrar-icono' : 'ocultar-icono']"
        @click="clickAgregarFavorito"
      >
        <feather type="heart" size="1em" />
      </b-button>

      <b-button
        id="agregar-carrito"
        title="Agregar al carrito"
        variant="outline-primary"
        class="d-flex align-items-center"
        @click="clickAgregarCarrito"
      >
        <feather type="shopping-cart" size="1em" />
      </b-button>
    </div>
  </b-card>
</template>

<script lang="ts">
import Vue from 'vue';
import { PropValidator } from 'vue/types/options';

// Mixins
import filtroCapitalizarMixin from '@/mixins/string/filtro-capitalizar-mixin';

// Props
export type PropNombre = string;
export type PropPresentacion = string;
export type PropImagen = string;

interface Data {
  hover: boolean;
}

export default Vue.extend({
  name: 'TarjetaProducto',

  mixins: [filtroCapitalizarMixin],

  props: {
    nombre: {
      type: String,
      required: true
    } as PropValidator<PropNombre>,

    presentacion: {
      type: String,
      required: true
    } as PropValidator<PropPresentacion>,

    imagen: {
      type: String,
      default: ''
    } as PropValidator<PropImagen>
  },

  data(): Data {
    return {
      hover: false
    };
  },

  methods: {
    hoverInicio(): void {
      this.hover = true;
    },

    hoverFin(): void {
      this.hover = false;
    },

    clickAgregarFavorito(): void {
      this.$emit('clickAgregarFavorito');
    },

    clickAgregarCarrito(): void {
      this.$emit('clickAgregarCarrito');
    }
  }
});
</script>

<style lang="scss" scoped>
@import '../../static/styles/variables.scss';

h2 {
  font-family: $font-family-base;
}

#tarjeta-producto {
  transition: all ease 0.5s;
}

#tarjeta-producto:hover {
  // Clase shadow de Bootstrap
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.mostrar-icono {
  opacity: 1;
  transition: opacity ease 0.5s;
}

.ocultar-icono {
  opacity: 0;
}
</style>
