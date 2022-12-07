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

      <div class="d-flex w-100">
        <div class="imgUp">
          <label class="w-100">
          <input :type="'file'"   accept='image/jpeg , image/jpg, image/gif, image/png' @change="onLogoSelected" class="uploadFile img custom-file-input" >
            {{ getLogoName() }}
            <div id="logo" class="imagePreview d-flex align-items-center">
              <div class="d-flex flex-column align-items-center w-100">
                <i class="bi bi-plus-circle-dotted"></i>
              </div>
            </div>
          </label>
          <div class="clear-file" @click="clearLogo()">clear</div>
        </div>

        <div class="imgUp">
          <label class="w-100">
            <input :type="'file'"   accept='image/jpeg , image/jpg, image/gif, image/png' @change="onBgImgSelected" class="uploadFile img custom-file-input">
            {{ getBgImgName() }}
            <div id="bgImg" class="imagePreview d-flex align-items-center">
              <div class="d-flex flex-column align-items-center w-100">
                <i class="bi bi-plus-circle-dotted"></i>
              </div>
            </div>
          </label>
          <div class="clear-file" @click="clearBgImg()">clear</div>
        </div>
      </div>

      <div>
        <input type="checkbox" v-model="newCourse.isActive"> aktivny
      </div>
      <div>
        <wswg-editor v-model="newCourse.content"></wswg-editor>
      </div>
      <div>
        <div for="cars">{{i18n('Add teacher: ')}}</div>
        <div @click="addTeacher = true"><i class="bi bi-plus-lg"></i></div>
        <div v-if="addTeacher">
          <template v-if="users.length">
            <template v-for="user in users">
              <input :value="user.id" type="checkbox" v-model="newCourse.teachers">{{user.name}}
            </template>
          </template>
          <template v-else>
            <span class="alert-danger">{{i18n('No teachers found!')}}</span>
          </template>
        </div>
        <i @click="addTeacher = false" class="bi bi-dash"></i>
      </div>
      <div>
        <button type="submit" @click.prevent="saveCourse()">
          <template>
            {{ i18n('Save') }}
          </template>
          <b-spinner small v-if="saving"></b-spinner>
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
import {i18n} from "../../app";
export default {
  components: {WswgEditor},
  props: ['courses','users'],
  data(){
    return {
      coursesList: this.courses ? (this.courses.length > 0 ? this.courses : null) : null,
      addTeacher: false,
      saving: false,
      newCourse: {
        isActive: false,
        name: '',
        description: '',
        content: '',
        teachers:[],
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
      formData.append('teachers', this.teachers === [] ? null : JSON.stringify(this.newCourse.teachers));
      console.log(this.newCourse.teachers);
      axios
      .post(this.route('admin.store'), formData)
      .then((response) => {
        this.coursesList = response.data;
        this.saving = !this.saving;
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
      this.clearLogo();
      this.clearBgImg();

    },
    addUser(id) {
      this.teachers.add(id);
    },
    getLogoName() {
      if (this.files.logo !== null){
        return this.files.logo.name;
      }
      else {
        return i18n('Choose image');
      }

    },
    getBgImgName() {
      if (this.files.bgImg !== null){
        return this.files.bgImg.name;
      }
      else {
        return i18n('Choose image');
      }
    },
    clearLogo() {
      this.files.logo = null;
      $("#logo").css("background-image", "none");
    },
    clearBgImg() {
      this.files.bgImg = null;
      $("#bgImg").css("background-image", "none");
    }
  }
}
</script>

<style scoped>

</style>