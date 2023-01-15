<template>
  <div>
    <form>
      <div>
        <input  class="form-control" v-model="article ? article.title : newArticle.title" :placeholder="i18n('Article name') + ' *'" >
      </div>
      <div>
        <input type="checkbox" v-model="article ? article.active : newArticle.active"> {{i18n('Active')}}
      </div>
      <div>
        <wswg-editor v-model="article ? article.content : newArticle.content"></wswg-editor>
      </div>
      <div>
        <button type="submit" @click.prevent="updateArticle()">
          <template>
            {{ i18n('Save') }}
          </template>
          <b-spinner small v-if="saving"></b-spinner>
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import courseFormMixin from "../courseFormMixin";
import WswgEditor from "../WswgEditor";
import {route} from "../../app";

export default {
  mixins:[courseFormMixin],
  components: {WswgEditor},
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
        axios
            .post( route('admin.update-article'),{id: this.article.id, article: this.article})
            .then((response) => {
              this.article = response.data;
            })
            .catch()
      }
      else {
        axios
            .post(route('admin.add-article'),{article: this.newArticle})
            .then((response) => {
              this.article = response.data;
            })
            .catch()
      }
    },
  }
}
</script>

<style scoped>

</style>