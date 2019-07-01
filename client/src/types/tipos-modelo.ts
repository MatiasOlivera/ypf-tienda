/* eslint-disable camelcase */
export type Modelo = { id: ID } & Timestamps;

export type ID = number;

export interface Timestamps {
  created_at: string;
  updated_at: string | null;
  deleted_at: string | null;
}
