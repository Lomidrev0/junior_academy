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
<!--            <template v-if="admin === false">-->
              <th><p>{{i18n('Edit')}}</p></th>
              <th><p>{{i18n('Notes')}}</p></th>
              <th><p>{{i18n('Delete')}}</p></th>
<!--            </template>-->
          </tr>
          </thead>
          <tbody>
          <template v-if="computedCourses.length > 0">
            <template v-for="(course, index) in computedCourses">
              <template v-if="index === select">
                <template v-if="computedCourses[index].users.length > 0 ">
                  <tr v-for="(user, key) in course.users">
                    <td><p>{{user.name}}</p></td>
                    <td><p>{{JSON.parse(user.student_info).school}}</p></td>
                    <td><p>{{ JSON.parse(user.student_info).class}}</p></td>
                    <td><p><a :href="'mailto:'+user.email">{{ user.email }}</a></p></td>
                    <td><p>{{ formatDate(user.created_at, 'H:mm - dd.MM.yyyy') }}</p></td>
<!--                    <template v-if="admin === false">-->
                      <td class="position-relative">
                        <label class="switch position-absolute">
                          <input type="checkbox" class="course-toggle" v-model="JSON.parse(user.student_info).active" @click="updateMember(user, key)">
                          <span class="slider round"></span>
                        </label>
                      </td>
                      <td>
                        <div class="notify-div">
                          <i v-b-modal.modal-scrollable class="bi bi-file-earmark-text-fill" @click="setUpdateNote(JSON.parse(user.student_info).notes, user.id, index)"></i>
                          <div class="notify-dot" v-if="JSON.parse(user.student_info).notes"></div>
                        </div>
                      </td>
                      <td><p><i class="bi bi-x-lg" @click=" deleteMember(user.id,user.name)"></i></p></td>
<!--                    </template>-->
                  </tr>
                </template>
                <template v-else>
                  <tr class="pointer-event-none">
                    <td colspan="8">
                      <no-results
                          :header="i18n('No users found')"
                          :body="i18n('It appears that no student has signed up for this course yet.')"
                      ></no-results>
                    </td>
                  </tr>
                </template>
              </template>
            </template>
          </template>
          <template v-else>
            <tr class="pointer-event-none">
              <td colspan="8">
                <no-results
                    :header="i18n('No courses found')"
                    :body="i18n('No course has been created yet. Create a course and allow students to sign up!')"
                ></no-results>
              </td>
            </tr>
          </template>
          </tbody>
        </table>
      </div>
      <div>
        <b-modal id="modal-scrollable" scrollable>
          <template #modal-header="{ close }">
            <h5>{{i18n('Notes')}}</h5>
            <i class="bi bi-x-lg" @click="close(); clearNote()"></i>
          </template>
          <div class="m-modal-body">
            <div class="msg-input-wrapper">
              <div class="w-100 m-auto msg-input">
                <label>
                  <textarea v-model="updateNote.note" :placeholder="i18n('Note content')" rows="15" class="w-100 mh-100"></textarea>
                </label>
              </div>
            </div>
          </div>
          <template #modal-footer="{ ok, cancel, hide }">
            <div class="modal-err-wrapper">
              <alert v-if="error.length > 0" :error="error" :off-margin="true"  @close="error = ''"></alert>
            </div>
            <div>
              <button class="button_first" @click="saveNote(); error.length > 0? '' :ok()">
                {{i18n('Save')}}
              </button>
            </div>
          </template>
        </b-modal>
      </div>
    </div>
</template>

<script>
import {parseISO} from 'date-fns';
import {i18n, toast} from "../app";
import Alert from "./Alert";
import NoResults from "./NoResults";
export default {
  components: {NoResults, Alert},
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
        validateNote: '',
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
          .post(this.route('update-active-member'), {id: user.id,value: !JSON.parse(user.student_info).active})
          .then((response) => {
            let data =response.data
            data.created_at = parseISO(data.created_at);
            data.student_info = JSON.stringify(data.student_info);
            this.$set(this.dataCourses[0].users,key, data);
            setTimeout(function() {
              $('input').prop( "disabled", false);
            }, 100);
            toast.success(i18n('Users state has been updated'),null);
          })

    },
    deleteMember(id,name) {
      if (confirm(i18n('Are you sure you want to delete user: ') + ' "' + name + '"?' )) {
        axios
            .post(this.route('delete-member'), {id: id})
            .then((response) => {
              this.dataCourses = response.data;
              this.formatDates();
              toast.success(i18n('Student has been sucessfully deleted'),null);
            })
      }
    },
    setUpdateNote(note, id,key) {
      this.updateNote.note = note;
      this.updateNote.id = id;
      this.updateNote.key = key;
    },
    saveNote() {
     // if (_.find(this.dataCourses[this.updateNote.key].users, (user) => {
     //   return JSON.parse(user.student_info).notes === this.updateNote.note;
     // })){
     //   this.error = i18n('A change is required for save');
     // }
     // else {
      var userClone = _.cloneDeep(_.find(this.dataCourses[0].users, { id: this.updateNote.id }));
       axios
           .post(this.route('set-note'), {id: this.updateNote.id, value: this.updateNote.note})
           .then((response) => {
             userClone.student_info = JSON.stringify(response.data.student_info)
             const userIndex = _.findIndex(this.dataCourses[0].users, { id: this.updateNote.id });
             this.$set(this.dataCourses[0].users ,userIndex,  userClone);
             this.clearNote();
             toast.success(i18n('Note has been updated'),null);
           })
     // }
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