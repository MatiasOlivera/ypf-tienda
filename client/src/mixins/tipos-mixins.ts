import Vue, { ComponentOptions } from 'vue';

export type Mixin = ComponentOptions<Vue> | typeof Vue;

export default Mixin;
