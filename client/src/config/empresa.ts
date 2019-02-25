export const nombreFantasia: string = 'YPF Directo';
export const razonSocial: string = 'Agroservicios Correntinos SRL';
export const cuit: number = 30708405538;

export const direccion: Direccion = {
  calle: 'Ruta Nacional N119, KM 108.3',
  localidad: 'Mercedes',
  provincia: 'Corrientes'
};

export const telefonos: Array<number> = [543773529218, 543774633944];
export const email: string = 'info@ascsrl.com.ar';

export interface Direccion {
  calle: string;
  localidad: string;
  provincia: string;
}
export default {
  nombreFantasia,
  razonSocial,
  cuit,
  direccion,
  telefonos,
  email
};
