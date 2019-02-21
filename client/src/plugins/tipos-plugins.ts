import { PluginFunction, PluginObject } from 'vue';

// eslint-disable-next-line import/prefer-default-export
export interface ConfiguracionPlugin<T = any> {
  plugin: PluginObject<T> | PluginFunction<T>;
  options?: T;
}
