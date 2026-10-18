<template>
  <v-card
    flat
    :loading="isLoading"
    :disabled="isLoading"
    class="rounded-0"
    outlined
  >
    <v-card-actions class="surface" >
      <v-list-item>
        <v-list-item-icon>
          <v-icon large >mdi-cog</v-icon>
        </v-list-item-icon>

        <v-list-item-content>
          <v-list-item-title > {{ $t('settings.chart_presets') }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-spacer></v-spacer>
      <v-form
        ref="form"
        v-model="valid"
      >
        <v-text-field
          v-model="activeChartPresetName"
          outlined
          dense
          label="Name"
          required
          hide-details
          type="text"
          class="mb-0"
          full-width
          :rules="nameRules"
        ></v-text-field>
      </v-form>
    </v-card-actions>
    <v-expansion-panels id="charts-expansion" flat class="elevation-0 rounded-0 border-t" accordion >
      <v-expansion-panel class="border-b" >
        <v-expansion-panel-header color="surface" ><span class="success--text">{{ $t('chart.chart1') }}</span></v-expansion-panel-header>
        <v-expansion-panel-content class="mx-1 py-0">
          <v-btn-toggle
            v-model="timeFrame1"
            mandatory
            rounded
            color="success"
            class="my-1"
          >
            <v-btn
              v-for="(timeFrame, index) in timeFrameItems"
              :key="index"
              style="padding-left: 8px !important;padding-right: 8px !important"
              small
              :value="timeFrame.value"
            >
              {{ timeFrame.text }}
            </v-btn>
          </v-btn-toggle>
          <chart-item color="success" mapped-chart="chartOne" ></chart-item>
        </v-expansion-panel-content>
      </v-expansion-panel>
      <v-expansion-panel class="border-b">
        <v-expansion-panel-header color="surface" class="text--error"><span class="error--text">{{ $t('chart.chart2') }}</span></v-expansion-panel-header>
        <v-expansion-panel-content class="mx-1 py-0">
          <v-btn-toggle
            v-model="timeFrame2"
            mandatory
            rounded
            class="my-1"
            color="error"
          >
            <v-btn
              v-for="(timeFrame, index) in timeFrameItemsAll"
              :key="index"
              x-small
              style="font-size: 12px"
              class="py-1"
              :value="timeFrame.value"
            >
              {{ timeFrame.text }}
            </v-btn>
          </v-btn-toggle>
          <chart-item color="error" v-if="!!activeChartPreset.chartTwo" mapped-chart="chartTwo" ></chart-item>
        </v-expansion-panel-content>
      </v-expansion-panel >
      <v-expansion-panel class="border-b">
        <v-expansion-panel-header color="surface"><span class="primary--text">{{ $t('chart.chart3') }}</span></v-expansion-panel-header>
        <v-expansion-panel-content class="mx-1 py-0">
          <v-btn-toggle
            v-model="timeFrame3"
            mandatory
            rounded
            class="my-1"
            color="primary"
          >
            <v-btn
              v-for="(timeFrame, index) in timeFrameItemsAll"
              :key="index"
              x-small
              style="font-size: 12px"
              class="py-1"
              :value="timeFrame.value"
            >
              {{ timeFrame.text }}
            </v-btn>
          </v-btn-toggle>

          <chart-item color="primary" v-if="!!activeChartPreset.chartThree" mapped-chart="chartThree" ></chart-item>
        </v-expansion-panel-content>
      </v-expansion-panel>
      <v-expansion-panel class="border-b">
        <v-expansion-panel-header color="surface">Fields</v-expansion-panel-header>
        <v-expansion-panel-content>
          <v-tabs vertical class="mb-4" >
            <v-tabs-slider></v-tabs-slider>
            <v-tab
              v-for="(value, name) in columnsGroups"
              :key="name"
              :href="`#tab-${name}`"
            >
              {{ $t('filters.group.' + name) }}
            </v-tab>
            <v-tab-item
              v-for="(filtersInGroup, name) in columnsGroups"
              :key="name"
              :value="'tab-' + name"
              :reverse-transition="false"
              :transition="false"
            >
              <v-card flat class="overflow-y-auto ml-1" height="420">
                <v-checkbox
                  v-for="filterObj in filtersInGroup"
                  :key="filterObj.filterId"
                  v-model="activeChartPresetDisplayFeedFields"
                  hide-details
                  :label="$t(`filters.${filterObj.filterId}.label`)"
                  :value="filterObj.filterId"
                  class="my-0"
                ></v-checkbox>
              </v-card>
            </v-tab-item>
          </v-tabs>
        </v-expansion-panel-content>
      </v-expansion-panel>

    </v-expansion-panels>
    <v-card-actions>

      <v-spacer></v-spacer>
      <v-btn
        text
        @click="exitChartEditMode"
      >{{ $t('Cancel') }}</v-btn>
      <v-btn
        v-if="activeChartPreset['@id']"
        text
        color="red_c1"
        @click="removePreset"
      >{{ $t('Delete') }}</v-btn>
      <v-btn
        :disabled="!valid"
        color="primary"
        @click="saveFilterPreset"
      >{{ $t('Save') }}</v-btn>
    </v-card-actions>

  </v-card>
</template>

<script>
import axios from '@/utils/interceptor'
import { mapActions, mapGetters, mapState } from 'vuex'
import { mapFields } from 'vuex-map-fields'
import { minLength, required } from 'vuelidate/lib/validators'
import ChartItem from '@/components/chart_presets/ChartItem'
import { defaultPresetItem } from '@/utils/chartLayout'
import filterSerialize from '@/utils/filters/filterSerialize'
export default {
  name: 'ChartPresetSettingBox',
  components: {
    ChartItem
  },
  data() {
    return {
      nameRules: [
        (v) => !!v || 'Name is required',
        (v) => (v && v.length <= 10) || 'Name must be less than 10 characters'
      ],
      valid: true,
      tabs: null,
      columnsGroups: {},
      step: 1,
      timeFrameItemsAll: [
        { text: 'Disabled', value: null },
        { text: 'D', value: 'd' },
        { text: '1m', value: '1' },
        { text: '2m', value: '2' },
        { text: '3m', value: '3' },
        { text: '5m', value: '5' },
        { text: '15m', value: '15' },
        { text: '30m', value: '30' }
      ],
      timeFrameItems: [
        { text: 'Daily', value: 'd' },
        { text: '1m', value: '1' },
        { text: '2m', value: '2' },
        { text: '3m', value: '3' },
        { text: '5m', value: '5' },
        { text: '15m', value: '15' },
        { text: '30m', value: '30' }
      ]
    }
  },
  computed: {
    initPreset: defaultPresetItem,
    timeFrame1: {
      get() {
        return this.activeChartPreset.chartOne.timeFrame
      },
      set(value) {
        this.$store.commit('screener/SET_CHART_ONE_TIMEFRAME', value)
      }
    },
    timeFrame2: {
      get() {
        return this.activeChartPreset.chartTwo ? this.activeChartPreset.chartTwo.timeFrame : null
      },
      set(value) {
        this.$store.commit('screener/SET_CHART_TWO_TIMEFRAME', value)
      }
    },
    timeFrame3: {
      get() {
        return this.activeChartPreset.chartThree ? this.activeChartPreset.chartThree.timeFrame : null
      },
      set(value) {
        this.$store.commit('screener/SET_CHART_THREE_TIMEFRAME', value)
      }
    },
    newPresetNameErrors () {
      const errors = []

      if (!this.$v.activeChartPreset.name.$dirty) return errors
      !this.$v.activeChartPreset.name.minLength && errors.push('Name must be 3 characters long')
      !this.$v.activeChartPreset.name.required && errors.push('Name is required.')

      return errors
    },
    items() {
      return this.list.filter((i) => {
        return i && i['@id']
      })
    },
    ...mapFields('screener', {
      'activeChartPreset': 'activeChartPreset'
    }),
    ...mapFields('screener', {
      navListTabs: 'navListTabs',
      activeChartPresetDisplayFeedFields: 'activeChartPreset.displayFeedFields',
      activeChartPresetName: 'activeChartPreset.name'
    }),
    ...mapState('panel_layouts', ['created','updated','totalItems']),
    ...mapGetters('panel_layouts', ['list', 'find']),
    ...mapFields('panel_layouts', ['deleted','error','resetList','isLoading']),
    ...mapFields('auth', { 'activeChartPresetId' : 'profile.activeChartPresetId' })
  },
  methods: {
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
    removePreset() {
      this.$confirm('Do you really want to delete this item?',{ color: 'error', title: 'Confirmation', icon: 'mdi-alert' }).then((res) => {
        if (res) {
          this.deleteItem(this.activeChartPreset).then(() => {
            this.activeChartPresetId = null
            this.activeChartPreset = defaultPresetItem()
            this.navListTabs = 'watchlist'
          })
        }
      })
    },
    saveFilterPreset()
    {
      this.$refs.form.validate()
      if (this.valid === false ) return

      if (this.activeChartPreset['@id']) {
        this.updateItem(this.activeChartPreset)
      } else {
        this.createItem(this.activeChartPreset)
      }
    },
    onCreated(item) {
      this.activeChartPresetId  =  item['@id']
      this.activeChartPreset = item
      this.getAllPresets()
      this.navListTabs = 'watchlist'
    },
    getAllPresets() {
      this.resetList = true
      this.fetchAll()
    },
    ...mapActions('screener', {
      setView: 'setView'
    }),
    ...mapActions('panel_layouts', {
      fetchAll: 'fetchAll',
      deleteItem: 'del',
      updateItem: 'update',
      createItem: 'create'
    })
  },
  watch: {
    navListTabs(value) {
      if (value === 'watchlist') this.getAllPresets()
    },
    updated(updated) {
      if (!updated) {
        return
      }
      this.onCreated(updated)
    },
    created(created) {
      if (!created) {
        return
      }
      this.onCreated(created)
    }
  },
  created() {
    this.columnsGroups = filterSerialize.getGroupedTableColumns()
    this.setView('charts')
  }
}
</script>

<style >
#charts-expansion .v-expansion-panel-content__wrap {
  padding: 0px !important;
}
#chats-stepper-header {
  -webkit-box-shadow: none;
  box-shadow: none;

}
#chart-presets-fields-tabs .v-tabs-bar{
  /*background-color: var(--falcon-300);*/
  padding-left: 15px;
  padding-right: 15px;
}
</style>
