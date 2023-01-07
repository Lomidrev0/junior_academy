export default {
  methods: {
    truncateContent(content,length) {
      return _.truncate(content, {
        'length': length
      })
    },
  }
}