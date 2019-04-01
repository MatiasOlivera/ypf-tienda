import dayjs from 'dayjs';
import utc from 'dayjs/plugin/utc';

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

  public esPosibleRenovarToken(): Renovacion {
    const fecha = this.getFechaExpiracion();
    if (fecha === null) return 'LOGIN';

    dayjs.extend(utc);

    const fechaActual = dayjs();
    const fechaExpiracion = dayjs(fecha);
    const renovarAPartirDe = fechaExpiracion.clone().subtract(30, 'minute');

    if (fechaActual.isBefore(renovarAPartirDe)) {
      return 'NO_RENOVAR';
    }

    if (
      fechaActual.isAfter(renovarAPartirDe) &&
      fechaActual.isBefore(fechaExpiracion)
    ) {
      return 'RENOVAR';
    }

    return 'LOGIN';
  }

  public limpiar(): void {
    this.almacen.eliminarItem('token');
    this.almacen.eliminarItem('fecha-expiracion');
  }

  private getFechaExpiracion(): string | null {
    return this.almacen.getItem('fecha-expiracion');
  }
}

type Renovacion = 'NO_RENOVAR' | 'RENOVAR' | 'LOGIN';

export default ServicioToken;
