<template>
  <div>
    <div>
      <button class="button_first" @click="user = 2, resetForm()">Admin</button>
      <button class="button_first" @click="user = 1, resetForm()">Teacher</button>
      <button class="button_first" @click="user = 0, resetForm()">Student</button>
    </div>
    <div>
      <div class="text-center m-3">
        <h3>{{user === 0 ? i18n('Add student') : (user === 1 ? i18n('Add teacher') : i18n('Add admin') )}}</h3>
      </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-header">
                            {{user === 0 ? i18n('Add student') : (user === 1 ? i18n('Add teacher') : i18n('Add admin') )}}
                            <i v-if="user === 0" class="bi bi-person-fill p-3"></i>
                            <i v-if="user === 1" class="bi bi-book-half p-3"></i>
                            <i v-if="user === 2" class="bi bi-shield-shaded p-3"></i>
                        </div>

                        <div class="card-body stredek">
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
                                <label  class="col-md-4 col-form-label text-md-end">Show Password</label>
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
                                    <div class="col-md-6 offset-md-4">
                                        <template v-if="addCourse">
                                            <div class="button_first nejde" @click="addCourse = !addCourse">{{i18n('Add course')}}<i class="bi bi-caret-up-fill arrow"></i></div>
                                        </template>
                                        <template v-else>
                                            <div class="button_first nejde" @click="addCourse = !addCourse">{{i18n('Add course')}}<i class="bi bi-caret-down-fill arrow"></i></div>
                                        </template>
                                        <div v-if="addCourse">
                                            <div v-for="course in courses">
                                                <input type="checkbox" id="fruit2" name="fruit-1" value="Apple" :value="course.id" v-model="newUser.course" >
                                                <label for="fruit2" >{{ course.name }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="button_first" @click.prevent="saveUser(user)" :disabled="saving">
                                            {{user === 0 ? i18n('Add student') : (user === 1 ? i18n('Add teacher') : i18n('Add admin') )}}
                                            <b-spinner small v-if="saving"></b-spinner>
                                        </button>
<!--                                         <input type="checkbox" id="fruit1" name="fruit-1" value="Apple">-->
<!--                                        <label for="fruit1">Show Password</label> -->
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <template v-if="error.length > 0">
      <div class="alert alert-danger my-3 mx-4" role="alert">
        {{ error }}
        <div @click="error = ''" class="d-md-inline">
          <i class="bi bi-x-lg"></i>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import {i18n} from "../../app";
export default {
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
                this.$toast.success(i18n('User has been successfully added'));
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
              this.error = i18n('Form has not been processed')
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
