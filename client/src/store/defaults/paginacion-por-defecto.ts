import { Paginacion } from '@/types/respuesta-tipos';

// eslint-disable-next-line import/prefer-default-export
export const paginacionPorDefecto: Paginacion = {
  total: 0,
  porPagina: 0,
  paginaActual: 1,
  ultimaPagina: 1,
  desde: null,
  hasta: null,
  rutas: {
    primeraPagina: '',
    ultimaPagina: '',
    siguientePagina: null,
    paginaAnterior: null,
    base: ''
  }
};
