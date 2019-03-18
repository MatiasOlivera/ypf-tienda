export class Almacen implements ServicioAlmacenamiento {
  constructor(private storage: Storage = localStorage) {
    this.storage = storage;
  }

  public getItem(clave: string): string | null {
    return this.storage.getItem(clave);
  }

  public setItem(clave: string, valor: string): boolean {
    this.storage.setItem(clave, valor);
    return this.storage.getItem(clave) === valor;
  }

  public eliminarItem(clave: string): boolean {
    this.storage.removeItem(clave);
    return this.storage.getItem(clave) === null;
  }

  public limpiar(): void {
    this.storage.clear();
  }
}

export interface ServicioAlmacenamiento {
  getItem(clave: string): string | null;
  setItem(clave: string, valor: string): boolean;
  eliminarItem(clave: string): boolean;
  limpiar(): void;
}

export default Almacen;
