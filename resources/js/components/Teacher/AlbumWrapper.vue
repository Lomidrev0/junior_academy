<template>
<div>
  <button class="btn btn-primary" @click="show = !show">
    <i class="bi bi-plus-lg"></i>
    {{i18n('Add')}}
  </button>
  <div v-show="show">
    <form key="saveAlbum" class="add-course-form">
      <div class="d-flex inputs-wrapper">
        <div class="d-flex flex-column text-input-wrapper m-auto w-75">
          <div>
            <input :type="'text'" class="form-control shadow" :placeholder="i18n('Course name') + ' *'" v-model="newAlbum.name">
          </div>
          <div>
            <textarea class="form-control shadow" rows="3" :placeholder="i18n('Short course description')" v-model="newAlbum.description"></textarea>
          </div>
        </div>
      </div>
      <div>
        <div class="d-flex align-content-center flex-column">
          <div class="m-auto mt-3">
            <label class="d-flex">
              Aktivovať kurz:
              <div class="checkbox-wrapper-31">
                <input type="checkbox" v-model="newAlbum.isActive"/>
                <svg viewBox="0 0 35.6 35.6">
                  <circle class="background" cx="17.8" cy="17.8" r="17.8"></circle>
                  <circle class="stroke" cx="17.8" cy="17.8" r="14.37"></circle>
                  <polyline class="check" points="11.78 18.12 15.55 22.23 25.17 12.87"></polyline>
                </svg>
              </div>
            </label>
          </div>
          <button class="button_first m-auto mt-3 mb-3 px-5" type="submit" @click.prevent="saveAlbum()">
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
  <div class="w-100">
    <div class="course-wrapper">
      <template v-if="albumList.length > 0">
        <div class="course-card" v-for="(album, key) in albumList">
          <div class="course-item shadow">
            <div class="course-card-body">
              <div class="course-item-head d-flex justify-content-between">
                <a :href="route('teacher.directory', {slug: album.slug})">
                  <div>
                    <strong v-b-tooltip.hover :title="album.name">{{truncateContent(album.name, 30)}}</strong>
                  </div>
                </a>
                <div>
                  <label class="switch">
                    <input type="checkbox" class="course-toggle" v-model="album.active" @click="updateAlbum(album, key, $event)">
                    <span class="slider round"></span>
                  </label>
                  <i class="bi bi-x-lg" @click="deleteAlbum(album.id,album.name, album.disk)"></i>
                </div>
              </div>
              <a class="disabled" :href="route('teacher.directory', {slug: album.slug})">
                <div class="course-item-body flex-column text-center">
                  <div v-b-tooltip.hover :title="album.description"> {{ truncateContent(album.description, 30) }}</div>
                  <div>Vytvorene: {{ formatDate(album.created_at, 'dd.MM.yyyy') }}</div>
                </div>
                <div class="course-item-footer d-flex justify-content-around">
                  <span v-b-tooltip.hover :title="album.user.name">{{ truncateContent(album.user.name, 15) }}</span>
                  <span>{{ formatDate(album.updated_at, 'H:mm - dd.MM.yyyy') }}</span>
                </div>
              </a>
            </div>
          </div>
        </div>
      </template>
      <template v-else>
        <div>
          <p>Zatial zaiadne albumy</p>
        </div>
      </template>
    </div>
  </div>
</div>
</template>

<script>
import {i18n} from "../../app";
import formatDatesMixin from "../formatDatesMixin";
import truncateMixin from "../truncateMixin";
import {parseISO} from "date-fns";

export default {
  mixins:[formatDatesMixin, truncateMixin],
  props: ['albums'],
  data(){
    return {
      show: false,
      albumList: this.albums ? (this.albums.length > 0 ? _.cloneDeep(this.albums) : null) : null,
      saving: false,
      error: '',
      newAlbum: {
        isActive: false,
        name: '',
        description: '',
      },
    }
  },
  methods: {
    saveAlbum() {
      this.saving = true;
      axios
      .post(this.route('teacher.save-album'),{newAlbum: this.newAlbum, disk: 'gallery'})
      .then((response) => {
        this.clearForm();
        this.saving = false;
        this.albumList = this.formatDates(response.data);
        this.$toast.success(i18n('Course has been sucessfully updated'));
      })
    },
    deleteAlbum(id,name,disk) {
      if (confirm(i18n('Are you sure you want to delete course: ') + ' "' + name + '"?' )) {
        axios
            .post(this.route('teacher.delete-album'), {id: id, name: name, disk: disk })
            .then((response) => {
              console.log(response.data)
              this.albumList = this.formatDates(response.data);
              this.$toast.success(i18n('Course has been successfully deleted'));
            })
      }
    },
    updateAlbum(album, key, event){
      if (confirm( i18n('Are you sure you want to change status of the course: ') + ' "' + album.name + '"?' )) {
        axios
            .post(this.route('teacher.update-active'), {id: album.id, value: !album.active})
            .then((response) => {
              this.$set(this.albumList, key, this.formatObjectDates(response.data));
              this.$toast.success(i18n('Course has been sucessfully updated'));
            })
      }
      else {
        event.preventDefault();
        event.stopPropagation();
      }
    },
    clearForm(){
      this.newAlbum.name = '';
      this.newAlbum.description = '';
      this.newAlbum.isActive = false;
    }
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
    this.albumList = this.formatDates(this.albumList);
  }
}
</script>

<style scoped>

</style>