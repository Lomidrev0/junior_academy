<template>
  <b-modal size="lg" scrollable visible no-close-on-backdrop >
    <template #modal-header>
        <h5>{{ header }}</h5>
        <i @click="closeModal(vals)" class="bi bi-x-lg"></i>
    </template>
    <div class="m-modal-body">
      <div class="w-100 m-auto">
        <template v-if="vals.selected">
          <div class="selected-items-wrapper">
            <div class="selected-items" v-for="(item) in vals.selected">
              <div class="selected-item">
                <span>{{truncateContent(item.name,25)}}</span>
                <i @click="removeFromSelected(item.id)" class="bi bi-x"></i>
              </div>
            </div>
            <div class="selected-items" v-if="vals.multipleSelect.text">
              <div class="selected-item">
                <span>{{truncateContent(vals.multipleSelect.text, 25)}}</span>
                <i @click="vals.multipleSelect.text = ''; vals.multipleSelect.value = '' " class="bi bi-x"></i>
              </div>
            </div>
          </div>
        </template>
      </div>
      <div class="w-100 m-auto">
        <div class="search-bar-radius d-flex">
          <div class="search-input">
            <div class="search-icon">
              <i class="bi bi-search"></i>
            </div>
            <search-input
                ref="searchInput"
                :placeholder="i18n('Click to start adding recipients')"
                :api-url="this.route('teacher.user-search')"
                :take="30"
                :skip="0"
                :initial-search="false"
                :search-on-focus="true"
                :val="vals.value"
                @result="result"
                @focus="openResults"

            ></search-input>
          </div>
          <div class="search-buttons-wrapper">
            <div class="only-active" @click="vals.multipleSelect.text = i18n('Course participants'); vals.multipleSelect.value = 'activeUsers'">
              <span>{{i18n('Course participants')}}</span>
            </div>
            <div class="all" @click="vals.multipleSelect.text = i18n('All participants'); vals.multipleSelect.value = 'allUsers'">
              <span>{{i18n('All participants')}}</span>
            </div>
          </div>
        </div>
      </div>
      <template >
        <div v-if="searchResult" class="result-wrapper shadow" v-click-outside="closeResults">
          <div v-for="(result) in searchResult">
            <div @click.prevent="select(result)">
              <i v-if="result.role === 0" class="bi bi-person-fill"></i>
              <i v-if="result.role === 1" class="bi bi-book-half"></i>
              <i v-if="result.role === 2" class="bi bi-shield-shaded"></i>
              <span>{{result.name}}</span>
            </div>
          </div>
          <div class="total-records" v-if="totalResultCount > searchResult.length">
            <span>{{'+ '+(totalResultCount-searchResult.length)+' '+ i18n('Records')}}</span>
          </div>
          <div class="total-records" v-if="searchResult.length === 0">
            <span>{{ i18n('No results')}}</span>
          </div>
        </div>
      </template>
      <div class="msg-input-wrapper">
        <div class="w-100 m-auto msg-input">
          <label>
            <input v-model="vals.title" :placeholder="i18n('Subject')" type="text" class="w-100 mh-100">
          </label>
        </div>
        <div class="w-100 m-auto msg-input">
          <label>
            <textarea v-model="vals.content" :placeholder="i18n('Message content')" rows="12" class="w-100 mh-100"></textarea>
          </label>
        </div>
        <div class="w-100 m-auto msg-input">
          <label class="attachments">
            <i class="bi bi-paperclip"></i>
            <span>{{i18n('Add attach')}}</span>
            <input id="attachments-input"  @change="vals.files = onFileSelected($event); resetInput()" type="file" multiple="multiple" class="w-100 mh-100">
          </label>
        </div>
        <div class="w-100 m-auto">
          <template v-if="vals.files">
            <div class="selected-items-wrapper">
              <div class="selected-items" v-for="(file) in vals.files">
                <div class="selected-item">
                  <span>{{truncateContent(file.name, 25)}}</span>
                  <i @click="removeFile(file.name)" class="bi bi-x"></i>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>
    <template #modal-footer>
        <div class="modal-err-wrapper">
          <alert v-if="error.length > 0" :error="error" :off-margin="true"  @close="error = ''"></alert>
        </div>
      <div>
        <button class="button_first" @click="clear()">
          {{i18n('Clear')}}
        </button>
        <button class="button_first" @click="sendMsg(vals)">
          {{i18n('Send meessage')}}
        </button>
      </div>
      </template>
  </b-modal>
