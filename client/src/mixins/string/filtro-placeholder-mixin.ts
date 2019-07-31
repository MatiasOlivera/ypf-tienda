import Mixin from '../tipos-mixins';

type Valor = string | number | undefined | null;

const filtroPlaceholderMixin: Mixin = {
  filters: {
    /**
     * Si no existe el valor, mostrar el placeholder (texto alternativo)
     * @param valor
     */
    placeholder(valor: Valor, texto: string): string | number {
      return valor === undefined || valor === null || valor === ''
        ? texto
        : valor;
    }
  }
};

export default filtroPlaceholderMixin;
