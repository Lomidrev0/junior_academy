<template>
<div>
<button>
  <i class="bi bi-plus-lg"></i> add
</button>
  <div>
    <form key="saveCourse">
      <div>
        <input :type="'text'" class="form-control" :name="'name'" :placeholder="i18n('Course name') + ' *'" v-model="newCourse.name">
      </div>
      <div>
        <textarea class="form-control" rows="3" :name="'description'" :placeholder="i18n('Short course description')" v-model="newCourse.description"></textarea>
      </div>
      <div>
        <input :type="'file'" class="custom-file-input"  accept='image/jpeg , image/jpg, image/gif, image/png' @change="onLogoSelected">
        <label>{{ i18n('Choose image') }} *</label>
      </div>
      <div>
        <input :type="'file'" class="custom-file-input"  accept='image/jpeg , image/jpg, image/gif, image/png' @change="onBgImgSelected">
        <label>{{ i18n('Choose image') }} *</label>
      </div>
      <div>
        <input type="checkbox" v-model="newCourse.isActive"> aktivny
      </div>
      <div>
        <wswg-editor v-model="newCourse.content"></wswg-editor>
      </div>
      <div>
        <label for="cars">{{i18n('Add teacher: ')}}</label>
        <template v-if="users.length">
          <select name="cars" id="cars">
            <option v-for="user in users" :value="user.id">{{user.name}}</option>
          </select>
        </template>
        <template v-else>
        <span class="alert-danger">{{i18n('No teachers found!')}}</span>
        </template>

      </div>
      <div>
        <button type="submit" @click.prevent="saveCourse()">
          <template>
            {{ i18n('Save') }}
          </template>
          <b-spinner></b-spinner>
        </button>
      </div>
    </form>
  </div>
  <div class="w-100">
    <div class="course-wrapper shadow">
      <div class="course-card" v-for="(course) in coursesList">
        <div class="course-item-head">
          {{course.name}}
        </div>
        <div class="course-item-body">
          <img :src="course.media[0].original_url" alt="logo">
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import WswgEditor from "../WswgEditor";
export default {
  components: {WswgEditor},
  props: ['courses','users'],
  data(){
    return {
      coursesList: this.courses ? (this.courses.length > 0 ? this.courses : null) : null,

      newCourse: {
        isActive: false,
        name: '',
        description: '',
        content: '',
      },

      files: {
        logo: null,
        bgImg: null,
      }

    }
  },
  methods: {
    saveCourse(){
      const formData = new FormData();
      formData.append('logo',this.files.logo, this.files.logo.name);
      formData.append('bgImg',this.files.bgImg, this.files.bgImg.name);
      formData.append('name', this.newCourse.name);
      formData.append('description', this.newCourse.description);
      formData.append('content', this.newCourse.content);
      formData.append('isActive', this.newCourse.isActive);
      axios
      .post(this.route('admin.store'), formData)
      .then((response) => {
        this.coursesList = response.data;
        this.reset();

      })
    },
    onLogoSelected(event) {
      this.files.logo = event.target.files[0];
    },
    onBgImgSelected(event) {
      this.files.bgImg = event.target.files[0];
    },

    reset() {
      this.newCourse.isActive = false;
      this.newCourse.name = '';
      this.newCourse.description = '';
      this.newCourse.content = '';

    },

  }

}
</script>

<style scoped>

</style>