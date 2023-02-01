<template>
    <input
        :placeholder="placeholder"
        ref="textInput" type="text"
        :value="val"
        @input="input"
        @focus="focus"
        @blur="blur"
        @keydown.enter.prevent="emit(true)"
    >
</template>

<script>
export default {
  props: [ 'value', 'placeholder', 'debounced' ],
  data() {
    return {
      val: this.value || '',
      lastEmitVal: this.value || '',
    };
  },
  watch: {
    value(current) {
      this.val = current;
      this.emitVal();
    },
  },
  methods: {
    input(event) {
      this.val = event.target.value;
      this.emit();
    },
    emitVal() {
      if (this.val !== this.lastEmitVal) {
        this.$emit('input', this.val);
        this.lastEmitVal = this.val;
      }
    },
    emit(now = false) {
      if (this.debounced) {
        this.emitDebounced();
        if (now) {
          this.emitDebounced.flush();
        }
      } else {
        this.emitVal();
      }
    },
    focus() {
      this.$refs.textInput.focus();
      this.$emit('focus');
    },
    blur() {
      this.$refs.textInput.blur();
      this.$emit('blur');
    },
    clear() {
      this.val = '';
      this.emit(true);
    },
  },
  created() {
    this.emitDebounced = _.debounce(function() {
      this.emitVal();
    }, 500);
  }
}
</script>

<style scoped>

</style>