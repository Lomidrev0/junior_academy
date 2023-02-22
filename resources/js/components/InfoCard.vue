<template>
  <div class="card">
    <div :class="!type || type ==='info'? 'card-inf':'card-war'" class="card-body card-color txt-col-white d-flex justify-content-between">
      <i v-if="type === 'info' || !type" class="bi bi-info-circle-fill"></i>
      <i v-if="type === 'war'" class="bi bi-exclamation-triangle-fill"></i>
      <span>{{ text }}</span>
    </div>
  </div>
</template>

<script>
export default {
  props: ['text', 'type']
}
</script>