</template>

<script>
import SearchInput from "./SearchInput";
import TextInput from "./TextInput";
import truncateMixin from "./truncateMixin";
import Alert from "./Alert";
import {i18n, toast} from "../app";

export default {
  components: {Alert, SearchInput, TextInput},
  mixins:[truncateMixin],
  props: ['header','values'],
  data(){
    return {
      searchResult: null,
      error:'',
      totalResultCount:null,
      vals: {
        selected: [],
        title: '',
        content: '',
        value: null,
        files: [],
        multipleSelect: {
          text: '',
          value: '',
        },
      },
    }
  },
  methods: {
    result(result){
      this.searchResult = result.items;
      this.vals.value = result.val;
      this.totalResultCount = result.totalCount;
    },
    closeResults(){
      this.searchResult = null;
      $('.search-icon').removeClass('search-focus')
    },
    openResults(){
      $('.search-icon').addClass('search-focus')
    },
    select(user){
      if (!_.find(this.vals.selected, (item) => { return item.id === user.id; })){
        this.vals.selected.push(user);
        this.searchResult = null;
      }
    },
    removeFromSelected(id) {
      this.vals.selected =_.remove(this.vals.selected, (item) => { return item.id !== id; });
    },
    closeModal(vals){
      if (_.isEqual(this.$data.vals, this.$options.data().vals)) {
        this.$emit('close',{});
      }
      else {
        this.$emit('close', { vals });
      }
    },
    onFileSelected(event) {
     _.find(event.target.files, (file) => { return file.size > 30000000}) ?  this.error = 'Niektoré zo súborov sú príliš veľké. Maximálna povolená veľkosť súboru je 30MB': this.error= '';
     if (this.vals.files.length > 0) {
       if (event.target.files.length > this.vals.files.length) {
         return _.differenceWith(Array.from(_.filter(event.target.files, (file) => { return file.size <= 30000000 })),this.vals.files,_.isEqual);
       }
     else {
         return _.differenceWith(this.vals.files, Array.from(_.filter(event.target.files, (file) => { return file.size <= 30000000 })),_.isEqual);
       }
     }
     else {
       return _.concat(this.vals.files, Array.from(_.filter(event.target.files, (file) => { return file.size <= 30000000 })));
     }
    },
    removeFile(name){
      this.vals.files =_.remove(this.vals.files, (file) => { return file.name !== name; });
    },
    resetInput(){
      document.getElementById('attachments-input').value = null;
    },
    clear(){
      Object.assign(this.$data, this.$options.data())
      this.$refs.searchInput.searchOnlyWhenNotEmpty = true;
      this.$refs.searchInput.clear();
      this.$refs.searchInput.searchOnlyWhenNotEmpty = false;
    },
    beforeWindowUnload(e) {
      if (!_.isEqual(this.$data.vals, this.$options.data().vals)){
        e.preventDefault();
        e.returnValue = '';
      }
    },
    sendMsg(message){
      const formData = new FormData();
      let data = _.cloneDeep(message);
       _.unset(data,'value');
      data.selected = _.map(data.selected, (select)=>{return select.id});
      data.selected = JSON.stringify(data.selected);
      data.multipleSelect = data.multipleSelect.value;
      _.forEach(data.files, (file,key) => {return  formData.append('file'+key,file);})
      _.unset(data,'files')
      formData.append('data',JSON.stringify(data));
      axios
      .post(this.route('send-message'), formData)
      .then((response) =>{
        this.clear();
        this.$emit('close',{});
        this.$emit('newMessage', response.data );
        toast.success(i18n('Course has been sucessfully deleted'),null);
      })

    },
  },
  mounted() {
    if (this.values){
      this.vals.selected = this.values.selected;
      this.vals.title =  this.values.title;
      this.vals.content = this.values.content;
      this.vals.value = this.values.value;
      this.vals.multipleSelect = this.values.multipleSelect;
      this.vals.files = this.values.files;
    }
  },
  created() {
    window.addEventListener('beforeunload', this.beforeWindowUnload)
  },

  beforeDestroy() {
    window.removeEventListener('beforeunload', this.beforeWindowUnload)
  },
}
</script>