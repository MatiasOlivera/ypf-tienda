import { Respuesta } from '@/services/cliente-http';

export interface TokenDatos {
  autenticacion: {
    token: string;
    tipoToken: string;
    fechaExpiracion: string;
  };
}

export type RespuestaToken = Respuesta<true, 200, TokenDatos>;
