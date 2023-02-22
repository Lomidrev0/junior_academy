<template>
  <div>
    <button @click="show = true" class="button_first">
      <i class="bi bi-pen-fill"></i>
     {{i18n('New Message')}}
    </button>
    <div>
      <message-modal
        v-if="show"
        :header="i18n('New message')"
        :values="values.vals ? values.vals : null"
        :auth="auth"
        :course=" course ? course.name: null"
        @close="closeModal"
        @newMessage="newMessage"
      ></message-modal>
      <message-detail
          v-if="openDetail"
          :header="i18n('Message Detail')"
          :msg-id="selectedMsg"
          @close="openDetail = false"
      >
      </message-detail>
    </div>

    <div class="d-flex flex-column">
      <template v-if="msgs.length !== 0">
        <div v-for="msg in msgs">
          <div :class="compareDates(msg.created_at, parseLastRead) && msg.sender.name !== 'Me' && !isSeen(msg.id) ? 'new-msg' : ''" class="msg-wrapper shadow" @click="compareDates(msg.created_at, parseLastRead) && msg.sender.name !== 'Me' ? selectSeen(msg.id) : '';selectedMsg = msg.id; openDetail = true">
            <div class="msg-header">
              <i v-if="compareDates(msg.created_at, parseLastRead) && msg.sender.name !== 'Me' && !isSeen(msg.id)" class="bi bi-envelope-exclamation-fill msg-icon"></i>
              <i v-if="msg.sender.name === 'Me'" class="bi bi-envelope-check-fill msg-icon"></i>
              <i v-if="(compareDates(msg.created_at, parseLastRead) === false && msg.sender.name !== 'Me') || isSeen(msg.id)" class="bi bi-envelope-paper-fill msg-icon"></i>
              <div class="subject-user">
                <div class="d-flex align-items-end position-relative from-to f-direct-c">
                  <h4 class="msg-subject"><b>{{truncateContent(msg.subject, 60)}}</b></h4>
                  <div class="msg-date d-flex">
                    <h4 class="h-ico" v-if="msg.attachments"><i class="bi bi-paperclip"></i></h4>
                    <h4 class="h-ico" v-if="msg.mail"><i class="bi bi-envelope-at-fill"></i></h4>
                  </div>
                </div>
                <div class="d-flex flex-row position-relative from-to justify-content-between">
                  <div class="d-flex flex-wrap">
                    <i v-if="msg.sender.role === 0" class="bi bi-person-fill role-ico"></i>
                    <i v-if="msg.sender.role === 1" class="bi bi-book-half role-ico"></i>
                    <i v-if="msg.sender.role === 2" class="bi bi-shield-shaded role-ico"></i>
                    <span><b>{{msg.sender.name === 'Me' ? i18n(msg.sender.name): msg.sender.name }}</b></span>
                    <i class="bi bi-arrow-right to-icon color-red"></i>
                    <i v-if="msg.groups" class="bi bi-people-fill role-ico"></i>
                    <template v-if="msg.groups">
                      <span v-if="msg.groups.recipients === 'all'">{{i18n('All participants')}} - {{msg.groups.name}}</span>
                      <span v-if="msg.groups.recipients === 'active'">{{i18n('Course participants')}} - {{msg.groups.name}}</span>
                    </template>
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
                  <p>{{ formatDate(msg.created_at, 'H:mm - dd.MM.yyyy')  }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
      <template v-else>
        <no-results
            :header="i18n('No mail yet')"
            :body="i18n('There are currently no messages in your inbox. Write someone your first message!')"
        ></no-results>
      </template>
    </div>
  </div>
</template>

<script>
import TextInput from "./TextInput";
import SearchInput from "./SearchInput";
import MessageModal from "./MessageModal";
import {i18n} from "../app";
import formatDatesMixin from "./formatDatesMixin";
import truncateMixin from "./truncateMixin";
import MessageDetail from "./MessageDetail";
import {parseISO} from "date-fns";
import isAfter from 'date-fns/isAfter'
import NoResults from "./NoResults";
export default {
  name: 'Chat',
  mixins: [formatDatesMixin, truncateMixin],
  props: ['messages','lastRead','auth','course'],
  components: {NoResults, MessageDetail, MessageModal},
  data(){
    return {
      msgs: this.messages ? _.cloneDeep(this.messages) : null,
      show: false,
      openDetail: false,
      selectedMsg: null,
      seen: [],
      parseLastRead: this.lastRead ? parseISO(this.lastRead) : null,
      values: {},
    }
  },
  methods: {
   closeModal(values){
     this.show = false;
     this.values = values;
   },
    newMessage(data) {
      data = this.formatDates(data)
      this.msgs = _.cloneDeep(data);
    },
   beforeWindowUnload(e) {
     if (!_.isEmpty(this.values)){
       e.preventDefault();
       e.returnValue = '';
     }
   },
    compareDates(date, dateToCompare) {
     return isAfter(date, dateToCompare);
    },
    selectSeen(id){
      if (!_.find(this.seen, (item) => { return item === id; })){
        this.seen.push(id);
      }
    },
    isSeen(id) {
      return _.find(this.seen, (item) => { return item === id; })
    }

 },
  created() {
    window.addEventListener('beforeunload', this.beforeWindowUnload)
    this.msgs = this.formatDates(this.msgs);
  },

  beforeDestroy() {
    window.removeEventListener('beforeunload', this.beforeWindowUnload)
  },
}
</script>

<style scoped>

</style>