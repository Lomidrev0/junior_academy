<template>
  <div>
    <div>
      <form key="saveAlbum" class="add-course-form">
        <div>
          <input :type="'text'" class="form-control" :placeholder="i18n('Course name') + ' *'" v-model="directory.name">
        </div>
        <div>
          <textarea class="form-control" rows="3" :placeholder="i18n('Short course description')" v-model="directory.description"></textarea>
        </div>
        <div>
          <input type="checkbox" v-model="directory.active"> aktivny
        </div>
        <div>
          <button type="submit" @click.prevent="updateDir()">
            <template>
              {{ i18n('Save') }}
            </template>
            <b-spinner small v-if="saving"></b-spinner>
          </button>
        </div>
      </form>
      <p>-----------------------------------</p>
      <form key="#" class="add-course-form">
          <div class="imgUp" v-for="(file,key) in files">
            <label class="w-100">
              <input :type="'file'" multiple="multiple" :id="'input-'+key" accept='image/jpeg , image/jpg, image/gif, image/png' @change="files[key] = onFileSelected($event,file,key); resetInput(key)" class="uploadFile img custom-file-input" >
              {{ file ? getFileName(file) : i18n('Choose image') }}
              <div  class="imagePreview d-flex align-items-center">
                <div :id="'append-'+key" class="d-flex flex-column align-items-center w-100 ">
                  <i class="bi bi-plus-circle-dotted" :id="'ico-'+key"></i>
                </div>
              </div>
            </label>
            <div class="clear-file" @click="files[key] = clearFile(key)">{{i18n('Remove')}}</div>
          </div>
        <div>
          <button type="submit" @click.prevent="saveImg()">
            <template>
              {{ i18n('Save') }}
            </template>
            <b-spinner small v-if="saving"></b-spinner>
          </button>
        </div>
      </form>
      <button @click.prevent="fn()">
        <template>
          {{ i18n('Saveddddd') }}
        </template>
        <b-spinner small v-if="saving"></b-spinner>
      </button>
      <template v-if="error.length > 0">
        <div class="alert alert-danger my-3 mx-4" role="alert">
          {{ error }}
          <div @click="error = ''" class="d-md-inline">
            <i class="bi bi-x-lg"></i>
          </div>
        </div>
      </template>
    </div>
    <div class="course-wrapper">
      <template v-if="true">
          <div class="course-card" v-for="(image, key) in directory.media">
            <div class="course-item shadow">
              <div>
                <div class="c-header">
                  <span>{{image.name}}</span>
                  <i class="bi bi-x-lg" @click="deleteImg(image.id,image.name)"></i>
                </div>
                <a :href="image.original_url" data-fancybox="gallery" :data-caption="image.name">
                  <img :src="image.original_url" alt="">
                </a>
              </div>
            </div>
          </div>
      </template>
      <template v-else>
        <div>
          <p>Zatial zaiadne obr</p>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
import courseFormMixin from "../courseFormMixin";
import {i18n, toast} from "../../app";
export default {
  mixins: [courseFormMixin],
  props: ['dir'],
  data() {
    return {
      directory: this.dir ? _.cloneDeep(this.dir) : null,
      error: '',
      saving: false,
      files:{
       img: null,
      },
    }
  },
  methods: {
    saveImg() {
      const formData = new FormData();
      _.forEach(this.files.img, (img,key) => {return  formData.append('img'+key,img);})
      formData.append('id',this.directory.id);
      axios
          .post(this.route('teacher.store-img'), formData)
          .then((response) => {
            this.clearFile('img');
            this.directory = response.data;
            this.files.img = null;
            this.saving = false;
            // toast.success(i18n('Course has been sucessfully created'),null);
          })
          .catch((error) => {
            // toast.error(i18n('Error'),error);
          })
    },
    deleteImg(id,name) {
      if (confirm(i18n('Are you sure you want to delete course: ') + ' "' + name + '"?' )) {
        axios
            .post(this.route('teacher.delete-img'), {dirId: this.directory.id, id: id})
            .then((response) => {
              this.directory = response.data;
              this.saving = false;
              //toast.success(i18n('Course has been sucessfully deleted'),null);
            })
            .catch((error) => {
              //toast.error(i18n('Error'),i18n('err'));
            })
      }
    },
    updateDir() {
      axios
          .post(this.route('teacher.update-album'), {id: this.directory.id, dir: {
            'name': this.directory.name,
            'description': this.directory.description,
            'active': this.directory.active
          }})
          .then((response) => {
            if (!Array. isArray(response.data)) {
              this.editCourse = response.data[0];
            } else {
              this.editCourse = response.data[0]
              history.replaceState(response.data, '', '/teacher/gallery/' + response.data[1]);
            }
            this.saving = false;
            //toast.success(i18n('Course has been sucessfully deleted'),null);
          })
          .catch((error) => {
            //toast.error(i18n('Error'),i18n('err'));
          })
    },

    fn() {
      axios
      .get(this.route('teacher.download-album'),{params: {id:8}})
      .then((response) => {
        console.log(response.data);
      })
      .catch()
    }
  },
}
</script>

<style scoped>

</style>