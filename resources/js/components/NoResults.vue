<template>
  <div class="no-results w-100">
    <img class="" src="/images/sad_cactus.png" alt="">
    <div class="text-center">
      <h3>{{header+' :('}}</h3>
      <p class="m-auto">{{body}}</p>
    </div>
  </div>
</template>

<script>
export default {
  props:['header','body'],
}
</script>

<style scoped>

</style>