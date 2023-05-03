<template>
<div>
  <div class="d-flex position-relative justify-content-between flex-column flex-sm-row">
    <button class="button_first" @click="show = !show">
      <i class="bi bi-plus-lg"></i>
      {{i18n('Add course')}}
    </button>
    <div ref="tooltipList" class="d-flex align-items-center position-relative pointer-event cursor-pointer user-select-none">
      <div class="d-flex" @click="startRegistration = !startRegistration">
        <h5 class="mx-2 m-none d-flex align-items-center">
          {{i18n('Registration is:')+' '}}
          {{permitReg === true ? i18n('Launched'): ( permitReg === false ? i18n('Prohibited') : i18n('Launched to')+' '+formatDate(permitReg, 'dd.MM.yyyy HH:mm')) }}
        </h5>
        <i class="bi bi-three-dots-vertical"></i>
      </div>
      <div class="launch-reg" v-if="startRegistration">
        <div class="tooltip-item">
          <div class="date-item">
            <input v-model="regUntil" type="datetime-local">
            <button @click.prevent="launchRegistration(regUntil)">
              {{i18n('Save')}}
            </button>
          </div>
        </div>
        <div class="tooltip-item">
          <button @click.prevent="launchRegistration(true)">
            {{i18n('Launch registration')}}
          </button>
        </div>
        <div class="tooltip-item">
          <button @click.prevent="launchRegistration(false)">
            {{i18n('Stop registration')}}
          </button>
        </div>
      </div>
    </div>

  </div>
  <div class="mb-5" v-show="show">
    <form key="saveCourse" class="add-course-form">
      <div class="d-flex inputs-wrapper">
        <div class="d-flex flex-column text-input-wrapper">
          <div>
            <input :type="'text'" class="form-control shadow" :name="'name'" :placeholder="i18n('Course name')" v-model="newCourse.name">
          </div>
          <div>
            <textarea class="form-control shadow" rows="3" :name="'description'" :placeholder="i18n('Short course description')" v-model="newCourse.description"></textarea>
          </div>
        </div>
        <div class="d-flex justify-content-around file-input-wrapper">
          <div class="imgUp" v-for="(file,key) in files">
            <p class="text-center p-1">{{key}}</p>
            <div class="shadow radius">
              <label class="w-100">
                <input :type="'file'" :id="'input-'+key"   accept='image/jpeg , image/jpg, image/gif, image/png' @change="files[key] = onFileSelected($event,file,key); resetInput(key)" class="uploadFile img custom-file-input" >
                <span v-b-tooltip.hover :title="file ? (getFileName(file).length >= 35 ? getFileName(file) : '') : ''"> {{ file ? truncateContent(getFileName(file), 35) : i18n('Choose image') }} </span>
                <div  class="imagePreview d-flex align-items-center">
                  <div :id="'append-'+key" class="d-flex flex-column align-items-center w-100 ">
                    <i class="bi bi-plus-circle-dotted" :id="'ico-'+key"></i>
                  </div>
                </div>
              </label>
              <div class="clear-file" @click="files[key] = clearFile(key)">{{i18n('Remove')}}</div>
            </div>

          </div>
        </div>
      </div>
      <div>
        <wswg-editor v-model="newCourse.content"></wswg-editor>
      </div>
      <div>
        <div class="add-teacher">
          <h4 @click="addTeacher = !addTeacher">
            {{i18n('Add teacher')+':'}}
            <i :class="addTeacher ? 'd-none':'d-inline-block'" class="bi bi-plus-lg"></i>
            <i :class="addTeacher ? 'd-inline-block':'d-none'" class="bi bi-dash"></i>
          </h4>
        </div>
        <div class="teacher-for-wrapper" v-if="addTeacher">
          <template v-if="users.length">
            <template v-for="user in users">
              <label class="add-teacher-wrapper" :class="getActiveTeacher(user.id) ? 'active-teacher':''">
                <input :value="user.id" type="checkbox" v-model="newCourse.teachers">
                <span >
                  <i class="bi bi-check-circle-fill check-icon" :class="getActiveTeacher(user.id) ? 'd-block':'d-none'"></i>
                  <i class="bi bi-x-circle-fill close-icon" :class="getActiveTeacher(user.id) ? 'd-none':'d-block'"></i>
                </span>
                <span>
                  {{user.name}}
                </span>
              </label>
            </template>
          </template>
          <template v-else>
            <span class="alert-danger">{{i18n('No teachers found!')}} <a :href="route('admin.add-user')">{{i18n('Add teacher')}}</a></span>
          </template>
        </div>
      </div>
      <div class="d-flex align-content-center flex-column">
        <div class="m-auto mt-3">
          <label class="d-flex">
            {{i18n('Activate course')+':'}}
            <div class="checkbox-wrapper-31">
              <input type="checkbox" v-model="newCourse.isActive"/>
              <svg viewBox="0 0 35.6 35.6">
                <circle class="background" cx="17.8" cy="17.8" r="17.8"></circle>
                <circle class="stroke" cx="17.8" cy="17.8" r="14.37"></circle>
                <polyline class="check" points="11.78 18.12 15.55 22.23 25.17 12.87"></polyline>
              </svg>
            </div>
          </label>
        </div>
        <button class="button_first m-auto mt-3 mb-3 px-5" type="submit" @click.prevent="saveCourse()">
            {{ i18n('Save') }}
          <b-spinner small v-if="saving"></b-spinner>
        </button>
      </div>
    </form>
    <alert v-if="error.length > 0" :error="error"  @close="error = ''"></alert>
  </div>
  <div class="course-wrapper">
    <template v-if="coursesList.length > 0">
      <div class="course-card" v-for="(course, key) in coursesList">
        <div class="course-item shadow">
          <div class="course-bg" :style="'background-image: url('+course.media[1].original_url+')'"></div>
          <div class="course-card-body">
            <div class="course-item-head d-flex justify-content-between">
              <a :href="route('admin.detail', {slug: course.slug})">
                <div>
                  <strong v-b-tooltip.hover  :title="course.name.length >= 30 ? course.name : ''">{{ truncateContent(course.name, 30) }}</strong>
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
                <span v-b-tooltip.hover :title="course.admin.name.length >= 15 ? course.admin.name : ''">{{truncateContent(course.admin.name, 15)}}</span>
                <span>{{ formatDate(course.updated_at, 'H:mm - dd.MM.yyyy') }}</span>
              </div>
            </a>
          </div>
        </div>
      </div>
    </template>
    <template v-else>
      <no-results
          :header="i18n('No courses found')"
          :body="i18n('No course has been created yet. Create a course and allow students to sign up!')"
      ></no-results>
    </template>
  </div>
