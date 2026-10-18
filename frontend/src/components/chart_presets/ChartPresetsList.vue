<template>
  <div>
    <chart-preset-item
      :preset="chartMiniPreset"
      :is-active="itemIsActivePreset(chartMiniPreset)"
      :configurable="false"
    >
      <v-icon >mdi-view-module</v-icon>
    </chart-preset-item>
    <chart-preset-item
      v-for="(preset, index) in items"
      :key="index"
      :preset="preset"
      :is-active="itemIsActivePreset(preset)"
    >
    </chart-preset-item>
  </div>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import { mapFields } from 'vuex-map-fields'
import ChartPresetItem from '@/components/chart_presets/ChartPresetItem'
import { defaultPresetItem } from '@/utils/chartLayout'
export default {
  name: 'ChartPresetsList',
  components: {
    ChartPresetItem
  },
  computed: {
    chartMiniPreset() {
      return {
        ...defaultPresetItem(true),
        ...{ name: '', '@id': 'mini' }
      }
    },
    items() {
      return this.list.filter((i) => {
        return i && i['@id']
      })
    },
    ...mapState('panel_layouts', ['totalItems']),
    ...mapState('screener', ['view','navListTabs']),
    ...mapFields('screener', ['activeChartPreset']),
    ...mapGetters('panel_layouts', ['list','find']),
    ...mapFields('panel_layouts', ['isLoading']),
    ...mapFields('auth', { 'activeChartPresetId' : 'profile.activeChartPresetId' })
  },
  methods: {
    setActivePresetFromLocalStorage() {
      if ( this.activeChartPresetId) {
        const activeChartItem = this.find( this.activeChartPresetId)

        this.activeChartPreset =  activeChartItem ?  activeChartItem :  defaultPresetItem()
      } else {
        this.activeChartPreset =  defaultPresetItem()
      }
    },
    itemIsActivePreset(item) {
      return !!(this.activeChartPreset && item['@id'] === this.activeChartPreset['@id'] && this.view === 'charts')
    },

    getAllPresets() {
      this.resetList = true
      this.fetchAll()
    },
    ...mapActions('panel_layouts', {
      fetchAll: 'fetchAll',
      deleteItem: 'del'
    })
  },
  watch: {
    navListTabs(value) {
      if (value === 'watchlist') this.setActivePresetFromLocalStorage()
    },
    totalItems(totalItems) {
      if (totalItems > 0) {
        this.setActivePresetFromLocalStorage()
      }
    }
  },
  created() {
    this.getAllPresets()

  }
}
</script>

<style scoped>

</style>
