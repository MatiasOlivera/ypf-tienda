import { Respuesta } from '@/services/cliente-http';

export interface TokenDatos {
  token: string;
  tipoToken: string;
  fechaExpiracion: string;
}

export type RespuestaToken = Respuesta<true, 200, TokenDatos>;
