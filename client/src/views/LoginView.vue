<template>
  <main class="mt-5">
    <b-container>
      <b-row>
        <b-col cols="10" offset="1" md="6" offset-md="3" lg="4" offset-lg="4">
          <h1 class="h3">
            Iniciar sesión
          </h1>
        </b-col>
      </b-row>

      <b-row class="mt-5">
        <b-col cols="10" offset="1" md="6" offset-md="3" lg="4" offset-lg="4">
          <formulario-login
            :cargando="cargando"
            :mensaje="mensaje"
            :validacion.sync="validacion"
            @submit="loginLocal"
          ></formulario-login>
        </b-col>
      </b-row>
    </b-container>
  </main>
</template>

<script lang="ts">
import Vue from 'vue';
import { mapActions } from 'vuex';

// Componentes
import FormularioLogin, {
  validacionPorDefecto,
  PropCargando,
  PropMensaje,
  PropValidacion,
  EventoSubmit
} from '../components/FormularioLogin.vue';

// Store
import { MODULO_AUTENTICACION } from '../store/types/modulos';
import { LOGIN } from '../store/types/acciones';

// Router
import { rutaProductos } from '@/router/rutas';

interface Data {
  cargando: PropCargando;
  mensaje: PropMensaje;
  validacion: PropValidacion;
}

export default Vue.extend({
  name: 'LoginView',

  components: { FormularioLogin },

  data(): Data {
    return {
      cargando: false,
      mensaje: '',
      validacion: { ...validacionPorDefecto }
    };
  },

  methods: {
    ...mapActions(MODULO_AUTENTICACION, [LOGIN]),

    loginLocal(credenciales: EventoSubmit): void {
      this.cargando = true;

      // @ts-ignore
      this.login(credenciales)
        // @ts-ignore
        .then((respuesta) => {
          this.mensaje = '';
          this.validacion = { ...validacionPorDefecto };

          if (respuesta.ok) {
            this.$router.push({ name: rutaProductos });
          } else {
            if (respuesta.estado === 401 || respuesta.estado === 500) {
              this.mensaje = respuesta.datos.mensaje.descripcion;
            }

            if (respuesta.estado === 422) {
              this.validacion = respuesta.datos.errores;
            }
          }
        })
        .finally(() => {
          this.cargando = false;
        });
    }
  }
});
</script>
