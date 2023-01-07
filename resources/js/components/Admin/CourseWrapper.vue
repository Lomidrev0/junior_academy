<template>
<div>
<button class="btn btn-primary" @click="show = !show">
  <i class="bi bi-plus-lg"></i>
  {{i18n('Add')}}
</button>
  <div v-show="show">
    <form key="saveCourse" class="add-course-form">
      <div>
        <input :type="'text'" class="form-control" :name="'name'" :placeholder="i18n('Course name') + ' *'" v-model="newCourse.name">
      </div>
      <div>
        <textarea class="form-control" rows="3" :name="'description'" :placeholder="i18n('Short course description')" v-model="newCourse.description"></textarea>
      </div>

      <div class="d-flex justify-content-evenly">
        <div class="imgUp" v-for="(file,key) in files">
          <label class="w-100">
            <input :type="'file'"   accept='image/jpeg , image/jpg, image/gif, image/png' @change="files[key] = onFileSelected($event,file,key)" class="uploadFile img custom-file-input" >
            {{ file ? file.name : i18n('Choose image') }}
            <div  class="imagePreview d-flex align-items-center">
              <div :id="'append-'+key" class="d-flex flex-column align-items-center w-100 ">
                <i class="bi bi-plus-circle-dotted" :id="'ico-'+key"></i>
              </div>
            </div>
          </label>
          <div class="clear-file" @click="files[key] = clearFile(key)">{{i18n('Remove')}}</div>
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
    <template v-if="error.length > 0">
      <div class="alert alert-danger my-3 mx-4" role="alert">
        {{ error }}
        <div @click="error = ''" class="d-md-inline">
          <i class="bi bi-x-lg"></i>
        </div>
      </div>
    </template>
  </div>
  <div class="w-100">
    <div class="course-wrapper shadow">
      <div class="course-card" v-for="(course, key) in coursesList">
          <div class="course-item">
            <div class="course-bg" :style="'background-image: url('+course.media[1].original_url+')'"></div>
            <div class="course-card-body">
              <div class="course-item-head d-flex justify-content-between">
                <a :href="route('admin.detail', {slug: course.slug})">
                  <div>
                    <strong v-b-tooltip.hover :title="course.name">{{ truncateContent(course.name, 30) }}</strong>
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
                  <span v-b-tooltip.hover :title="course.admin.name">{{truncateContent(course.admin.name, 15)}}</span>
                  <span>{{ formatDate(course.updated_at, 'H:mm - dd.MM.yyyy') }}</span>
                </div>
              </a>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import WswgEditor from "../WswgEditor";
import {i18n} from "../../app";
import courseFormMixin from "../courseFormMixin";
import {parseISO} from 'date-fns';
import formatDatesMixin from "../formatDatesMixin";
import truncateMixin from "../truncateMixin";

export default {
  mixins:[courseFormMixin, formatDatesMixin, truncateMixin],
  components: {WswgEditor},
  props: ['courses','users'],
  data(){
    return {
      show: false,
      coursesList: this.courses ? (this.courses.length > 0 ? _.cloneDeep(this.courses) : null) : null,
      addTeacher: false,
      saving: false,
      error: '',
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
      if (this.newCourse.name.length === 0 || this.newCourse.description.length === 0 || this.removeHTML(this.newCourse.content).length === 0 || this.files.logo === null || this.files.bgImg === null){
        this.error = i18n('You have not filled in all required fields');
       } else if (this.newCourse.name.length > 60){
        this.error = i18n('The name is too long!');
      } else if (this.newCourse.description.length > 1000){
        this.error = i18n('Description is too long!');
      } else if (_.find(this.coursesList, (course) => { return course.name === this.newCourse.name })){
        this.error = i18n('There is already course with this name');
      } else {
        const formData = new FormData();
        formData.append('logo',this.files.logo, this.files.logo.name);
        formData.append('bgImg',this.files.bgImg, this.files.bgImg.name);
        formData.append('name', this.newCourse.name);
        formData.append('description', this.newCourse.description);
        formData.append('about', this.newCourse.content);
        formData.append('isActive', this.newCourse.isActive);
        formData.append('teachers', this.teachers === [] ? null : JSON.stringify(this.newCourse.teachers));
        this.saving = true;
        axios
            .post(this.route('admin.store'), formData)
            .then((response) => {
              this.reset();
              this.coursesList = this.formatDates(response.data);
              this.saving = false;
              this.$toast.success(i18n('Course has been sucessfully created'));
            })
      }
    },
    deleteCourse(id,name) {
      if (confirm(i18n('Are you sure you want to delete course: ') + ' "' + name + '"?' )) {
        axios
            .post(this.route('admin.delete'), {id: id})
            .then((response) => {
              this.coursesList = this.formatDates(response.data);
              this.$toast.success(i18n('Course has been successfully deleted'));
            })
      }
    },
    updateCourse(course, key, event){
       if (confirm( i18n('Are you sure you want to change status of the course: ') + ' "' + course.name + '"?' )) {
         axios
             .post(this.route('admin.update-active'), {id: course.id, value: !course.active})
             .then((response) => {
               this.$set(this.coursesList, key, this.formatObjectDates(response.data));
               this.$toast.success(i18n('Course has been sucessfully updated'));
             })
       }
       else {
         event.preventDefault();
         event.stopPropagation();

       }
    },
    reset() {
      this.newCourse.isActive = false;
      this.newCourse.name = '';
      this.newCourse.description = '';
      this.newCourse.content = '';
      this.clearFile();
      this.newCourse.teachers = [];
    },
  },

  created() {
    window.addEventListener( "pageshow", function ( event ) {
      var historyTraversal = event.persisted ||
          ( typeof window.performance != "undefined" &&
              window.performance.navigation.type === 2 );
      if ( historyTraversal ) {
        window.location.reload();
      }
    });
    this.coursesList = this.formatDates(this.coursesList);
  }
}
</script>

<style scoped>

</style>