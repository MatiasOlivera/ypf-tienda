import { Almacen, ServicioAlmacenamiento } from './almacen-servicio';

export class ServicioToken {
  constructor(private almacen: ServicioAlmacenamiento = new Almacen()) {
    this.almacen = almacen;
  }

  public setToken(tipo: string, token: string): boolean {
    return this.almacen.setItem('token', `${tipo} ${token}`);
  }

  public getToken(): string | null {
    return this.almacen.getItem('token');
  }

  public setFechaExpiracion(fecha: string): boolean {
    return this.almacen.setItem('fecha-expiracion', fecha);
  }

  public esPosibleRenovarToken(): boolean {
    const fecha = this.getFechaExpiracion();
    if (fecha === null) return false;

    const fechaActual = new Date();
    const fechaExpiracion = new Date(fecha);

    return fechaActual <= fechaExpiracion;
  }

  public limpiar(): void {
    this.almacen.eliminarItem('token');
    this.almacen.eliminarItem('fecha-expiracion');
  }

  private getFechaExpiracion(): string | null {
    return this.almacen.getItem('fecha-expiracion');
  }
}

export default ServicioToken;
