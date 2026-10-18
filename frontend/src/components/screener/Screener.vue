<template>
  <div>
    <v-container style="max-width: 1200px" class="pa-0">
      <filters-card ></filters-card>
      <v-toolbar
        :disabled="emptyResult"
        dense
        color="transparent"
        flat
        class="elevation-0 pt-0"
      >
        <v-chip
          label
          :color="viewIsDefaultTable ? 'success': ''"
          :disabled="emptyResult || isChartSettingMode"
          class="pa-1 mr-1"
          @click="activateDefaultTableView"
        >
          <v-icon :color="viewIsDefaultTable ? '': 'success'">mdi-view-list</v-icon>
        </v-chip>
        <v-chip
          label
          :color="view === 'tickers' ? 'warning': ''"
          :disabled="emptyResult || isChartSettingMode"
          class="pa-1 mr-1"
          @click="activateTickersView"
        >
          <v-icon :color="view === 'tickers' ? '': 'orange_c3'">mdi-card-text-outline</v-icon>
        </v-chip>
        <v-chip
          label
          :disabled="emptyResult || isChartSettingMode"
          :color="viewIsDefaultChart ? 'info': ''"
          class="px-1 ml-0 mr-1"
          @click="activateDefaultChartView"
        >
          <v-icon size="20" :color="viewIsDefaultChart ? '': 'info'">mdi-chart-areaspline-variant</v-icon>
        </v-chip>
        <chart-presets-list v-if="authorized"></chart-presets-list>
        <table-presets-list v-if="authorized"></table-presets-list>
        <v-btn
          v-if="authorized ===false"
          outlined
          class="dotted"
          color="grey_ll1_dd2"
          :disabled="isChartSettingMode || emptyResult"
          style="height: 30px"
          @click="$router.push('/login')"
        >
          <v-icon size="20">mdi-plus</v-icon> {{ $t('customize') }}
        </v-btn>
        <v-menu v-if="authorized" offset-y>
          <template v-slot:activator="{ on }">
            <v-btn
              outlined
              class="dotted"
              color="grey_ll1_dd2"
              :disabled="isChartSettingMode || emptyResult"
              style="height: 30px"
              v-on="on"
            >
              <v-icon size="20">mdi-plus</v-icon> {{ $t('customize') }}
            </v-btn>
          </template>
          <v-list>
            <v-list-item @click="openTableSettings">
              <v-list-item-title>Table</v-list-item-title>
            </v-list-item>
            <v-list-item @click="openChartSettings">
              <v-list-item-title>Chart</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
        <v-btn
          v-if="isChartSettingMode === false && emptyResult === false"
          style="height: 30px"
          color="primary"
          class="ml-2"
          @click="search"
        >{{ $t('Search') }}</v-btn>
      </v-toolbar>

      <v-toolbar
        disabled
        dense
        color="transparent"
        flat
        class="elevation-0 pt-0 "
      >
        <v-toolbar-title class="font-weight-bold text--disabled" style="font-size: 16px">{{ $t('Total') }}: {{ totalItems }}</v-toolbar-title>

        <v-switch
          v-model="autoRefresh"
          hide-details
          dense
          class="ml-2"
        >
          <template v-slot:label>
            <span class="font-weight-bold text--disabled">{{ $t('label.autoRefresh') }}</span>
          </template>
        </v-switch>
        <v-spacer></v-spacer>
        <v-btn v-if="isChartSettingMode" class="text--primary" @click="exitChartEditMode">{{ $t('settings.exit_chart_preset_mode') }}</v-btn>
        <v-pagination
          v-if="isChartSettingMode === false && emptyResult === false && view!=='tickers'"
          v-model="page"
          color="primary"
          class="mx-auto"
          :length="pageCount"
          :total-visible="7"
        ></v-pagination>
        <v-spacer></v-spacer>
      </v-toolbar>
    </v-container>

    <v-responsive v-if="screenerResultItems.length === 0" max-width="300" class="mx-auto text-center">
      <div class="mb-1">
        <v-icon class="text-h1 text--disabled" >mdi-folder-outline</v-icon>
      </div>
      <div class="font-weight-regular mt-1 text--disabled" style="font-size: 26px">{{ $t('screener.noResult') }}</div>
      <div class="font-weight-regular text--disabled">{{ $t('screener.tryAnotherSearch') }}</div>
      <v-btn class="mt-1" @click="resetFilters">{{ $t('Reset') }}</v-btn>
    </v-responsive>

    <v-container v-if="view === 'table' && screenerResultItems.length > 0">
      <table-view
        :quotes="screenerResultItems"
      ></table-view>
    </v-container>
    <v-container v-if="view === 'tickers' && screenerResultItems.length > 0" style="max-width: 1200px">
      <v-row>
        <v-col v-for="(separator, index) in [' ',',','\n']" :key="index" cols="4" class="px-1" >
          <v-card :loading="isLoading" :disabled="isLoading">
            <v-card-title class="nav"><v-icon @click="copyToClipboard(tickersArray.join(separator))">mdi-content-copy</v-icon> </v-card-title>
            <v-textarea
              solo
              flat
              class="text-area-ticker"
              :value="tickersArray.join(separator)"
              readonly
              no-resize
              height="500"
            ></v-textarea>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
    <v-container v-if="view === 'charts' && screenerResultItems.length > 0" fluid >
      <v-sheet v-if="isLoading === true" color="transparent" class="d-flex flex-column" >
        <div :class="{'d-flex flex-wrap' : isFlexWrapChartsArea}" >
          <v-sheet
            v-for="(n, index) in [ 1, 2, 3, 4 ]"
            :key="index"
            :max-width="cardWidth+2"
            :min-width="cardWidth+2"
            min-height="500"
            class="mx-auto mb-4 chart-card"
          >
            <v-skeleton-loader
              :max-width="cardWidth"
              :min-height="300"
              type="list-item-avatar,card, image"
            ></v-skeleton-loader>

          </v-sheet>

        </div>
      </v-sheet>
      <div v-if="isLoading === false && !isChartSettingMode">

        <v-sheet v-if="allowShowCharts === false" color="transparent" class="d-flex flex-column" >
          <v-sheet max-width="700" min-width="700" class="mx-auto" >
            <v-container class="py-8">
              <div class="text-center">
                <div>
                  <div v-if="authorized" class="text-h5 warning--text" >{{ $t('premium.get_started.continue_with_premium') }}</div>
                  <!--                  <div v-if="authorized" class="text-h5 mt-1">{{ $t('premium.get_started.lets') }}</div>-->
                  <div v-if="authorized===false" class="text-h5 mt-1">{{ $t('register.pleaseSignIn') }}</div>
                </div>
                <div class="mt-4">
                  <v-btn
                    v-if="authorized"
                    x-large
                    class="my-1 mx-sm-2 w-full w-sm-auto"
                    color="primary"
                    to="/premium"
                  >{{ $t('premium.get_started.buy') }}</v-btn>
                  <v-btn
                    v-if="authorized===false"
                    x-large
                    class="my-1 mx-sm-2 w-full w-sm-auto"
                    color="primary"
                    to="/login"
                  >{{ $t('register.signIn') }}</v-btn>
                </div>
              </div>
            </v-container>
          </v-sheet>
        </v-sheet>
        <div v-if="allowShowCharts" >
          <div :class="{'d-flex flex-wrap' : isFlexWrapChartsArea}">
            <chart-card
              v-for="quote in screenerResultItems"
              :key="quote.id"
              :quote="quote"
            ></chart-card>
          </div>
          <v-pagination
            v-if="view!=='tickers'"
            v-model="page"
            color="primary"
            class="mx-auto mt-2"
            :length="pageCount"
            :total-visible="7"
          ></v-pagination>
        </div>

      </div>

      <div v-if="isLoading === false && isChartSettingMode && screenerResultItems.length">
        <chart-card
          :quote="screenerResultItems[0]"
        ></chart-card>
      </div>
    </v-container>

  </div>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import ChartCard from './ChartCard'
