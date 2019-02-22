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
  </main>
</template>

<script lang="ts">
import Vue from 'vue';

export default Vue.extend({
  name: 'InicioView'
});
</script>

<style lang="scss" scoped>
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
</style>
