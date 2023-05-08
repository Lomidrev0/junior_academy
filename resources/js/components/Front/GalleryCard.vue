<template>
    <div class="grid-item shadow">
      <a class="text-decoration-none" :href="route('directory', {'slug' : album.slug})">
      <div class="album-grid-item">
        <div class="album-img">
          <img v-if="album.media.length > 0" :src="album.media[0].original_url" alt="">
          <img v-else src="/images/default_img.png" alt="">
          <div class="album-count">
            <i class="bi bi-images"></i>
            <span class="px-2">{{album.media_count}}</span>
          </div>
        </div>
        <div class="album-text">
          <h4>{{album.name}}</h4>
          <p class="mx-2">{{album.description.substring(0,180) + (album.description.length > 180 ? '...' : '')}}</p>
        </div>
        <div class="my-3 mx-3 album-footer">
          <p><a :href=" route('course-detail',{'slug' : album.slug }) ">{{album.name}}</a></p>
          <p class="card-date">{{i18n('Created at')}}: {{ formatDate(created_at, 'd.M.yyyy - H:mm ') }}</p>
        </div>
      </div>
      </a>
    </div>
</template>

<script>
import truncateMixin from "../truncateMixin";
import formatDatesMixin from "../formatDatesMixin";
import {parseISO} from "date-fns";

export default {
  mixins: [truncateMixin, formatDatesMixin],
  props: ['album'],
  data(){
    return {
      created_at: parseISO(this.album.created_at),
    }
  },
  methods: {
    excute(){
      console.log(this.album.slug)
    }
  },
  mounted() {
    this.excute();
  }
}
</script>

<style scoped>

</style>