import FiltersCard from './FiltersCard'
import TableView from './TableView'
import { mapFields } from 'vuex-map-fields'
import ChartPresetsList from '@/components/chart_presets/ChartPresetsList'
import TablePresetsList from '@/components/table_presets/TablePresetsList'
import ChartPresetSettingBox from '@/components/chart_presets/ChartPresetSettingBox'
import { defaultPresetItem } from '@/utils/chartLayout'
import axios from '@/utils/interceptor'
import store from '@/store'

export default {
  name: 'Screener',
  components: {
    FiltersCard,
    ChartCard,
    TableView,
    ChartPresetsList,
    TablePresetsList
  },
  data() {
    return {
      fab: false,
      autoRefresh: false
    }
  },
  computed: {
    tickersArray() {
      const tickers = []

      this.screenerResultItems.forEach((quote) => {
        tickers.push(quote.id)
      })

      return tickers
    },
    isFlexWrapChartsArea() {
      if (!this.activeChartPreset) return  false

      return !(this.activeChartPreset.chartTwo || this.activeChartPreset.chartThree)

    },
    allowShowCharts() {
      if (this.isPremium) return true

      return this.page === 1  && this.authorized
    },
    viewIsDefaultTable() {
      return this.view === 'table' && !(this.activeTablePreset && this.activeTablePreset['@id'])
    },
    viewIsDefaultChart() {
      return this.view === 'charts' && !(this.activeChartPreset && this.activeChartPreset['@id'])
    },
    sorting: {
      get () {
        return this.$store.state.screener.sorting
      },
      set (value) {
        this.$store.commit('screener/SET_SORTING', value)
      }
    },
    ...mapFields('auth',{ primaryDrawerOn: 'profile.primaryDrawerOn' }),
    ...mapGetters('auth', { authorized: 'authorized', isPremium: 'isPremium' }),
    ...mapState('screener', ['view','totalItems','activeTablePreset']),
    ...mapGetters('screener', ['screenerResultItems','pageCount','isChartSettingMode','emptyResult','cardWidth']),
    ...mapFields('screener', ['isLoading','page','autoSearch','activeChartPreset','editChartPreset','showTableSettingDialog','navListTabs','windowWidth']),
    ...mapState('quote', ['quote']),
    ...mapFields('auth', { 'activeChartPresetId' : 'profile.activeChartPresetId' })
  },
  methods: {
    copyToClipboard(text) {
      navigator.clipboard.writeText(text)
      store.commit('notifications/push',{ text: this.$t('Copied to clipboard'),  type: 'success' } )
    },
    resetFilters() {
      this.reset()
      this.activeFilterPresetId = null
    },
    exitChartEditMode() {
      if ( this.activeChartPresetId) {
        this.isLoading = true
        axios
          .get( this.activeChartPresetId)
          .then((response) => response.data)
          .then((data) => {
            this.activeChartPreset  = data
          }).finally(() => {
            this.isLoading = false
          })
      } else {
        this.activeChartPreset =  defaultPresetItem()
      }
      this.navListTabs = 'watchlist'
    },
    activateDefaultTableView() {
      this.resetTablePreset()
      this.setView('table')
    },
    activateTickersView() {
      this.setView('tickers')
    },
    activateDefaultChartView() {
      this.activeChartPreset =  { ...defaultPresetItem() }
      this.setView('charts')
    },
    openChartSettings() {
      this.activeChartPreset =  { ...defaultPresetItem() }
      this.setView('charts')
      this.navListTabs = 'charts'
      this.primaryDrawerOn  = false
    },
    openTableSettings() {
      this.showTableSettingDialog = true
      this.resetTablePreset()
      this.setView('table')
    },
    ...mapActions('screener', {
      resetTablePreset: 'resetTablePreset',
      search: 'search',
      reset: 'reset',
      setView: 'setView'
    }),
    getWindowWidth() {
      this.windowWidth = document.documentElement.clientWidth
    }
  },
  watch: {
    autoRefresh(value) {
      if (value) {
        localStorage.setItem('autoRefresh','1')
      } else {
        localStorage.removeItem('autoRefresh')
      }
    },
    view() {
      this.search(true)
    },
    page() {
      this.search(true)
    }
  },
  // eslint-disable-next-line vue/order-in-components
  beforeDestroy() {
    window.removeEventListener('resize', this.getWindowWidth)
  },
  // eslint-disable-next-line vue/order-in-components
  mounted() {
    if (localStorage.getItem('autoRefresh')) {
      this.autoRefresh = true
    }
    this.search()
    this.$nextTick(function() {
      window.addEventListener('resize', this.getWindowWidth)
      this.getWindowWidth()
    })

    setInterval(() => {
      if (this.autoRefresh) {
        this.search(true)
      }
    },60000)
  }
}
</script>

<style>
  .v-application--is-ltr .text-area-ticker.v-text-field .v-input__prepend-inner {
    padding-right: 16px;
  }
    #screener-view-toolbar .v-toolbar__content {
      margin: auto;
      max-width: 1200px;
      padding: 0px;
    }

</style>
