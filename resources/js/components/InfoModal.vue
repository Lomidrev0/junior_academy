<template>
  <b-modal @hide="emitClose" size="lg" scrollable visible no-close-on-backdrop hide-footer>
    <template #modal-header="{ close }">
      <div class="position-relative d-flex w-available">
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
<!--        <h5>{{ header }}</h5>-->
      </div>
      <i @click="close()" class="bi bi-x-lg"></i>
    </template>
    <div class="m-modal-body">
      <div class="w-100 m-auto">
        <slot></slot>
      </div>
    </div>
  </b-modal>
</template>

<script>
export default {
  methods: {
    emitClose() {
      setTimeout(() => {
        this.$emit('close');
      }, 300);
    },
  }

}
</script>

<style scoped>

</style>