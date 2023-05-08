<template>
    <div>
      <div v-for="(course, index) in coursesList" :key="index">
        <a class="text-decoration-none" :href="route('course-detail',{slug:course.slug})">
          <article :data-aos="index % 2 === 1 ? 'fade-left' : 'fade-right'" class="postcard light shadow" :class=" index % 2 === 1 ? 'flex-row-reverse right-12': 'left-12' " >
            <div class="postcard__img":style="'background-image: url('+course.media[1].original_url+')'" />
            <div class="postcard__text t-dark">
              <div class="d-flex">
                <div class="image rekt"><img class="img_up" :src="course.media[0].original_url" alt="logo"></div>
                <div class="sicko">
                  <div class="logos rekt">
                    <h1 class="postcard__title blue heading_course">{{course.name}}</h1>
                    <i class="fas fa-calendar-alt mr-2"></i><p>{{ formatDate(course.created_at, 'dd.MMMM.yyyy') }}</p>
                  </div>
                </div>
              </div>
              <div class="postcard__bar"></div>
              <div class="postcard__preview-txt">{{ removeHTML(course.description) }}</div>
            </div>
          </article>
        </a>
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
    methods: {
        removeHTML(str){
            var tmp = document.createElement("DIV");
            tmp.innerHTML = str;
            return tmp.textContent || tmp.innerText || "";
        },
    },
  created(){
    this.coursesList = this.formatDates(this.coursesList);
  }
}
</script>



















































































































































































































































































































































