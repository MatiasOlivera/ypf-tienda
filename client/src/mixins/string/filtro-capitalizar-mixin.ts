import capitalize from 'lodash/capitalize';
import Mixin from '../tipos-mixins';

const filtroCapitalizarMixin: Mixin = {
  filters: {
    /**
     * Convierte el primer caracter de la cadena a mayúsculas y el resto a minúsculas
     * @param valor
     */
    capitalizar(valor: string): string {
      return capitalize(valor);
    }
  }
};

export default filtroCapitalizarMixin;
