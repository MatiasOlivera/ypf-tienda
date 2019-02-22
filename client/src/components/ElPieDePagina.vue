<template>
  <footer class="bg-light border-top mt-5">
    <b-container>
      <b-row class="mb-5">
        <b-col class="d-flex justify-content-center">
          <img
            id="logotipo"
            src="../static/images/logotipo.png"
            alt="Logotipo"
          />
        </b-col>
      </b-row>

      <b-row class="text-center">
        <b-col cols="12" md="3">
          <p>{{ empresa.razonSocial }}<br />{{ empresa.cuit | formatoCuit }}</p>
        </b-col>

        <b-col cols="12" md="3">
          <address>
            <ul class="list-unstyled">
              <li v-for="(telefono, indice) in empresa.telefonos" :key="indice">
                <a :href="`tel:+${telefono}`">
                  {{ telefono | formatoTelefono }}
                </a>
              </li>
            </ul>
          </address>
        </b-col>

        <b-col cols="12" md="3">
          <address>
            <a :href="`mailto:${empresa.email}`">
              {{ empresa.email }}
            </a>
          </address>
        </b-col>

        <b-col cols="12" md="3">
          <p>
            {{ empresa.direccion.calle }} <br />
            {{ empresa.direccion.localidad }}, {{ empresa.direccion.provincia }}
          </p>
        </b-col>
      </b-row>
    </b-container>
  </footer>
</template>

<script lang="ts">
import Vue from 'vue';
import empresa from '../config/empresa';
import { formatoCuitMixin, formatoTelefonoMixin } from '../mixins';

export default Vue.extend({
  name: 'ElPieDePagina',
  mixins: [formatoCuitMixin, formatoTelefonoMixin],
  data: () => {
    return { empresa };
  }
});
</script>

<style lang="scss" scoped>
@import '~/bootstrap/scss/bootstrap.scss';

footer {
  color: $gray-600;
  padding: 40px 0;
}

a,
a:hover {
  text-decoration: underline;
  color: $gray-600;
}

#logotipo {
  height: 40px;
  filter: grayscale(100%) opacity(0.5);
  transition: all 0.5s ease;
}

#logotipo:hover {
  filter: grayscale(0%) opacity(1);
}
</style>
