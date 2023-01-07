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
          <button type="submit" @click.prevent="saveAlbum()">
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
        <div>
<!--          <button type="submit" @click.prevent="saveAlbum()">-->
<!--            <template>-->
<!--              {{ i18n('Save') }}-->
<!--            </template>-->
<!--            <b-spinner small v-if="saving"></b-spinner>-->
<!--          </button>-->
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
<!--        v-if="imageList.length > 0"-->
        <template>
<!--          <div class="course-card" v-for="(image, key) in imageList">-->
<!--            <div class="course-item">-->
<!--              <div class="course-card-body">-->
<!--                <div class="course-item-head d-flex justify-content-between">-->
<!--                  <a :href="route('teacher.directory', {slug: image.slug})">-->
<!--                    <div>-->
<!--                      <strong v-b-tooltip.hover :title="album.name">{{truncateContent(image.name, 30)}}</strong>-->
<!--                    </div>-->
<!--                  </a>-->
<!--                  <div>-->
<!--                    <label class="switch">-->
<!--                      <input type="checkbox" class="course-toggle" v-model="album.active" @click="updateAlbum(album, key, $event)">-->
<!--                      <span class="slider round"></span>-->
<!--                    </label>-->
<!--                    <i class="bi bi-x-lg" @click="deleteAlbum(album.id,album.name, album.disk)"></i>-->
<!--                  </div>-->
<!--                </div>-->
<!--                <a class="disabled" href="">-->
<!--                  <div class="course-item-body flex-column text-center">-->
<!--                    <div v-b-tooltip.hover :title="album.description"> {{ truncateContent(album.description, 30) }}</div>-->
<!--                    <div>Vytvorene: {{ formatDate(album.created_at, 'dd.MM.yyyy') }}</div>-->
<!--                  </div>-->
<!--                  <div class="course-item-footer d-flex justify-content-around">-->
<!--                    <span v-b-tooltip.hover :title="album.user.name">{{ truncateContent(album.user.name, 15) }}</span>-->
<!--                    <span>{{ formatDate(album.updated_at, 'H:mm - dd.MM.yyyy') }}</span>-->
<!--                  </div>-->
<!--                </a>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
        </template>
<!--        v-else-->
        <template>
          <div>
            <p>Zatial zaiadne obr</p>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import courseFormMixin from "../courseFormMixin";
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

  }
}
</script>

<style scoped>

</style>