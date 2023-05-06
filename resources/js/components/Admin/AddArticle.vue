<template>
  <div>
    <form>
      <div>
        <wswg-editor v-model="article.content"></wswg-editor>
      </div>
      <div>
        <div>
          <div class="d-flex align-content-center flex-column">
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