<template>
  <v-container
    style="max-width: 1200px"
    class="pa-0"
  >
    <v-card
      class="mx-2"
      :disabled="isLoading === true"
    >
      <v-simple-table dense class="quote-table table-striped">
        <template v-slot:default>
          <thead class="falcon-200-bg">
            <tr>
              <th
                v-for="field in columnsForDisplay"
                :key="field"
                @click="setSorting(field)"
              >
                <span class=" font-weight-bold text--disabled" style="font-size: 14px" >{{ $t(`filters.${field}.label`) }}</span>
                <v-icon
                  v-if="sort1Field === field && sort1Order ==='desc'"
                  small
                  right
                  color="red"
                  class="table-sort table-sort-desc"
                >mdi-triangle
                </v-icon>
                <v-icon
                  v-if="sort1Field === field && sort1Order ==='asc'"
                  small
                  right
                  color="green"
                  class="table-sort table-sort-asc"
                >mdi-triangle
                </v-icon>
                <v-icon
                  v-if="sort2Field === field && sort2Order ==='desc'"
                  small
                  right
                  color="red"
                  class="table-sort table-sort-desc"
                >mdi-triangle-outline
                </v-icon>
                <v-icon
                  v-if="sort2Field === field && sort2Order ==='asc'"
                  small
                  right
                  color="green"
                  class="table-sort table-sort-asc"
                >mdi-triangle-outline
                </v-icon>
              </th>
            </tr>
          </thead>
          <tbody v-if="isLoading === false">
            <tr v-for="quote in quotes" :key="quote.id">
              <td v-for="field in columnsForDisplay" :key="field">
                <quote-field-value
                  :field-id="field"
                  :field-value="quote[field]"
                  :exchange="quote.exchange"
                  :index="quote.index"
                >
                </quote-field-value>
              </td>
            </tr>
          </tbody>
          <tbody v-if="isLoading === true">
            <tr v-for="n in 25" :key="n">
              <td v-for="field in columnsForDisplay" :key="field">
                <v-skeleton-loader
                  ref="skeleton"
                  type="table-cell"
                  class="mx-auto"
                ></v-skeleton-loader>
              </td>
            </tr>
          </tbody>
        </template>
      </v-simple-table>
    </v-card>
  </v-container>
</template>

<script>
import { mapFields } from 'vuex-map-fields'
import filterSerialize from '../../utils/filters/filterSerialize'
import { mapActions, mapGetters, mapState } from 'vuex'
import QuoteFieldValue from '@/components/quote/QuoteFieldValue'
export default {
  name: 'TableView',
  components: {
    QuoteFieldValue
  },
  props: {
    quotes: {
      required: true
    }
  },
  methods: {
    ...mapActions('screener', ['search']),
    headerClassObject(field) {
      return {
        'text-left': true,
        'secondary': (field !== this.sort1Field && field !== this.sort2Field)
      }
    },
    setSorting(fieldId) {

      if (this.sort1Field === fieldId) {
        this.sort1Order = this.sort1Order === 'asc' ? 'desc' : 'asc'
      } else {
        this.sort1Field = fieldId
        this.sort1Order = 'desc'
      }
      // multi-sort logic
      // if (this.sort1Field && this.sort2Field) {
      //   this.resetSorting()
      //   this.sort1Field = fieldId
      // } else if (this.sort1Field === fieldId) {
      //   this.sort1Order = this.sort1Order === 'asc' ? 'desc' : 'asc'
      // } else if (this.sort2Field === fieldId) {
      //   this.sort2Order = this.sort2Order === 'asc' ? 'desc' : 'asc'
      // } else {
      //   this.sort2Field = fieldId
      // }
      this.search()

    },
    resetSorting() {
      this.$store.commit('screener/RESET_SORTING')
      this.search()
    }
  },
  computed: {
    sort1Field: {
      get() {
        return this.$store.state.screener.sorting.sort1.filterId ?
          this.$store.state.screener.sorting.sort1.filterId : 'chp'
      },
      set(value) {
        this.$store.commit('screener/SET_SORTING1', value)
      }
    },
    sort2Field: {
      get() {
        return this.$store.state.screener.sorting.sort2.filterId
      },
      set(value) {
        this.$store.commit('screener/SET_SORTING2', value)
      }
    },
    sort1Order: {
      get() {
        return this.$store.state.screener.sorting.sort1.order
      },
      set(value) {
        this.$store.commit('screener/SET_SORTING1_DIRECTION', value)
      }
    },
    sort2Order: {
      get() {
        return this.$store.state.screener.sorting.sort2.order
      },
      set(value) {
        this.$store.commit('screener/SET_SORTING2_DIRECTION', value)
      }
    },
    ...mapFields('screener', ['page', 'tableFields']),
    ...mapGetters('screener', ['columnsForDisplay']),
    ...mapState('screener', ['autoSearch', 'isLoading'])
  },
  watch: {
    // fields()  {
    //     this.columnFields = this.fields.unshift('id')
    // },
  },
  data() {
    return {
      columnsGroups: {}
    }
  },
  created() {
    //    this.columnFields = this.fields.unshift('id')
    this.columnsGroups = filterSerialize.getGroupedTableColumns()
  }
}
</script>

<style>

.quote-table th {
  cursor: pointer;
}

.table-sort {
  margin-top: -4px;
  opacity: 0.9;
}

.sort-opacity {
  opacity: 0.6;
}

.table-sort-desc {
  transform: rotate(-180deg);
}
</style>
