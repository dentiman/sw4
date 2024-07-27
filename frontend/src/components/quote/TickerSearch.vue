<template>
  <div>
    <v-autocomplete
      id="ticker-search"
      ref="autocomplete"
      v-model="selectedSymbol"
      :menu-props="menuProps"
      auto-select-first
      :class="{ 'mr-2': true, 'ticker-search-small': isSmall, 'ticker-search-default': !isSmall }"
      prepend-inner-icon="mdi-magnify"
      :items="items"
      :loading="isLoading"
      :search-input.sync="searchValue"
      clearable
      hide-details
      item-text="id"
      item-value="id"
      :placeholder="placeholder"
      filled
      flat
      rounded
      no-filter
      single-line
      dense
      item-color="red"
      @focus="placeholder = ''"
      @blur="placeholder = $t('Search')"
    >
      <template v-slot:no-data>
        <v-list-item>
          <v-list-item-title>
            {{ $t('No symbols matched your criteria') }}
          </v-list-item-title>
        </v-list-item>
      </template>

      <template v-slot:item="{ item }">
        <v-list-item-icon>
          <v-icon v-if="!searchValue">mdi-clock-outline</v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>
            <quote-field-value
              field-id="id"
              :field-value="item.id"
              :exchange="item.exchange"
              index="0"
            >
            </quote-field-value>
          </v-list-item-title>
          <v-list-item-subtitle v-text="item.name"></v-list-item-subtitle>
        </v-list-item-content>
        <v-list-item-action>
          <quote-field-value
            field-id="exchange"
            :field-value="item.exchange"
            :exchange="item.exchange"
            index="0"
          >
          </quote-field-value>
        </v-list-item-action>
      </template>
    </v-autocomplete>
  </div>
</template>

<script>
import axios from '../../utils/interceptor'
import QuoteFieldValue from '@/components/quote/QuoteFieldValue'
import { mapFields } from 'vuex-map-fields'
export default {
  name: 'TickerSearch',
  components: {
    QuoteFieldValue
  },
  props: {
    small: {
      type: Boolean,
      required: false
    }
  },
  data: () => ({
    isSmall: false,
    menuProps: {
      maxWidth: 500,
      minWidth: 500
    },
    placeholder: '',
    isLoading: false,
    searchResults: [

    ],
    searchValue: null,
    selectedSymbol: null
  }),
  computed: {
    items() {
      if (this.searchValue && this.searchValue.length) return this.searchResults

      return this.historyQuotes
    },
    ...mapFields('auth', {
      historyQuotes: 'profile.historyQuotes'
    }),
    inverted() {
      return !this.$vuetify.theme.dark
    }
  },
  watch: {
    selectedSymbol(value) {
      if (value) {
        this.handle(value)
      }
    },
    searchValue(value) {
      this.search(value)
    }
  },
  created() {
    this.placeholder = this.$t('Search')
  },
  beforeDestroy () {
    if (typeof window === 'undefined') return

    window.removeEventListener('resize', this.onResize, { passive: true })
  },

  mounted () {
    this.onResize()

    window.addEventListener('resize', this.onResize, { passive: true })
  },
  methods: {
    onResize () {
      this.isSmall = window.innerWidth < 1660
    },
    reset() {
      this.$nextTick(() => {
        this.selectedSymbol = null
        this.searchValue = null
        this.$refs.autocomplete.internalSearch = null
      })

    },
    handle(ticker) {
      this.reset()
      this.$router.push({ name: 'Quote', params: { ticker: ticker } })
    },
    search (value) {
      this.isLoading = true
      axios
        .get( `api/ticker?id=${value}`)
        .then((response) => response.data)
        .then((data) => {
          this.searchResults = data['hydra:member']

        })
        .catch((err) => {
          global.console.log(err)
        })
        .finally(() => (this.isLoading = false))
    }
  }
}
</script>

<style >
  .ticker-search-default #ticker-search,
  .ticker-search-default .v-text-field.v-select--is-menu-active
  {
    min-width: 370px !important;
  }

  .ticker-search-small #ticker-search,
  .ticker-search-small .v-text-field.v-select--is-menu-active
  {
    min-width: 100px !important;
  }

  #main-app-bar .v-text-field.v-select--is-menu-active {
     border-radius: 18px 18px 0 0 !important;
  }
</style>
