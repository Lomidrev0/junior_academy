<template>
  <div>
    <div>
      <div class="text-center m-3">
        <h3>{{user === 0 ? i18n('Add student') : (user === 1 ? i18n('Add teacher') : i18n('Add admin') )}}</h3>
      </div>
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-md-8">
              <div class="card card-style">
                <div class="card-header">
                  {{user === 0 ? i18n('Add student') : (user === 1 ? i18n('Add teacher') : i18n('Add admin') )}}
                  <i v-if="user === 0" class="bi bi-person-fill p-3"></i>
                  <i v-if="user === 1" class="bi bi-book-half p-3"></i>
                  <i v-if="user === 2" class="bi bi-shield-shaded p-3"></i>
                  <label class="select-wrapper card-header-select">
                    <select name="id" v-model="user" class="select">
                      <option :value="2">
                       <span>{{ i18n('Admin')}}</span>
                      </option>
                      <option :value="1" >
                        <span>{{ i18n('Teacher')}}</span>
                      </option>
                      <option :value="0">
                        <span>{{ i18n('Student')}}</span>
                      </option>
                    </select>
                  </label>
                </div>
                <div class="card-body custom-card-body">
                  <form key="saveUser">
                    <div class="row mb-3">
                      <label  class="col-md-4 col-form-label text-md-end">{{ i18n('Name and surname') }}</label>
                      <div class="col-md-6">
                        <input  type="text" class="form-control" v-model="newUser.name" required>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <label  class="col-md-4 col-form-label text-md-end">{{ i18n('Email address') }}</label>
                      <div class="col-md-6">
                        <input type="email" class="form-control" v-model="newUser.email" required>
                      </div>
                    </div>
                    <template v-if="user === 0">
                        <div class="row mb-3">
                          <label  class="col-md-4 col-form-label text-md-end">{{ i18n('School') }}</label>
                          <div class="col-md-6">
                            <input type="text" class="form-control" v-model="newUser.school" required>
                          </div>
                        </div>
                        <div class="row mb-3">
                          <label  class="col-md-4 col-form-label text-md-end">{{ i18n('Class') }}</label>
                          <div class="col-md-6">
                            <input type="text" class="form-control" v-model="newUser.class" required>
                          </div>
                        </div>
                    </template>
                    <div class="row mb-3">
                      <label  class="col-md-4 col-form-label text-md-end">{{ i18n('Confirm by password') }}</label>
                      <div class="col-md-6">
                        <input type="password" class="form-control pass" v-model="password" required>
                      </div>
                    </div>
                  <div class="d-flex flex-row mb-3 checkbox-wrapper">
                    <label  class="col-md-4 col-form-label text-md-end">{{i18n('Show password')}}</label>
                    <div class="checkbox-wrapper-31">
                      <input type="checkbox" class="showPass"/>
                      <svg viewBox="0 0 35.6 35.6">
                        <circle class="background" cx="17.8" cy="17.8" r="17.8"></circle>
                        <circle class="stroke" cx="17.8" cy="17.8" r="14.37"></circle>
                        <polyline class="check" points="11.78 18.12 15.55 22.23 25.17 12.87"></polyline>
                      </svg>
                    </div>
                  </div>
                    <div v-if="user === 1 || user === 0" class="row mb-3">
                      <div class="d-flex justify-content-center">
                        <template v-if="addCourse">
                          <div class="button_first" @click="addCourse = !addCourse">
                            {{i18n('Close')}}
                            <i class="bi bi-caret-up-fill"></i>
                          </div>
                        </template>
                        <template v-else>
                          <div class="button_first" @click="addCourse = !addCourse">
                            {{i18n('Add course')}}
                            <i class="bi bi-caret-down-fill"></i>
                          </div>
                        </template>
                      </div>
                    </div>
                    <div v-if="addCourse">
                      <div class="d-flex flex-column register-wrapper" id="course-list">
                        <div v-for="course in courses" class="register-item">
                          <label :for="course.id">
                            <img :src="course.media[0].original_url" alt="">
                          </label>
                          <input class="mx-2" :id="course.id" type="checkbox" :value="course.id" v-model="newUser.course">
                          <label class="cursor-pointer" :for="course.id">{{ course.name }}</label>
                        </div>
                      </div>
                    </div>
                    <div class="row mb-0">
                      <div class="d-flex justify-content-center">
                        <button type="submit" class="button_first" @click.prevent="saveUser(user)" :disabled="saving">
                          {{user === 0 ? i18n('Add student') : (user === 1 ? i18n('Add teacher') : i18n('Add admin') )}}
                          <b-spinner small v-if="saving"></b-spinner>
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    <alert v-if="error.length > 0" :error="error"  @close="error = ''"></alert>
  </div>
</template>

<script>
import {i18n, toast} from "../../app";
import Alert from "../Alert";
export default {
  components: {Alert},
  props:['courses'],

  data() {
    return {
      user: 1,
      addCourse:false,
      saving: false,
      error: '',
      password:'',
      newUser: {
        name: '',
        email: '',
        role: '',
        school: null,
        class: null,
        course:[],
      }
    }
  },
  watch : {
    user: function (){
      this.resetForm();
    }
  },
  methods: {
    saveUser(user) {
      if (this.newUser.name.length < 1 || this.newUser.email.length < 0 || this.password.length < 0) {
        this.error = this.i18n('You have not filled in all required fields');
      } else if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.newUser.email.valueOf()) === false){
        this.error = this.i18n('Invalid email address');
      } else if (this.newUser.name.length > 254) {
        this.error = this.i18n('The name is too long!');
      } else if (this.newUser.email.length > 254) {
        this.error = this.i18n('Email is too long!');
      } else {
        if (user === 0){
          this.newUser.role = 0;
        }
        else if (user === 1) {
          this.newUser.role = 1;
        }
        else {
          this.newUser.role = 2;
        }
        this.error = '';
        this.saving = !this.saving;
         axios
            .post(this.route('admin.add'), {
              newUser: this.newUser,
              password: this.password
            })
            .then((response) => {
              this.saving = !this.saving;
              if (response.data === 0) {
                toast.success(i18n('User has been sucessfully added'),null);
                this.resetForm();
              } else if(response.data === 1) {
                this.error = this.i18n('Incorrect password');
                this.password ='';
              } else if(response.data === 2) {
                this.error = this.i18n('The address is taken');
                this.password ='';
              } else {
                this.error = this.i18n('Form has not been processed');
                this.password ='';
              }
            })
           .catch((error) => {
              this.saving = !this.saving;
              toast.success(i18n('Form has not been processed'),null);
              setTimeout(() => this.error = '',6000);
              this.resetForm();
              console.log(error);
          });
      }
    },
    resetForm() {
      this.newUser.name = '';
      this.newUser.email = '';
      this.newUser.role = '';
      this.newUser.school = '';
      this.newUser.class = '';
      this.newUser.course = [];
      this.password = '';
    },
  }
}
</script>

<style scoped>

</style>
