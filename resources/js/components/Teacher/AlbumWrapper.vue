<template>
<div>
  <button class="button_first" @click="show = !show">
    <i class="bi bi-plus-lg"></i>
    {{i18n('Add album')}}
  </button>
  <div v-show="show">
    <form key="saveAlbum" class="add-course-form">
      <div class="d-flex inputs-wrapper">
        <div class="d-flex flex-column text-input-wrapper m-auto w-75">
          <div>
            <input :type="'text'" class="form-control shadow" :placeholder="i18n('Album name')" v-model="newAlbum.name">
          </div>
          <div>
            <textarea class="form-control shadow" rows="3" :placeholder="i18n('Short album description')" v-model="newAlbum.description"></textarea>
          </div>
        </div>
      </div>
      <div>
        <div class="d-flex align-content-center flex-column">
          <div class="m-auto mt-3">
            <label class="d-flex">
             {{i18n('Activate album')}}
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
            {{ i18n('Save album') }}
            <b-spinner small v-if="saving"></b-spinner>
          </button>
        </div>
      </div>
    </form>
    <alert v-if="error.length > 0" :error="error"  @close="error = ''"></alert>
  </div>
  <div class="w-100">
    <div class="course-wrapper">
      <template v-if="albumList.length > 0">
        <div class="course-card" v-for="(album, key) in albumList">
          <div class="course-item shadow">
            <div v-if="album.media.length > 0" class="course-bg" :style="'background-image: url('+album.media[0].original_url+')'"></div>
            <div v-else class="course-bg" style="background-image: url('/images/default_img.png')"></div>
            <div class="course-card-body">
              <div class="course-item-head d-flex justify-content-between">
                <a :href="route('teacher.directory', {slug: album.slug})">
                  <div>
                    <strong v-b-tooltip.hover :title="album.name.length >= 25 ? album.name : ''">{{truncateContent(album.name, 25)}}</strong>
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
                <div class="course-item-body flex-column text-center position-relative">
                  <div class="album-text-wrapper">
<!--                    <div v-b-tooltip.hover :title="album.description.length >= 30 ? album.description : ''"> {{ truncateContent(album.description, 30) }}</div>-->
                    <div>{{i18n('Created at')}} {{ formatDate(album.created_at, 'dd.MM.yyyy') }}</div>
                    <div><i class="bi bi-image-fill px-2"></i>{{album.media_count}}</div>
                  </div>
                </div>
                <div class="course-item-footer d-flex justify-content-around">
                  <span v-b-tooltip.hover :title="album.user.name.length >= 15 ? album.user.name : ''">{{ truncateContent(album.user.name, 15) }}</span>
                  <span>{{ formatDate(album.updated_at, 'H:mm - dd.MM.yyyy') }}</span>
                </div>
              </a>
            </div>
          </div>
        </div>
      </template>
      <template v-else>
        <no-results
            :header="i18n('No albums found')"
            :body="i18n('This course has no albums yet. Try adding some!')"
        ></no-results>
      </template>
    </div>
  </div>
</div>
</template>

<script>
import {i18n, toast} from "../../app";
import formatDatesMixin from "../formatDatesMixin";
import truncateMixin from "../truncateMixin";
import {parseISO} from "date-fns";
import Alert from "../Alert";
import NoResults from "../NoResults";

export default {
  components: {NoResults, Alert},
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
      if (this.newAlbum.name.length > 119){
        this.error = i18n('The name is too long!');
      } else if(this.newAlbum.name.length === 0){
        this.error = i18n('Name is requaired');
      }else if(this.newAlbum.name.length > 499) {
        this.error = i18n('Description is too long!');
      }else if(_.find(this.albumList, (album) => { return album.name === this.newAlbum.name })){
        this.error = i18n('There is already album with this name');
      }
      else {
        this.saving = true;
        axios
            .post(this.route('teacher.save-album'),{newAlbum: this.newAlbum, disk: 'gallery'})
            .then((response) => {
              this.clearForm();
              this.saving = false;
              this.albumList = this.formatDates(response.data);
              toast.success(i18n('Album has been sucessfully createed'),null);
            })
      }

    },
    deleteAlbum(id,name,disk) {
      if (confirm(i18n('Are you sure you want to delete course: ') + ' "' + name + '"?' )) {
        axios
            .post(this.route('teacher.delete-album'), {id: id, name: name, disk: disk })
            .then((response) => {
              this.albumList = this.formatDates(response.data);
              toast.success(i18n('Album has been sucessfully deleted'),null);
            })
      }
    },
    updateAlbum(album, key, event){
      if (confirm( i18n('Are you sure you want to change status of the course: ') + ' "' + album.name + '"?' )) {
        axios
            .post(this.route('teacher.update-active'), {id: album.id, value: !album.active})
            .then((response) => {
              this.$set(this.albumList, key, this.formatObjectDates(response.data));
              toast.success(i18n('Album has been sucessfully updated'),null);
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