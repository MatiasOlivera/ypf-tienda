<template>
  <div>
    <p v-if="mensaje" class="text-danger">
      {{ mensaje }}
    </p>

    <b-form novalidate @submit.prevent="submit" @reset="resetear">
      <b-form-group label="Email" label-for="email-input">
        <b-form-input
          id="email-input"
          v-model="credenciales.email"
          :state="validacionEstado.email"
          :disabled="cargando"
          type="email"
          required
        ></b-form-input>

        <b-form-invalid-feedback id="email-input-error">
          {{ validacionLocal.email }}
        </b-form-invalid-feedback>
      </b-form-group>

      <b-form-group label="Contraseña" label-for="password-input">
        <b-form-input
          id="password-input"
          v-model="credenciales.password"
          :state="validacionEstado.password"
          :disabled="cargando"
          type="password"
          required
        ></b-form-input>

        <b-form-invalid-feedback id="password-input-error">
          {{ validacionLocal.password }}
        </b-form-invalid-feedback>
      </b-form-group>

      <div class="mt-4">
        <boton-submit
          :cargando="cargando"
          texto="Iniciar sesión"
        ></boton-submit>

        <boton-resetear :disabled="cargando" />
      </div>
    </b-form>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { PropValidator } from 'vue/types/options';
import {
  obtenerEstadoValidacion,
  EstadoValidacion
} from './formulario/utilidades';

// Tipos
import { CredencialesUsuario } from '../types/tipos-auth';
import { ErroresValidacion } from '../types/respuesta-tipos';

// Componentes
import BotonSubmit from './formulario/BotonSubmit.vue';
import BotonResetear from './formulario/BotonResetear.vue';

// Props
export type PropCargando = boolean;
export type PropMensaje = string;
export type PropValidacion = ErroresValidacion<CredencialesUsuario>;

// Eventos
export type EventoSubmit = CredencialesUsuario;

// Data
interface Data {
  credenciales: CredencialesUsuario;
  validacionLocal: PropValidacion;
}

// Valores por defecto
const credencialesPorDefecto: CredencialesUsuario = {
  email: '',
  password: ''
};
export const validacionPorDefecto: PropValidacion = {
  email: null,
  password: null
};

export default Vue.extend({
  components: { BotonSubmit, BotonResetear },

  props: {
    cargando: {
      type: Boolean,
      default: false
    } as PropValidator<PropCargando>,

    mensaje: {
      type: String,
      default: ''
    } as PropValidator<PropMensaje>,

    validacion: {
      type: Object,
      default: () => ({ ...validacionPorDefecto })
    } as PropValidator<PropValidacion>
  },

  data(): Data {
    return {
      credenciales: { ...credencialesPorDefecto },
      validacionLocal: { ...validacionPorDefecto }
    };
  },

  computed: {
    validacionEstado(): EstadoValidacion<CredencialesUsuario> {
      return obtenerEstadoValidacion<CredencialesUsuario>(this.validacionLocal);
    }
  },

  watch: {
    validacion(nuevosErrores) {
      this.validacionLocal = nuevosErrores;
    }
  },

  methods: {
    submit(): void {
      this.$emit('submit', this.credenciales);
    },

    resetear(): void {
      this.credenciales = { ...credencialesPorDefecto };
      this.validacionLocal = { ...validacionPorDefecto };
    }
  }
});
</script>
