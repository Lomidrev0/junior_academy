<template>
  <div class="card">
    <div class="card-body card-color txt-col-white d-flex justify-content-between">
      <i class="bi bi-info-circle-fill"></i>
      <span>{{ text }}</span>
    </div>
  </div>
</template>

<script>
export default {
  props: ['text']
}
</script>