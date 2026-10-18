import axios from '@/utils/interceptor'
export default {
  data () {
    return {
      apiResourceUrl: 'default',
      loading: true
    }
  },
  methods: {
    onDeleted() {
      // need owerride outside
    },
    deleteItem (item) {
      this.loading = true
      axios
        .delete( item['@id'])
        .then(() => {
          this.onDeleted()
        }).finally(() => {
          this.loading = false
        })

    }
  }
}
