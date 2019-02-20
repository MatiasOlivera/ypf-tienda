import Mixin from './tipos-mixins';

const formatoCuitMixin: Mixin = {
  filters: {
    formatoCuit(valor: number | string): string {
      if (!valor) return '';

      const caracter: string = 'x';
      const cuit: string = typeof valor === 'number' ? valor.toString() : valor;

      const tipo: string = cuit.substring(0, 2).padEnd(2, caracter);
      const numero: string = cuit.substring(2, 10).padEnd(8, caracter);
      const digitoVerificador: string = cuit
        .substring(10, 11)
        .padEnd(1, caracter);

      return `${tipo}-${numero}-${digitoVerificador}`;
    }
  }
};

export default formatoCuitMixin;
