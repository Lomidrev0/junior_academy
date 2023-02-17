<template>
  <div class="w-100">
    <div v-if="editable" class="forms-wrapper">
      <form key="saveAlbum" class="add-course-form">
        <h5 class="text-center mt-3">{{i18n('Edit album')+': '+directory.name}}</h5>
        <div class="d-flex inputs-wrapper">
          <div class="d-flex flex-column text-input-wrapper m-auto w-75">
            <div class="shadow">
              <input :type="'text'" class="form-control" :placeholder="i18n('Course name') + ' *'" v-model="directory.name">
            </div>
            <div class="shadow">
              <textarea class="form-control" rows="3" :placeholder="i18n('Short course description')" v-model="directory.description"></textarea>
            </div>
          </div>
        </div>
        <div>
          <div class="d-flex align-content-center flex-column">
            <div class="m-auto">
              <label class="d-flex">
                {{i18n('Activate album')}}
                <div class="checkbox-wrapper-31">
                  <input type="checkbox" v-model="directory.active"/>
                  <svg viewBox="0 0 35.6 35.6">
                    <circle class="background" cx="17.8" cy="17.8" r="17.8"></circle>
                    <circle class="stroke" cx="17.8" cy="17.8" r="14.37"></circle>
                    <polyline class="check" points="11.78 18.12 15.55 22.23 25.17 12.87"></polyline>
                  </svg>
                </div>
              </label>
            </div>
            <button class="button_first m-auto mt-3 mb-3 px-5" type="submit" @click.prevent="updateDir()">
              {{ i18n('Save') }}
              <b-spinner small v-if="saving"></b-spinner>
            </button>
          </div>
        </div>
      </form>
      <form class="add-course-form">
        <h5 class="text-center mt-3">{{i18n('Add photos')}}</h5>
        <div class="d-flex inputs-wrapper">
          <div class="d-flex justify-content-around file-input-wrapper">
            <div class="imgUp shadow" v-for="(file,key) in files">
              <label class="w-100">
                <input :type="'file'" :id="'input-'+key" multiple="multiple"   accept='image/jpeg , image/jpg, image/gif, image/png' @change="files[key] = onFileSelected($event,file,key); resetInput(key)" class="uploadFile img custom-file-input" >
                {{ files[key] ? (files[key].length > 1 ? files[key].length +' '+i18n('selected images') : files[key][0].name): i18n('Choose image') }}
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
          <div class="d-flex align-content-center flex-column">
            <button class="button_first m-auto mt-3 mb-3 px-5" id="store-imgs" type="submit" @click.prevent="saveImg()">
              {{ i18n('Save') }}
              <b-spinner small v-if="savingImg"></b-spinner>
            </button>
          </div>
        </div>
      </form>
    </div>
    <alert v-if="error.length > 0 && editable" :error="error"  @close="error = ''"></alert>
    <template v-if="directory.media.length > 0">
      <div v-if="editable" class="d-flex mt-5">
        <a class="button_first" :href="route('teacher.download-album', {
        dir: {
            slug:this.directory.slug,
            id: this.directory.id
          }
      })">
          <i class="bi bi-download"></i>
        </a>
        <button v-if="directory.media.length > 0" type="button" class="button_first mx-3" @click="selectAllImgs">
          <template v-if="canSelectImg()">
            {{i18n('Select all')}}
          </template>
          <template v-if="!canSelectImg()">
            {{i18n('Deselect all')}}
          </template>
        </button>
        <button v-if="selectedImgs.length > 0" type="button" class="button_first" @click="deleteImgs" :title="i18n('Delete directories')">
          <i class="bi bi-trash3-fill"></i>
        </button>
      </div>
      <div class="course-wrapper">
        <div class="course-card" v-for="(image, key) in directory.media">
          <div class="course-item shadow img-item" :class="isImgSelected(image.id) ? 'active-img-item' : ''">
            <a :href="image.original_url" data-fancybox="gallery" :data-caption="image.name">
              <div class="course-bg" :style="'background-image: url('+image.original_url+')'">
                <img class="d-none"  :src="image.original_url" alt="">
              </div>
            </a>
            <div class=" image-detail">
              <div class="d-flex justify-content-between">
                <div>
                  <strong v-b-tooltip.hover :title="image.name.length > 22 ? image.name:''">{{ truncateContent(image.name,22) }}</strong>
                </div>
                <div v-if="editable" class="d-flex">
                  <div class="checkbox-wrapper-31">
                    <input type="checkbox" :value="image.id" v-model="selectedImgs"/>
                    <svg viewBox="0 0 35.6 35.6">
                      <circle class="background" cx="17.8" cy="17.8" r="17.8"></circle>
                      <circle class="stroke" cx="17.8" cy="17.8" r="14.37"></circle>
                      <polyline class="check" points="11.78 18.12 15.55 22.23 25.17 12.87"></polyline>
                    </svg>
                  </div>
                  <div>
                    <i class="bi bi-x-lg" @click="deleteImg(image.id,image.name)"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
    <template v-else>
      <no-results
          :header="i18n('No image found')"
          :body="i18n('There are no images in this album yet. Try adding some!')"
      ></no-results>
    </template>
  </div>
