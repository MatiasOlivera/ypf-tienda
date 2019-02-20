import Mixin from './tipos-mixins';

const formatoTelefonoMixin: Mixin = {
  filters: {
    formatoTelefono(valor: number): string {
      if (!valor) return '';

      const telefono: string = valor.toString();

      const codigoArea: string = telefono.substring(2, 6);
      const numero: string = telefono.substring(6);

      return `${codigoArea} ${numero}`;
    }
  }
};

export default formatoTelefonoMixin;
