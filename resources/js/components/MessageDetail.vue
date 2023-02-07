<template>
  <b-modal @hide="emitClose" size="lg" scrollable visible no-close-on-backdrop hide-footer>
    <template #modal-header="{ close }">
      <div>
        <h5>{{ header }}</h5>
        <template  v-if="loading === false">
          <i v-show="msg.attachments" class="bi bi-paperclip"></i>
          <i v-show="msg.mail" class="bi bi-envelope-at-fill"></i>
        </template>
      </div>
      <i @click="close()" class="bi bi-x-lg"></i>
    </template>
    <div class="m-modal-body">
      <div class="w-100 m-auto">
        <template v-if="loading">
          <div class="d-flex">
            <div class=" loading-wrapper m-auto">
              <b-spinner></b-spinner>
              <p>{{i18n('Loading...')}}</p>
            </div>
          </div>
        </template>
        <template v-else>
          <div class="d-flex flex-row position-relative from-to flex-wrap">
            <i v-if="msg.sender.role === 0" class="bi bi-person-fill role-ico"></i>
            <i v-if="msg.sender.role === 1" class="bi bi-book-half role-ico"></i>
            <i v-if="msg.sender.role === 2" class="bi bi-shield-shaded role-ico"></i>
            <span><b>{{msg.sender.name === 'Me' ? i18n(msg.sender.name): msg.sender.name }}</b></span>
            <i class="bi bi-arrow-right to-icon color-red"></i>
            <i v-if="msg.groups" class="bi bi-people-fill role-ico"></i>
            <span v-if="msg.groups === 'all'">{{i18n('All participants')}}</span>
            <span v-if="msg.groups === 'selected'">{{i18n('Course participants')}}</span>
            <template v-if="msg.users">
              <span v-if="msg.groups" class="comma-span">,</span>
              <div v-for="(user, index) in msg.users" v-if="index < (msg.groups ? 1 : 2)">
                <i v-if="user.role === 0" class="bi bi-person-fill role-ico"></i>
                <i v-if="user.role === 1" class="bi bi-book-half role-ico"></i>
                <i v-if="user.role === 2" class="bi bi-shield-shaded role-ico"></i>
                <span>{{user.name}}</span>
                <span class="comma-span" v-if="index < (msg.groups ? 0: 1) && msg.users.length > 1">,</span>
              </div>
              <span class="plus-another" v-if="msg.users.length > (msg.groups ? 1 : 2)">+{{msg.users.length - (msg.groups ? 1 : 2)}}</span>
            </template>
          </div>
          <hr>
          <div class="d-flex justify-content-between flex-wrap">
            <h4 class="m-none text-break">{{msg.subject}}</h4>
            <p>{{ formatToUpperCase(formatDate(msg.updated_at, 'cccc')) +' '+ formatDate(msg.updated_at, 'd.M.yyyy - H:mm ')  }}</p>
          </div>
          <p class="msg-content">{{msg.content}}</p>
          <template v-if="msg.media.length > 0">
            <div class="msg-attachments">
              <p>{{i18n('Attachments')+':'}}</p>
              <div class="attachments-wrapper">
                <div v-for="file in msg.media">
                  <i class="bi bi-paperclip"></i>
                  <a :href="file.original_url" target="_blank">{{file.file_name}}</a>
                </div>
              </div>
            </div>
          </template>
          <div>
            <hr>
            <div class="d-flex flex-wrap">
              <p>{{msg.sender.name}}&nbsp;-&nbsp;</p>
              <a class="" :href="'mailto:'+msg.sender.email">{{msg.sender.email}}</a>
              <p v-if="msg.sender.role === 1">&nbsp;-&nbsp;{{ i18n('Instructor of course')+': '+msg.course.name }}</p>
            </div>
          </div>
        </template>
      </div>
    </div>
  </b-modal>
</template>

<script>
import formatDatesMixin from "./formatDatesMixin";

export default {
  mixins: [formatDatesMixin],
  props: ['header','msgId'],
  data(){
    return {
      msg: null,
      loading: true,
    }
  },
  methods: {
    emitClose() {
      setTimeout(() => {
        this.$emit('close');
      }, 300);
    },
    setMsgData() {
      this.loading = true;
      axios
          .get(this.route('get-message'), { params: { id: this.msgId},})
          .then((response) => {
            this.msg = this.formatObjectDates(response.data);
            this.loading = false;
          })
    },
  },
  mounted() {
    this.setMsgData();
  }
}
</script>

<style scoped>

</style>