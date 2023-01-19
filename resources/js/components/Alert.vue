<template>
  <div class="alert alert-danger my-3 mx-4 d-flex justify-content-between" role="alert">
    <div>
      {{ error }}
    </div>
    <div @click="close()" class="d-md-inline">
      <i class="bi bi-x-lg"></i>
    </div>
  </div>
</template>

<script>
export default {
  props: ['error'],
  methods: {
    close() {
      this.$emit('close');
    }
  }
}
</script>
