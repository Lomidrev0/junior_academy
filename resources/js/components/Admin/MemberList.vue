<template>
    <div class="table-wrapper shadow">
      <div class="search-bar d-flex ">
        <div class="search-icon"> <i class="bi bi-search"></i> </div>
        <input type="text" v-model="search" placeholder="Search...">
      </div>
      <ul>
        <li :class=" (select === index) ? 'active-course':'unactive-course'" v-for="(course, index) in courses" @click="select = index">
          <p>{{ course.name +' ('+course.users.length+')' }}</p>
        </li>
      </ul>
      <div class="overflow-auto">
        <table class="table">
          <thead class="thead-dark">
          <tr>
            <th>{{i18n('Name and surname')}}</th>
            <th>{{i18n('School')}}</th>
            <th>{{i18n('Class')}}</th>
            <th>{{i18n('Email')}}</th>
            <th>{{i18n('Registration date')}}</th>
          </tr>
          </thead>
          <tbody>
          <template v-for="(course, index) in computedCourses">
            <tr v-for="(user) in course.users">
              <template v-if="index === select">
                <td>{{user.name}}</td>
                <td>{{JSON.parse(user.student_info).school}}</td>
                <td>{{ JSON.parse(user.student_info).class}}</td>
                <td><a :href="'mailto:'+user.email">{{ user.email }}</a></td>
                <td>{{ formatDate(user.created_at, 'H:mm - dd.MM.yyyyy') }}</td>
              </template>
            </tr>
          </template>
          </tbody>
        </table>
      </div>
    </div>
</template>

<script>
import {parseISO} from 'date-fns';
export default {
  props:['courses'],
  data (){
    return{
      select: 0,
      dataCourses: this.courses,
      search:'',
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
  },
  created() {
    this.formatDates();
  },
}

</script>

<style scoped>

</style>