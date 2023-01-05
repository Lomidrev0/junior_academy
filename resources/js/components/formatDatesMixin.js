import {parseISO} from "date-fns";
import {i18n} from "../app";
export default {
  methods: {
    formatDates(values) {
      values = _.map(values, (value) => {
        return {
          ...value,
          updated_at: parseISO(value.updated_at),
          created_at: parseISO(value.created_at)
        }
      });
      return values;
    },
  }
}
