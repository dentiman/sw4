import axios from '@/utils/interceptor'
export default {
  data () {
    return {
      apiResourceUrl: 'default',
      items: [],
      totalItems: 0,
      loading: true,
      options: {
        page: 1,
        sortBy: [],
        sortDesc: []
      }
    }
  },
  computed: {
    pageCount() {
      return this.items.length > 0 ? Math.ceil(this.totalItems / this.items.length) : 0
    }
  },
  watch: {
    options: {
      handler () {
        this.getDataFromApi()
      },
      deep: true
    }
  },
  mounted () {
    this.getDataFromApi()
  },
  methods: {
    getDataFromApi () {
      this.loading = true
      this.items = []
      const sortingParams = this.options.sortBy.reduce((accumulator, currentValue, index) => {
        return Object.assign(accumulator,{ [`order[${currentValue}]`] : (this.options.sortDesc[index] === true ? 'DESC' : 'ASC') })
      }, {})

      axios
        .get( this.apiResourceUrl,{ params: { ...sortingParams, ...{ page: this.options.page } } })
        .then((response) => response.data)
        .then((data) => {
          this.items = data['hydra:member']
          this.totalItems = data['hydra:totalItems']
        }).finally(() => {
          this.loading = false
        })

    }
  }
}
