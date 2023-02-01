<template>
    <text-input
        ref="searchInput"
        v-model="value"
        :placeholder="placeholder"
        :debounced="true"
        :value="value"
        @input="search"
        @focus="onFocus"
        @blur="emitBlur"
    ></text-input>
</template>

<script>
import TextInput from "./TextInput";
import axiosBase from 'axios';
export default {
  components: {TextInput},
  props: [
     'val','subject', 'placeholder', 'apiUrl', 'params',
    'take', 'skip', 'initialSearch', 'searchOnFocus', 'searchOnlyIfNotEmpty',
  ],
  data(){
    return {
      value: this.val ? this.val : '',
      searching: false,
      searchOnlyWhenNotEmpty: this.searchOnlyIfNotEmpty || false,
    }
  },
  watch: {
    params: {
      handler(current, old) {
        if (!_.isEqual(current, old)) {
          this.$nextTick(this.search);
        }
      },
      deep: true,
    },
  },
  methods: {

    search(val) {
      this.getData({skip: 0});
    },
    emitBlur() {
      this.$emit('blur');
    },
    blur() {
      this.$refs.searchInput.blur();
    },
    clear() {
      this.$refs.searchInput.clear();
    },
    focus() {
      this.$refs.searchInput.focus();
    },
    onFocus() {
      this.$emit('focus');
      if (this.searchOnFocus) {
        this.search();
      }
    },
    emitResult(skip, ids, totalCount, items, val) {
      this.$emit('result', {skip, ids, totalCount, items, val});
    },
  },
  created() {
    let cancel = null;

    this.getData = function({skip, ids}) {
      if (cancel) {
        cancel();
      }
      if (this.searchOnlyWhenNotEmpty && !this.value) {
        this.emitResult(skip, ids, 0, [],null);
        return;
      }
      const take = this.take || -1;
      this.searching = true;
      this.$emit('searching');
      axios
          .get(this.apiUrl || this.route(_.snakeCase(this.subject) + '.search'), {
            params: {
              val: this.value,
              ...this.params,
              skip,
              take,
              ids,
            },
            cancelToken: new axiosBase.CancelToken(function executor(c) {
              cancel = c;
            }),
          })
          .then((response) => {
            this.emitResult(skip, ids, response.data.count, response.data.items, this.value ? this.value : null);
            this.searching = false;
          })
          .catch((error) => {
            if (!axiosBase.isCancel(error)) {
              //this.processError(error);
            }
          });
    };

    if (this.initialSearch) {
      this.search();
    }
  }
}
</script>

<style scoped>

</style>