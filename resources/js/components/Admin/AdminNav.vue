<template>
  <nav class="side-nav">
    <ul>
      <li>
        <div class="open-nav d-flex justify-content-center" @click="show = !show">
              <i class="bi bi-x-lg" v-if="show"></i>
              <i class="bi bi-caret-right-fill" v-else></i>
        </div>
      </li>
      <li>
        <a :href="route('admin.home')">
          <i class="bi bi-house-fill"></i>
          <span>{{i18n('Home')}}</span>
        </a>
      </li>
      <li>
        <a :href="route('admin.course')">
          <i class="bi bi-wrench"></i>
          <span>{{i18n('Course management')}}</span>
        </a>
      </li>
      <li>
        <a href="">
          <i class="bi bi-person-lines-fill"></i>
          <span>{{ i18n('List of registered')}}</span>
        </a>
      </li>
      <li>
        <a href="">
          <i class="bi bi-chat-square-text-fill"></i>
         <span> {{i18n('Front page text')}}</span>
        </a>
      </li>
      <li>
        <a href="">
          <i class="bi bi-person-plus-fill"></i>
          <span>{{i18n('Add admin')}}</span>
        </a>
      </li>
      <li>
        <a href="">
          <i class="bi bi-lock-fill"></i>
          <span>{{i18n('Change password')}}</span>
        </a>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {

  data() {
    return {
      show: true,
    }
  },
  methods: {
    openNav() {
      document.getElementsByClassName("side-nav").style.width = "250px";
    },

    closeNav() {
      document.getElementsByClassName("side-nav").style.width = "0";
    }
  },

}
</script>

<style scoped>

</style>