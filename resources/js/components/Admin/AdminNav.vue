<template>
  <nav class="side-nav">
    <ul>
      <li>
        <div class="open-nav d-flex justify-content-end">
          <button class="btn" @click="show = true" >
            <i class="bi bi-caret-left-fill" v-if="show===false"></i>
            <i class="bi bi-caret-right-fill" v-else></i>
          </button>
          {{show}}
        </div>
      </li>
      <li>
        <a href="">
          <i class="bi bi-house-fill"></i>
          {{i18n('Home')}}
        </a>
      </li>
      <li>
        <a href="">
          <i class="bi bi-wrench"></i>
          {{i18n('Course Management')}}
        </a>
      </li>
      <li>
        <a href="">
          <i class="bi bi-person-lines-fill"></i>
          {{ i18n('List of registered')}}
        </a>
      </li>
      <li>
        <a href="">
          <i class="bi bi-chat-square-text-fill"></i>
          {{i18n('Front page text')}}
        </a>
      </li>
      <li>
        <a href="">
          <i class="bi bi-person-plus-fill"></i>
          {{i18n('Add admin')}}
        </a>
      </li>
      <li>
        <a href="">
          <i class="bi bi-lock-fill"></i>
         {{i18n('Change password')}}
        </a>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {

  data() {
    return {
      show: false,
    }
  },

}
</script>

<style scoped>

</style>