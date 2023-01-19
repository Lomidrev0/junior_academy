<template>
  <div>
    <div>
      <a :href="route('admin.courses')">
        <button class="btn btn-primary">
          <i class="bi bi-arrow-left"></i>{{i18n('Back')}}
        </button>
      </a>
    </div>
    <form class="add-course-form">
      <div class="d-flex inputs-wrapper">
        <div class="d-flex flex-column text-input-wrapper">
          <div>
            <input :type="'text'" class="form-control shadow" :name="'name'" :placeholder="i18n('Course name') + ' *'" v-model="editCourse.name">
          </div>
          <div>
            <textarea class="form-control shadow" rows="3" :name="'description'" :placeholder="i18n('Short course description')" v-model="editCourse.description"></textarea>
          </div>
        </div>
        <div class="d-flex justify-content-around file-input-wrapper">
          <div class="imgUp shadow" v-for="(file,key) in files">
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
      </div>
      <div>
        <wswg-editor v-model="editCourse.about"></wswg-editor>
      </div>
      <div>
        <div class="add-teacher">
          <h4>
            {{i18n('Add teacher')+':'}}
          </h4>
        </div>
        <div class="teacher-for-wrapper">
          <template v-if="users.length">
            <template v-for="user in users">
              <label class="add-teacher-wrapper" :class="getActiveTeacher(user.id) ? 'active-teacher':''">
                <input :value="user.id" type="checkbox" v-model="teachers">
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
            <span class="alert-danger">{{i18n('No teachers found!')}} <a :href="route('admin.add-user')">Pridať učiteľa</a></span>
          </template>
        </div>
      </div>
      <div>
        <div class="d-flex align-content-center flex-column">
          <div class="m-auto mt-3">
            <label class="d-flex">
              Aktivovať kurz:
              <div class="checkbox-wrapper-31">
                <input type="checkbox" v-model="editCourse.active"/>
                <svg viewBox="0 0 35.6 35.6">
                  <circle class="background" cx="17.8" cy="17.8" r="17.8"></circle>
                  <circle class="stroke" cx="17.8" cy="17.8" r="14.37"></circle>
                  <polyline class="check" points="11.78 18.12 15.55 22.23 25.17 12.87"></polyline>
                </svg>
              </div>
            </label>
          </div>
          <button class="button_first m-auto mt-3 mb-3 px-5" type="submit" @click.prevent="update()">
            {{ i18n('Save') }}
            <b-spinner small v-if="saving"></b-spinner>
          </button>
        </div>
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
</template>

<script>
import WswgEditor from "../WswgEditor";
import {i18n, route} from "../../app";
import courseFormMixin from "../courseFormMixin";

export default {
  mixins:[courseFormMixin],
  components: {WswgEditor},
  props: ['course','users'],
  data() {
    return {
      change: false,
      saving: false,
      error:'',
      editCourse: this.course ?  _.cloneDeep(this.course) : null,
      teachers: this.getTeacherId(),
        files: {
          logo: null,
          bgImg: null,
        }
      }
  },

  methods: {
    getTeacherId() {
      return _.map(this.course.users, (user) => {
        return user.id;
      });
    },

    update() {
      if (this.editCourse.name.length === 0 || this.editCourse.description.length === 0 || this.removeHTML(this.editCourse.content).length === 0) {
        this.error = i18n('You have not filled in all required fields');
      } else if (this.editCourse.name.length > 60) {
        this.error = i18n('The name is too long!');
      } else if (this.editCourse.description.length > 1000) {
        this.error = i18n('Description is too long!');
      } else if (this.editCourse.name === this.course.name &&
          this.editCourse.description === this.course.description &&
          this.removeHTML(this.editCourse.content).length === this.removeHTML(this.course.content).length &&
          this.files.logo === null && this.files.bgImg === null && this.editCourse.active === this.course.active &&
          _.isEqual(this.teachers,this.getRawTeachers())
          ) {
        this.error = i18n('A change is required for save');
      } else {

        const formData = new FormData();
        formData.append('id', this.editCourse.id);
        this.files.logo ? formData.append('logo', this.files.logo, this.files.logo.name) : null;
        this.files.bgImg ? formData.append('bgImg', this.files.bgImg, this.files.bgImg.name) : null;
        this.editCourse.name === this.course.name ? null : formData.append('name', this.editCourse.name);
        formData.append('description', this.editCourse.description);
        formData.append('about', this.editCourse.about);
        formData.append('isActive', this.editCourse.active);
        formData.append('teachers', this.teachers === [] ? null : JSON.stringify(this.teachers));
        axios
            .post(route('admin.update'), formData)
            .then((response) => {
              if (response.data.length > 1) {
                this.editCourse = response.data[0];
                this.$toast.success(i18n('Course has been sucessfully updated'));
              } else {
                history.replaceState(response.data, '', '/admin/detail/' + response.data.slug);
              }
            })
      }
    },
    getRawTeachers() {
      return _.map(this.editCourse.users, (user)=>{
        return user.id;
      })
    },
    getActiveTeacher(id) {
     return  _.includes(this.teachers,id)
    }
  },
  mounted() {
    /*, _.find(this.editCourse.media, {'disk':'bg-photo'}).original_url*/
    this.setExistingImg(this.editCourse.media, _.keys(this.files));
  },
}
</script>

<style scoped>

</style>