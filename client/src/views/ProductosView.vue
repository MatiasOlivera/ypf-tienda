<template>
  <b-container tag="main" class="mt-5">
    <VCargando
      :esta-cargando="estadoEsPendiente"
      :pantalla-completa="true"
    ></VCargando>

    <VMensaje
      v-if="estadoEsMensaje"
      :tipo="mensaje.tipo"
      :descripcion="mensaje.descripcion"
      @clickVolverIntentar="obtenerProductos"
    >
    </VMensaje>

    <b-row>
      <b-col lg="4" tag="aside"></b-col>

      <b-col lg="8">
        <b-row v-if="mostrarProductos">
          <b-col
            v-for="producto in productos"
            :key="producto.id"
            sm="12"
            md="6"
            lg="4"
            class="mb-4"
          >
            <TarjetaProducto
              :nombre="producto.nombre"
              :presentacion="producto.presentacion"
              :imagen="producto.imagen"
            ></TarjetaProducto>
          </b-col>
        </b-row>

        <b-row v-if="estadoEsProductos && !hayProductos">
          <b-col>
            <p>
              No se encontró ningún producto que coincida con la palabra buscada
              o con los filtros aplicados
            </p>
          </b-col>
        </b-row>
      </b-col>
    </b-row>
  </b-container>
</template>

<script lang="ts">
/* eslint-disable object-shorthand */
/* eslint-disable func-names */
import Vue from 'vue';
import { mapState, mapGetters, mapActions } from 'vuex';
import isEmpty from 'lodash/isEmpty';

// Componentes
import VCargando from '../components/VCargando.vue';
import VMensaje from '../components/VMensaje.vue';
import TarjetaProducto from '../components/productos/TarjetaProducto.vue';

// Store
import { MODULO_PRODUCTOS } from '../store/types/modulos';
import { OBTENER_PRODUCTOS } from '../store/types/acciones';

export default Vue.extend({
  name: 'ProductosView',

  components: { VCargando, VMensaje, TarjetaProducto },

  computed: {
    ...mapState(MODULO_PRODUCTOS, ['productos', 'mensaje']),
    ...mapGetters(MODULO_PRODUCTOS, [
      'estadoEsPendiente',
      'estadoEsProductos',
      'estadoEsValidacion',
      'estadoEsMensaje'
    ]),

    mostrarProductos(): boolean {
      return (
        ((this.estadoEsPendiente as unknown) as boolean) ||
        ((this.estadoEsValidacion as unknown) as boolean) ||
        (((this.estadoEsProductos as unknown) as boolean) && this.hayProductos)
      );
    },

    hayProductos(): boolean {
      return !isEmpty(this.productos);
    }
  },

  created() {
    if (!this.hayProductos) {
      this.obtenerProductos().catch(() => {});
    }
  },

  methods: {
    ...mapActions(MODULO_PRODUCTOS, [OBTENER_PRODUCTOS])
  }
});
</script>

<style scoped></style>
