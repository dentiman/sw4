<template>
  <v-chip
    :disabled="disabled"
    :close="!isLoading && configurable"
    :close-icon="activePresetCloseIcon"
    :color="presetButtonColor"
    class="mr-1 text-uppercase font-weight-black view-item-chip"
    label
    loading
    @click="activatePreset"
    @click:close="onActivePresetClose"
  >
    <slot></slot>
    <v-icon v-if="configurable" class="mr-1 pre-chart-icon">{{ icon }}</v-icon>
    {{ preset.name }}
  </v-chip>
</template>

<script>

import { mapFields } from 'vuex-map-fields'
import { mapActions, mapState, mapGetters } from 'vuex'
import { defaultPresetItem } from '@/utils/chartLayout'

export default {
  name: 'ChartPresetItem',
  props: {
    preset: {
      type: Object,
      required: true
    },
    isActive: {
      type: Boolean,
      required: true
    },
    configurable: {
      type: Boolean,
      required: false,
      default: true
    },
    icon: {
      type: String,
      required: false,
      default: 'mdi-chart-box-outline'
    }
  },

  data() {
    return {
      isLoading: false
    }
  },
  computed: {
    disabled() {
      if ( this.isChartSettingMode &&  this.configurable === false) return true
      if (this.isLoading) return false

      return this.emptyResult
    },
    activePresetCloseIcon() {
      if (this.screenerTab === 'settings' && this.view === 'charts' )  return  'mdi-close'

      return 'mdi-cog'
    },
    presetButtonColor() {
      if (this.isActive) {
        return 'primary'
      }

      return null
    },
    ...mapGetters('screener', ['emptyResult','isChartSettingMode']),
    ...mapState('screener', ['view']),
    ...mapFields('panel_layouts', ['deleted']),
    ...mapFields('screener', ['activeChartPreset','screenerTab','navListTabs']),
    ...mapFields('auth', { 'activeChartPresetId' : 'profile.activeChartPresetId',primaryDrawerOn: 'profile.primaryDrawerOn' })

  },
  methods: {
    ...mapActions('panel_layouts', { deleteItem: 'del' }),
    ...mapActions('screener', {
      search: 'search',
      setView: 'setView'
    }),
    onActivePresetClose() {
      this.setView('charts')
      this.activeChartPresetId = this.preset['@id']
      this.activeChartPreset = this.preset
      this.editChartPreset = { ...this.preset }
      this.navListTabs = 'charts'
      this.primaryDrawerOn  = false
    },
    removePreset() {
      this.deleteItem(this.preset).then(() => {
        if (this.isActive) {
          this.activeChartPresetId = null
          this.activeChartPreset = defaultPresetItem()
        }
      })
    },
    activatePreset() {
      this.activeChartPresetId = this.preset['@id']
      this.activeChartPreset = this.preset
      this.setView('charts')
    }
  }
}
</script>

<style scoped>
  /*.view-item-chip.theme--light {*/
  /*  color: var(--v-grey_c2-base);*/
  /*}*/
  /*.view-item-chip.theme--dark {*/
  /*  color: var(--v-grey_o4-base);*/
  /*}*/
.pre-chart-icon {
  opacity: 0.3;
}

</style>
