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
          :state="validacion.email.esValido"
          :disabled="cargando"
          type="email"
          required
        ></b-form-input>

        <b-form-invalid-feedback id="email-input-error">
          {{ validacion.email.error }}
        </b-form-invalid-feedback>
      </b-form-group>

      <b-form-group label="Contraseña" label-for="password-input">
        <b-form-input
          id="password-input"
          v-model="credenciales.password"
          :state="validacion.password.esValido"
          :disabled="cargando"
          type="password"
          required
        ></b-form-input>

        <b-form-invalid-feedback id="password-input-error">
          {{ validacion.password.error }}
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

// Tipos
import { CredencialesUsuario } from '../types/tipos-auth';
import {
  ErroresValidacion,
  ErroresValidacionInicial
} from '../types/respuesta-tipos';

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
}

// Valores por defecto
const credencialesPorDefecto: CredencialesUsuario = {
  email: '',
  password: ''
};

type ValidacionPorDefecto = ErroresValidacionInicial<CredencialesUsuario>;

const validacionPorDefecto: ValidacionPorDefecto = {
  email: {
    esValido: null,
    error: null
  },
  password: {
    esValido: null,
    error: null
  }
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
    } as PropValidator<PropValidacion | ValidacionPorDefecto>
  },

  data(): Data {
    return {
      credenciales: { ...credencialesPorDefecto }
    };
  },

  methods: {
    submit(): void {
      this.$emit('submit', this.credenciales);
    },

    resetear(): void {
      this.credenciales = { ...credencialesPorDefecto };
      this.$emit('update:validacion', { ...validacionPorDefecto });
    }
  }
});
</script>
