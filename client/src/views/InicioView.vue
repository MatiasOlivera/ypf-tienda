<template>
  <main>
    <div
      id="hero"
      class="jumbotron jumbotron-fluid d-flex align-items-center mb-5"
    >
      <b-container>
        <b-row>
          <b-col id="hero__contenido" md="6" offset-md="6">
            <h1 id="hero__titulo" class="text-white text-center text-md-right">
              Venta de insumos para desarrollar la actividad agropecuaria
            </h1>

            <div class="d-flex justify-content-center justify-content-md-end">
              <b-button variant="success" size="lg" href="#">
                Ver productos
              </b-button>
            </div>
          </b-col>
        </b-row>
      </b-container>

      <svg
        id="hero__linea-inclinada"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 100 100"
        preserveAspectRatio="none"
      >
        <polygon fill="white" points="0,100 100,0 100,100" />
      </svg>
    </div>

    <b-container>
      <b-row class="py-5">
        <b-col md="8" offset-md="2">
          <p class="text-center text-muted">
            YPF DIRECTO es el canal de venta integral de YPF en donde el
            productor agropecuario o el empresario pyme encuentra todos los
            productos para desarrollar su actividad, más el soporte técnico y el
            asesoramiento necesario para solucionar sus necesidades.
          </p>
        </b-col>
      </b-row>

      <b-row class="py-5">
        <b-col class="text-center">
          <h2 class="titulo text-uppercase">
            Productos y servicios
          </h2>
        </b-col>
      </b-row>

      <b-row class="pb-1">
        <b-col
          v-for="(categoria, indice) in categorias"
          :key="indice"
          cols="12"
          md="6"
          xl="4"
        >
          <b-card
            :title="categoria.nombre"
            title-tag="p"
            :img-src="categoria.rutaImagen"
            :img-alt="categoria.nombre"
            img-top
            align="center"
            class="tarjeta-categoria mb-4"
            :class="{
              'd-none d-sm-block': indice >= 3 && !verTodasLasCategorias
            }"
          />
        </b-col>
      </b-row>

      <b-row class="pb-5">
        <b-col class="d-flex justify-content-center d-block d-sm-none">
          <b-button
            v-if="!verTodasLasCategorias"
            variant="outline-secondary"
            @click="mostrarCategorias"
          >
            Ver más
          </b-button>
        </b-col>
      </b-row>
    </b-container>
  </main>
</template>

<script lang="ts">
import Vue from 'vue';

import asesoramiento from '../static/images/categorias/asesoramiento-tecnico.jpg';
import muestreoDeSuelo from '../static/images/categorias/muestreo-suelo.jpg';
import canjeDeGranos from '../static/images/categorias/canje-de-granos.jpg';
import lubricantes from '../static/images/categorias/lubricantes.jpg';
import combustibles from '../static/images/categorias/combustibles.jpg';
import grasas from '../static/images/categorias/grasas.jpg';
import fitosanitarios from '../static/images/categorias/fitosanitarios.jpg';
import fertilizantes from '../static/images/categorias/fertilizantes.jpg';
import plasticos from '../static/images/categorias/plasticos.jpg';
import servicioCapilar from '../static/images/categorias/servicio-capilar.jpg';

export default Vue.extend({
  name: 'InicioView',
  data: (): Data => {
    return {
      categorias: [
        {
          nombre: 'Asesoramiento técnico',
          rutaImagen: asesoramiento
        },
        {
          nombre: 'Muestreo de suelo',
          rutaImagen: muestreoDeSuelo
        },
        {
          nombre: 'Canje de granos',
          rutaImagen: canjeDeGranos
        },
        {
          nombre: 'Lubricantes y especialidades',
          rutaImagen: lubricantes
        },
        {
          nombre: 'Combustibles',
          rutaImagen: combustibles
        },
        {
          nombre: 'Grasas',
          rutaImagen: grasas
        },
        {
          nombre: 'Fitosanitarios',
          rutaImagen: fitosanitarios
        },
        {
          nombre: 'Fertilizantes',
          rutaImagen: fertilizantes
        },
        {
          nombre: 'Plásticos',
          rutaImagen: plasticos
        },
        {
          nombre: 'Servicio capilar',
          rutaImagen: servicioCapilar
        }
      ],
      verTodasLasCategorias: false
    };
  },
  methods: {
    mostrarCategorias(): void {
      this.verTodasLasCategorias = true;
    }
  }
});

interface Data {
  verTodasLasCategorias: boolean;
  categorias: Array<Categoria>;
}

interface Categoria {
  nombre: string;
  rutaImagen: string;
}
</script>

<style lang="scss" scoped>
@import '../static/styles/personalizado.scss';

#hero {
  position: relative;
  height: 90vh;
  background-image: url('../static/images/oficina.jpg');
  background-size: cover;
  background-position: center center;
  background-attachment: fixed;
}

#hero__contenido {
  margin-bottom: 10vh;
}

#hero__titulo {
  font-size: 3rem;
  text-shadow: 0px 2px 10px rgba($color: #000000, $alpha: 0.5);
}

#hero__linea-inclinada {
  position: absolute;
  bottom: 0;
  width: 100%;
  height: 10vw;
}

.tarjeta-categoria {
  transition: all ease 0.5s;
  top: 0;
}

.tarjeta-categoria:hover {
  // Clase shadow de Bootstrap
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
  top: -10px;
}
</style>