</div>
</template>

<script>
import WswgEditor from "../WswgEditor";
import {i18n} from "../../app";
import {toast} from "../../app"
import courseFormMixin from "../courseFormMixin";
import {parseISO} from 'date-fns';
import formatDatesMixin from "../formatDatesMixin";
import truncateMixin from "../truncateMixin";
import Alert from "../Alert";
import NoResults from "../NoResults";

export default {
  mixins: [courseFormMixin, formatDatesMixin, truncateMixin],
  components: {NoResults, Alert, WswgEditor},
  props: ['courses', 'users','reg'],
  data() {
    return {
      permitReg :this.courses ? _.cloneDeep(this.reg) : null,
      show: false,
      coursesList: this.courses ? (this.courses.length > 0 ? _.cloneDeep(this.courses) : null) : null,
      addTeacher: false,
      saving: false,
      error: '',
      regUntil: null,
      startRegistration: false,
      newCourse: {
        isActive: false,
        name: '',
        description: '',
        content: '',
        teachers: [],
      },
      files: {
        logo: null,
        bgImg: null,
      }
    }
  },
  methods: {
    saveCourse() {
      if (this.newCourse.name.length === 0 || this.newCourse.description.length === 0 || this.removeHTML(this.newCourse.content).length === 0 || this.files.logo === null || this.files.bgImg === null) {
        this.error = i18n('You have not filled in all required fields');
      } else if (this.newCourse.name.length > 60) {
        this.error = i18n('The name is too long!');
      } else if (this.newCourse.description.length > 390) {
        this.error = i18n('Description is too long!');
      } else if (_.find(this.coursesList, (course) => {
        return course.name === this.newCourse.name
      })) {
        this.error = i18n('There is already course with this name');
      } else {
        const formData = new FormData();
        formData.append('logo', this.files.logo[0]);
        formData.append('bgImg', this.files.bgImg[0]);
        formData.append('name', this.newCourse.name);
        formData.append('description', this.newCourse.description);
        formData.append('about', this.newCourse.content);
        formData.append('isActive', this.newCourse.isActive);
        formData.append('teachers', this.teachers === [] ? null : JSON.stringify(this.newCourse.teachers));
        this.saving = true;
        axios
            .post(this.route('admin.store'), formData)
            .then((response) => {
              this.clearFile(['logo', 'bgImg']);
              this.coursesList = this.formatDates(response.data);
              this.reset()
              this.saving = false;
              toast.success(i18n('Course has been sucessfully created'), null);
            })
            .catch((error) => {
              toast.error(i18n('Error'), error);
            })
      }
    },
    deleteCourse(id, name) {
      if (confirm(i18n('Are you sure you want to delete course: ') + ' "' + name + '"?')) {
        axios
            .post(this.route('admin.delete'), {id: id})
            .then((response) => {
              this.coursesList = this.formatDates(response.data);
              toast.success(i18n('Course has been sucessfully deleted'), null);
            })
            .catch((error) => {
              toast.error(i18n('Error'), i18n('err'));
            })
      }
    },
    updateCourse(course, key, event) {
      if (confirm(i18n('Are you sure you want to change status of the course: ') + ' "' + course.name + '"?')) {
        axios
            .post(this.route('admin.update-active'), {id: course.id, value: !course.active})
            .then((response) => {
              this.$set(this.coursesList, key, this.formatObjectDates(response.data));
              toast.success(i18n('Course has been sucessfully updated'), null);
            })
            .catch((error) => {
              toast.error(i18n('Error'), i18n('err'));
            })
      } else {
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
    getActiveTeacher(id) {
      return _.includes(this.newCourse.teachers, id)
    },
    launchRegistration(value) {
      if (!(this.permitReg === value && this.regUntil === null)){
        if (confirm( value !== false ? i18n('Do you really want to launch registration for courses?') : i18n('Do you really want to disable registration for courses?'))) {
          if (typeof value !== "boolean") {
            value = new Date(value);
          }
          axios
              .post(this.route('admin.update-registration'), {permit: value})
              .then((response) => {
                if (typeof response.data.permit !== 'boolean'){
                  this.permitReg = parseISO(response.data.permit);
                }
                else {
                  this.permitReg = response.data.permit;
                }
              })
          // .catch((error) => {
          //   toast.error(i18n('Error'),i18n('err'));
          // })
        }
      }
      this.startRegistration = false;
    },
    handleClickOutside(event) {
      if (!this.$refs.tooltipList.contains(event.target)) {
        this.startRegistration = false;
      }
    },
  },
  mounted() {
    document.addEventListener('click', this.handleClickOutside);
  },
  destroyed() {
    document.removeEventListener('click', this.handleClickOutside);
  },
  created() {
    window.addEventListener("pageshow", function (event) {
      var historyTraversal = event.persisted ||
          (typeof window.performance != "undefined" &&
              window.performance.navigation.type === 2);
      if (historyTraversal) {
        window.location.reload();
      }
    });
    this.coursesList = this.formatDates(this.coursesList);
    if (typeof this.permitReg !== 'boolean'){
      this.permitReg = parseISO(this.permitReg);
    }
  }
}
</script>

<style scoped>

</style>