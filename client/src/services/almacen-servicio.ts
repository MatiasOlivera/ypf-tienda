import { Diccionario } from '@/types/utilidades';

export class Almacen implements ServicioAlmacenamiento {
  constructor(private storage: Storage = localStorage) {
    this.storage = storage;
  }

  public getItem<Tipo extends ItemGetter>(clave: string): Tipo | null {
    const item = this.storage.getItem(clave);
    if (item === null) {
      return item;
    }
    return this.cadenaATipo(item) as Tipo;
  }

  public setItem(clave: string, valor: ItemSetter): boolean {
    const itemCadena = this.tipoACadena(valor);
    this.storage.setItem(clave, itemCadena);
    return this.storage.getItem(clave) !== null;
  }

  public eliminarItem(clave: string): boolean {
    this.storage.removeItem(clave);
    return this.storage.getItem(clave) === null;
  }

  public limpiar(): void {
    this.storage.clear();
  }

  // eslint-disable-next-line class-methods-use-this
  private tipoACadena(valor: ItemGetter): string {
    if (typeof valor === 'string') {
      return valor;
    }
    return JSON.stringify(valor);
  }

  // eslint-disable-next-line class-methods-use-this
  private cadenaATipo(valor: string): ItemGetter {
    try {
      return JSON.parse(valor);
    } catch (error) {
      return valor;
    }
  }
}

type ItemSetter = string | boolean | number | Array<any> | Diccionario<any>;
type ItemGetter = ItemSetter | null;

export interface ServicioAlmacenamiento {
  getItem<Tipo extends ItemGetter>(clave: string): Tipo | null;
  setItem(clave: string, valor: ItemGetter): boolean;
  eliminarItem(clave: string): boolean;
  limpiar(): void;
}

export default Almacen;
