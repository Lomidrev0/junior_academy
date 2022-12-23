<template>
  <div>
    <div>
      <a :href="route('admin.courses')">
        <button class="btn btn-primary">
          <i class="bi bi-arrow-left"></i>{{i18n('Back')}}
        </button>
      </a>
    </div>
    <form>
      <div>
        <input :type="'text'" class="form-control" :name="'name'" :placeholder="i18n('Course name') + ' *'" v-model="editCourse.name">
      </div>
      <div>
        <textarea class="form-control" rows="3" :name="'description'" :placeholder="i18n('Short course description')" v-model="editCourse.description"></textarea>
      </div>

      <div class="d-flex justify-content-evenly">
        <div class="imgUp">
          <label class="w-100">
            <input :type="'file'"   accept='image/jpeg , image/jpg, image/gif, image/png' @change="onLogoSelected" class="uploadFile img custom-file-input" >
            {{ files.logo === null ? editCourse.media[0].file_name  : getLogoName() }}
            <div  class="imagePreview d-flex align-items-center">
              <div id="logo" class="d-flex flex-column align-items-center w-100 ">
                <i class="bi bi-plus-circle-dotted add-logo-icon"></i>
              </div>
            </div>
          </label>
          <div class="clear-file" @click="clearLogo()">{{i18n('Remove')}}</div>
        </div>

        <div class="imgUp">
          <label class="w-100">
            <input :type="'file'"   accept='image/jpeg , image/jpg, image/gif, image/png' @change="onBgImgSelected" class="uploadFile img custom-file-input">
            {{ files.logo === null ? editCourse.media[1].file_name : getBgImgName() }}
            <div  class="imagePreview d-flex align-items-center">
              <div id="bgImg" class="d-flex flex-column align-items-center w-100 ">
                <i class="bi bi-plus-circle-dotted add-bg-img-icon"></i>
              </div>
            </div>
          </label>
          <div class="clear-file" @click="clearBgImg()">{{i18n('Remove')}}</div>
        </div>
      </div>

      <div>
        <input type="checkbox" v-model="editCourse.active"> {{i18n('Active')}}
      </div>
      <div>
        <wswg-editor v-model="editCourse.about"></wswg-editor>
      </div>
      <div>
        <div>
          {{i18n('Add teacher: ')}}
        </div>
        <div class="d-flex teacher-wrapper">
          <template v-if="users.length">
            <template v-for="user in users">
              <label class="d-flex checkbox-item" :class="getActiveTeacher(user.id) ? 'active-teacher':''">
                <input :value="user.id" type="checkbox" v-model="teachers">
                <div>
                  {{user.name}}
                </div>
              </label>

            </template>
          </template>
          <template v-else>
            <span class="alert-danger">{{i18n('No teachers found!')}}</span>
          </template>
        </div>
      </div>
      <div>
        <button type="submit" @click.prevent="update()">
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
</template>

<script>
import WswgEditor from "../WswgEditor";
import {i18n, route} from "../../app";
import courseFormMixin from "./courseFormMixin";

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
    this.setImg(_.find(this.editCourse.media, {'disk':'logo'}).original_url, _.find(this.editCourse.media, {'disk':'bg-photo'}).original_url);
  },
}
</script>

<style scoped>

</style>