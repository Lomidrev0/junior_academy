<template>
  <div>
    <div class="course-card" v-for="(course, key) in coursesList">
      <div class="course-item">
        <div class="course-bg" :style="'background-image: url('+course.media[1].original_url+')'"></div>
        <div class="course-card-body">
          <div class="course-item-head d-flex justify-content-between">
            <a :href="route('admin.detail', {slug: course.slug})">
              <div>
                <strong>{{course.name}}</strong>
              </div>
            </a>
            <div>
              <label class="switch">
                <input type="checkbox" class="course-toggle" v-model="course.active" @click="updateCourse(course, key, $event)">
                <span class="slider round"></span>
              </label>
              <i class="bi bi-x-lg" @click="deleteCourse(course.id,course.name)"></i>
            </div>
          </div>
          <a class="disabled" :href="route('admin.detail', {slug: course.slug})">
            <div class="course-item-body">
              <img :src="course.media[0].original_url" alt="logo">
            </div>
            <div class="course-item-footer d-flex justify-content-around">
              <span>{{course.admin.name}}</span>
              <span>{{ formatDate(course.updated_at, 'H:mm - dd.MM.yyyy') }}</span>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
import formatDates from "../formatDatesMixin";

export default {
  mixins:[formatDates],
  props: ['courses'],
  data(){
    return {
      coursesList: this.courses ? (this.courses.length > 0 ? _.cloneDeep(this.courses)  : null) : null,
    }
  },
  created(){
    this.coursesList = this.formatDates(this.coursesList);
  }
}
</script>

<style scoped>

</style>