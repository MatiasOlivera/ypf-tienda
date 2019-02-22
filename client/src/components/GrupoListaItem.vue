<template>
  <b-list-group-item id="item" class="d-flex justify-content-between">
    <b-col class="d-flex align-items-center">
      <feather :type="icono" size="1em" />
    </b-col>
    <b-col cols="10" class="d-flex align-items-center">
      <slot />
    </b-col>
  </b-list-group-item>
</template>

<script lang="ts">
import Vue from 'vue';

export default Vue.extend({
  name: 'GrupoListaItem',
  props: {
    icono: {
      type: String,
      required: true
    }
  }
});
</script>

<style lang="scss" scoped>
@import '~/bootstrap/scss/bootstrap.scss';

#item {
  transition: all ease 0.5s;
}

#item:hover {
  @extend .shadow;
  transform: scale(1.1);
}
</style>
