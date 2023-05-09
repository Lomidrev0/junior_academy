<template>
  <div>
    <form>
      <div>
        <wswg-editor v-model="content"></wswg-editor>
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
  props: ['article'],
  data() {
    return {
      saving: false,
      error: '',
      content: JSON.parse(this.article.value).text,
      textValidator: JSON.parse(this.article.value).text,
    }
  },
  methods: {
    updateArticle() {
      if(this.textValidator !== this.content){
        this.saving = true;
        axios
            .post( route('admin.update-article'),{id: this.article.id, article: this.content})
            .then((response) => {
              this.content,this.textValidator = JSON.parse(response.data.value).text;
              this.saving = false;
              toast.success(i18n('Article has been sucessfully updated'),null);
            })
            .catch((error) => {
              this.saving = false;
              toast.error(i18n('Form has not been processed'),null);
            })
      }
    },
  }
}
</script>

<style scoped>

</style>