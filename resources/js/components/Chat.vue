<template>
  <div>
    <button @click="show = true" class="button_first">
      <i class="bi bi-pen-fill"></i>
      napisat mgs
    </button>
    <div>

  <message-modal
    v-if="show"
    :header="i18n('New message')"
    :values="values.vals ? values.vals : null"
    @close="closeModal"
    @newMessage="newMessage"
  ></message-modal>

    </div>

    <div class="d-flex flex-column">
      <template v-if="msgs.length !== 0">
        <div v-for="msg in msgs">
          <div class="msg-wrapper shadow">
            <div class="msg-header">
              <i class="bi bi-envelope-fill msg-icon color-green"></i>
              <div class="subject-user">
                <div class="d-flex align-items-end position-relative from-to f-direct-c">
                  <h4><b>{{msg.subject}}</b></h4>
                  <h4 v-if="msg.attachments" class="msg-date"><i class="bi bi-file-earmark"></i></h4>
                </div>
                <div class="d-flex flex-row position-relative from-to">
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
                  <p class="msg-date">{{ formatDate(msg.updated_at, 'H:mm - dd.MM.yyyy')  }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
      <template v-else>
        <div class="text-center">
          <h3>Zatial ziadne spravy</h3>
        </div>
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
export default {
  mixins: [formatDatesMixin, truncateMixin],
  props: ['messages'],
  components: {MessageModal},
  data(){
    return {
     msgs: this.messages ? _.cloneDeep(this.messages) : null,
     show: false,
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