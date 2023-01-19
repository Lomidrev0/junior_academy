<template>
    <div class="table-wrapper shadow">
      <div class="search-bar d-flex ">
        <div class="search-icon"> <i class="bi bi-search"></i> </div>
        <input type="text" v-model="search" placeholder="Search...">
      </div>
      <template v-if="admin">
        <ul>
          <li :class=" (select === index) ? 'active-course':'unactive-course'" v-for="(course, index) in courses" @click="select = index">
            <p>{{ course.name +' ('+course.users.length+')' }}</p>
          </li>
        </ul>
      </template>
      <div class="overflow-auto">
        <table class="table">
          <thead class="thead-dark">
          <tr>
            <th><p>{{i18n('Name and surname')}}</p></th>
            <th><p>{{i18n('School')}}</p></th>
            <th><p>{{i18n('Class')}}</p></th>
            <th><p>{{i18n('Email')}}</p></th>
            <th><p>{{i18n('Registration date')}}</p></th>
            <template v-if="admin === false">
              <th><p>{{i18n('Active')}}</p></th>
              <th><p>{{i18n('Notes')}}</p></th>
              <th><p>{{i18n('Delete')}}</p></th>
            </template>
          </tr>
          </thead>
          <tbody>
          <template v-for="(course, index) in computedCourses">
            <tr v-for="(user, key) in course.users">
              <template v-if="index === select">
                <td><p>{{user.name}}</p></td>
                <td><p>{{JSON.parse(user.student_info).school}}</p></td>
                <td><p>{{ JSON.parse(user.student_info).class}}</p></td>
                <td><p><a :href="'mailto:'+user.email">{{ user.email }}</a></p></td>
                <td><p>{{ formatDate(user.created_at, 'H:mm - dd.MM.yyyy') }}</p></td>
                <template v-if="admin === false">
                  <td class="position-relative">
                    <label class="switch position-absolute">
                      <input type="checkbox" class="course-toggle" v-model="JSON.parse(user.student_info).active" @click="updateMember(user, key)">
                      <span class="slider round"></span>
                    </label>
                  </td>
                  <td>
                    <div class="notify-div">
                      <i v-b-modal.modal-scrollable class="bi bi-file-earmark-text-fill" @click="setUpdateNote(JSON.parse(user.student_info).notes, user.id, key)"></i>
                      <div class="notify-dot" v-if="JSON.parse(user.student_info).notes"></div>
                    </div>
                  </td>
                  <td><p><i class="bi bi-x-lg" @click=" deleteMember(user.id,user.name)"></i></p></td>
                </template>
              </template>
            </tr>
          </template>
          </tbody>
        </table>
      </div>
      <div>
        <b-modal id="modal-scrollable" scrollable>
          <template #modal-header="{ close }">
            <h5>Modal Header</h5>
            <i class="bi bi-x-lg" @click="close(); clearNote()"></i>
          </template>
          <div class="w-100 m-auto">
            <textarea class="w-100 mh-100" v-model="updateNote.note"></textarea>
          </div>
          <template #modal-footer="{ ok, cancel, hide }">
            <template v-if="error.length > 0">
              <div class="alert alert-danger my-3 mx-4" role="alert">
                {{ error }}
                <div @click="error = ''" class="d-md-inline">
                  <i class="bi bi-x-lg"></i>
                </div>
              </div>
            </template>
            <b-button size="sm" variant="success" @click="saveNote(); ok()">
              OK
            </b-button>
          </template>
        </b-modal>
      </div>
    </div>
</template>

<script>
import {parseISO} from 'date-fns';
import {i18n} from "../app";
export default {
  props:['courses', 'admin'],
  data (){
    return{
      select: 0,
      dataCourses: this.courses,
      search:'',
      error: '',
      updateNote: {
        note:'',
        id: null,
        key: null,
      },
    }
  },
  computed: {
    computedCourses(){
      return _.map(this.dataCourses, (course) => {
        return {
          ...course,
          users: [
            ..._.filter(course.users, (user) => {
              if((user.name.toUpperCase().match(this.search.toUpperCase()) ||
                  user.email.toUpperCase().match(this.search.toUpperCase()))
                 || (JSON.parse(user.student_info).school.toUpperCase().match(this.search.toUpperCase())
                 || JSON.parse(user.student_info).class.toUpperCase().match(this.search.toUpperCase()))) {
                return user
              }
            })
          ]
        }
      })
    }
  },
  methods: {
    getSchool(json){
      return json.school;
    },
    getClass(json){
      return json.class;
    },

    formatDates() {
      this.dataCourses = _.map(this.dataCourses, (course) => {
        return {
          ...course,
          users: [
              ..._.map(course.users, (user) => {
                return {
                  ...user,
                  created_at: parseISO(user.created_at)
                }
              })
          ]
        }
      });
    },
    updateMember(user,key){
      $('input').prop( "disabled", true);
      axios
          .post(this.route('teacher.update-active-member'), {id: user.id,value: !JSON.parse(user.student_info).active})
          .then((response) => {
            let data =response.data
            data.created_at = parseISO(data.created_at);
            data.student_info = JSON.stringify(data.student_info);
            this.$set(this.dataCourses[0].users,key, data);
            setTimeout(function() {
              $('input').prop( "disabled", false);
            }, 1000);
            this.$toast.success(i18n('Course has been sucessfully updated'));
          })

    },
    deleteMember(id,name) {
      if (confirm(i18n('Are you sure you want to delete user: ') + ' "' + name + '"?' )) {
        axios
            .post(this.route('teacher.delete-member'), {id: id})
            .then((response) => {
              this.dataCourses = response.data;
              this.formatDates();
              this.$toast.success(i18n('Course has been successfully deleted'));
            })
      }
    },
    setUpdateNote(note, id,key) {
      this.updateNote.note = note;
      this.updateNote.id = id;
      this.updateNote.key = key;
    },
    saveNote() {
      axios
          .post(this.route('teacher.set-note'), {id: this.updateNote.id, value: this.updateNote.note})
          .then((response) => {
            let data =response.data
            data.created_at = parseISO(data.created_at);
            data.student_info = JSON.stringify(data.student_info);
            this.$set(this.dataCourses[0].users,this.updateNote.key, data);
            this.clearNote();
            this.$toast.success(i18n('Course has been successfully deleted'));
          })
    },
    clearNote() {
      this.updateNote.note = '';
      this.updateNote.id = null;
      this.updateNote.key = null;

    }
  },
  created() {
    this.formatDates();
  },
}

</script>

<style scoped>

</style>