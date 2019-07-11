import usarParametros, { EstadoParametros } from '..';

describe('Usar parámetros', () => {
  let estado: EstadoParametros;

  beforeEach(() => {
    estado = { cargando: false, parametros: {} };
  });

  describe('actions', () => {
    const { actions } = usarParametros('getProductos');

    let commit: any;
    let dispatch: any;

    beforeEach(() => {
      commit = jest.fn();
      dispatch = jest.fn();
    });

    test('ESTABLECER_BUSCAR', () => {
      // @ts-ignore
      const { establecerBuscar } = actions;

      establecerBuscar({ commit, dispatch }, 'semillas');

      expect(commit).toHaveBeenCalledWith('setBuscar', 'semillas');
      expect(commit).toHaveBeenCalledTimes(1);

      expect(dispatch).toHaveBeenCalledWith('getProductos');
      expect(dispatch).toHaveBeenCalledTimes(1);
    });

    test('ESTABLECER_ELIMINADOS', () => {
      // @ts-ignore
      const { establecerEliminados } = actions;

      establecerEliminados({ commit, dispatch }, true);

      expect(commit).toHaveBeenCalledWith('setEliminados', true);
      expect(commit).toHaveBeenCalledTimes(1);

      expect(dispatch).toHaveBeenCalledWith('getProductos');
      expect(dispatch).toHaveBeenCalledTimes(1);
    });

    test('ESTABLECER_PAGINA', () => {
      // @ts-ignore
      const { establecerPagina } = actions;

      establecerPagina({ commit, dispatch }, 2);

      expect(commit).toHaveBeenCalledWith('setPagina', 2);
      expect(commit).toHaveBeenCalledTimes(1);

      expect(dispatch).toHaveBeenCalledWith('getProductos');
      expect(dispatch).toHaveBeenCalledTimes(1);
    });

    test('ESTABLECER_POR_PAGINA', () => {
      // @ts-ignore
      const { establecerPorPagina } = actions;

      establecerPorPagina({ commit, dispatch }, 10);

      expect(commit).toHaveBeenCalledWith('setPorPagina', 10);
      expect(commit).toHaveBeenCalledTimes(1);

      expect(dispatch).toHaveBeenCalledWith('getProductos');
      expect(dispatch).toHaveBeenCalledTimes(1);
    });

    test('ESTABLECER_ORDENAR_POR', () => {
      // @ts-ignore
      const { establecerOrdenarPor } = actions;

      establecerOrdenarPor({ commit, dispatch }, 'nombre');

      expect(commit).toHaveBeenCalledWith('setOrdenarPor', 'nombre');
      expect(commit).toHaveBeenCalledTimes(1);

      expect(dispatch).toHaveBeenCalledWith('getProductos');
      expect(dispatch).toHaveBeenCalledTimes(1);
    });

    test('ESTABLECER_ORDEN', () => {
      // @ts-ignore
      const { establecerOrden } = actions;

      establecerOrden({ commit, dispatch }, 'asc');

      expect(commit).toHaveBeenCalledWith('setOrden', 'asc');
      expect(commit).toHaveBeenCalledTimes(1);

      expect(dispatch).toHaveBeenCalledWith('getProductos');
      expect(dispatch).toHaveBeenCalledTimes(1);
    });
  });

  describe('mutations', () => {
    const { mutations } = usarParametros('getProductos');

    describe('SET_CARGANDO', () => {
      test('debería establecer cargando como true', () => {
        // @ts-ignore
        const { setCargando } = mutations;
        setCargando(estado, true);
        expect(estado.cargando).toBe(true);
      });

      test('debería establecer cargando como false', () => {
        // @ts-ignore
        const { setCargando } = mutations;
        setCargando(estado, false);
        expect(estado.cargando).toBe(false);
      });
    });

    describe('SET_BUSCAR', () => {
      test('debería establecer el valor buscado', () => {
        // @ts-ignore
        const { setBuscar } = mutations;
        setBuscar(estado, 'semillas');
        expect(estado.parametros.buscar).toBe('semillas');
      });

      test('debería quitar el valor buscado', () => {
        // @ts-ignore
        const { setBuscar } = mutations;
        setBuscar(estado, undefined);
        expect(estado.parametros.buscar).toBeUndefined();
      });
    });

    describe('SET_ELIMINADOS', () => {
      test('debería establecer eliminados como true', () => {
        // @ts-ignore
        const { setEliminados } = mutations;
        setEliminados(estado, true);
        expect(estado.parametros.eliminados).toBe(true);
      });

      test('debería establecer eliminados como false', () => {
        // @ts-ignore
        const { setEliminados } = mutations;
        setEliminados(estado, false);
        expect(estado.parametros.eliminados).toBe(false);
      });
    });

    test('SET_PAGINA: debería establecer la página', () => {
      // @ts-ignore
      const { setPagina } = mutations;
      setPagina(estado, 1);
      expect(estado.parametros.pagina).toBe(1);
    });

    test('SET_POR_PAGINA: debería establecer la cantidad de registros por página', () => {
      // @ts-ignore
      const { setPorPagina } = mutations;
      setPorPagina(estado, 10);
      expect(estado.parametros.porPagina).toBe(10);
    });

    test('SET_ORDENAR_POR: debería establecer el campo utilizado para ordenar los registros', () => {
      // @ts-ignore
      const { setOrdenarPor } = mutations;
      setOrdenarPor(estado, 'nombre');
      expect(estado.parametros.ordenarPor).toBe('nombre');
    });

    describe('SET_ORDEN', () => {
      test('debería establecer el orden como ascendente', () => {
        // @ts-ignore
        const { setOrden } = mutations;
        setOrden(estado, 'asc');
        expect(estado.parametros.orden).toBe('asc');
      });

      test('debería establecer el orden como descendente', () => {
        // @ts-ignore
        const { setOrden } = mutations;
        setOrden(estado, 'desc');
        expect(estado.parametros.orden).toBe('desc');
      });
    });
  });
});