</template>

<script>
import courseFormMixin from "./courseFormMixin";
import {i18n, toast} from "../app";
import Alert from "./Alert";
import NoResults from "./NoResults";
import truncateMixin from "./truncateMixin";
export default {
  components: {NoResults, Alert},
  mixins: [courseFormMixin,truncateMixin],
  props: ['dir','editable'],
  data() {
    return {
      directory: this.dir ? _.cloneDeep(this.dir) : null,
      selectedImgs: [],
      error: '',
      saving: false,
      savingImg: false,
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
      this.savingImg = true;
      axios
          .post(this.route('teacher.store-img'), formData)
          .then((response) => {
            this.clearFile('img');
            this.directory = response.data;
            this.files.img = null;
            this.savingImg = false;
             toast.success(i18n('Course has been sucessfully created'),null);
          })
          .catch((error) => {
             toast.error(i18n('Error'),error);
          })
    },
    deleteImg(id,name) {
      if (confirm(i18n('Are you sure you want to delete course: ') + ' "' + name + '"?' )) {
        axios
            .post(this.route('teacher.delete-img'), {dirId: this.directory.id, id: id})
            .then((response) => {
              this.directory = response.data;
              this.saving = false;
              toast.success(i18n('Course has been sucessfully deleted'),null);
            })
            .catch((error) => {
              toast.error(i18n('Error'),i18n('err'));
            })
      }
    },
    updateDir() {
      if (this.directory.name.length === 0){
        this.error = i18n('Name is requaired');
      } else if (this.directory.name.length > 119){
        this.error = i18n('The name is too long!');
      } else if (this.directory.description.length > 499){
        this.error = i18n('Description is too long!');
      } else if(this.directory.name === this.dir.name && this.directory.description === this.dir.description && this.directory.active === this.dir.active){
        this.error = i18n('A change is required for save');
      } else {
        this.saving = true
        axios
            .post(this.route('teacher.update-album'), {
              id: this.directory.id, dir: {
                'name': this.directory.name,
                'description': this.directory.description,
                'active': this.directory.active
              }
            })
            .then((response) => {
              if (!response.data){
                this.error = i18n('There is already album with this name');
              }
              else {
                if (!Array.isArray(response.data)) {
                  this.editCourse = response.data[0];
                } else {
                  this.editCourse = response.data[0]
                  history.replaceState(response.data, '', '/teacher/gallery/' + response.data[1]);
                }
                toast.success(i18n('Album has been sucessfully updated'), null);
              }
              this.saving = false;
            })
            .catch((error) => {
              toast.error(i18n('Error'),i18n('err'));
            })
      }
    },
    canSelectImg() {
      return !(_.size(this.selectedImgs) === _.size(this.directory.media));
    },
    isImgSelected(img) {
      return _.find(this.selectedImgs, (selected) => {
        return selected === img;
      });
    },
    selectAllImgs() {
      if (this.canSelectImg()){
        this.selectedImgs = _.map(this.directory.media, (media) => {
          return media.id;
        })
      }
      else {
        this.selectedImgs = [];
      }
    },
    deleteImgs() {
      if (confirm(i18n('Are you sure you want to delete course: ') + ' ?' )) {
        axios
            .post(this.route('teacher.delete-imgs'), {dirId: this.directory.id, imgs: this.selectedImgs})
            .then((response) => {
              this.directory = response.data;
              this.selectedImgs = [];
              this.saving = false;
              toast.success(i18n('Course has been sucessfully deleted'),null);
            })
            .catch((error) => {
              toast.error(i18n('Error'),i18n('err'));
            })
      }
    },

    fn() {
      axios
      .get(this.route('teacher.download-album'),{
        params: {
          dir: {
            slug:this.directory.slug,
            id: this.directory.id
          }
        }
      })
    },
  },
}
</script>

<style scoped>

</style>