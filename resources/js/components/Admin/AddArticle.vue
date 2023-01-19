<template>
  <div>
    <form>
      <div class="my-4">
        <input  class="form-control shadow"  v-model="article.title" :placeholder="i18n('Article title')" >
      </div>
      <div>
        <wswg-editor v-model="article.content"></wswg-editor>
      </div>
      <div>
        <div>
          <div class="d-flex align-content-center flex-column">
            <div class="m-auto mt-3">
              <label class="d-flex">
                {{i18n('Active')}}
                <div class="checkbox-wrapper-31">
                  <input type="checkbox" v-model="article.active"/>
                  <svg viewBox="0 0 35.6 35.6">
                    <circle class="background" cx="17.8" cy="17.8" r="17.8"></circle>
                    <circle class="stroke" cx="17.8" cy="17.8" r="14.37"></circle>
                    <polyline class="check" points="11.78 18.12 15.55 22.23 25.17 12.87"></polyline>
                  </svg>
                </div>
              </label>
            </div>
            <button class="button_first m-auto mt-3 mb-3 px-5" type="submit" @click.prevent="updateArticle()">
              {{ i18n('Save') }}
              <b-spinner small v-if="saving"></b-spinner>
            </button>
          </div>
        </div>
      </div>
    </form>
    <alert v-if="error.length > 0" :error="error"  @close="error = ''"></alert>
  </div>
</template>

<script>
import courseFormMixin from "../courseFormMixin";
import WswgEditor from "../WswgEditor";
import Alert from "../Alert";
import {i18n, route, toast} from "../../app";

export default {
  mixins:[courseFormMixin],
  components: {WswgEditor, Alert},
  props: ['articles'],
  data() {
    return {
      article: this.articles ? _.cloneDeep(this.articles) : null,
      saving: false,
      error: '',
      newArticle: {
        title: null,
        active: false,
        content: null
      }
    }
  },
  methods: {
    updateArticle() {
      if (this.article) {
        if (
            this.article.active === this.articles.active &&
            this.article.content === this.articles.content
        ){
          this.error = i18n('You have not filled in all required fields');
        } else if(this.article.name) {
          if ( this.article.title.length >149) {
            this.error = i18n('The title is too long!');
          } else if (this.article.title === this.articles.title) {
            this.error = i18n('There is already article with this name');
          }
        }
        else {
          this.saving = true;
          axios
              .post( route('admin.update-article'),{id: this.article.id, article: this.article})
              .then((response) => {
                this.article = response.data;
                this.saving = false;
                toast.success(i18n('Article has been sucessfully updated'),null);
              })
          .catch((error) => {
            this.saving = false;
            toast.error(i18n('Form has not been processed'),null);
          })
        }
      }
      else {
        axios
            .post(route('admin.add-article'),{article: this.newArticle})
            .then((response) => {
              this.article = response.data;
              toast.success(i18n('Article has been sucessfully created'),null);
            })
      }
    },
  }
}
</script>

<style scoped>

</style>