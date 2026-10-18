<template>
  <div class="mx-2 mt-2">
    <filter-presets-list v-if="authorized"></filter-presets-list>
    <template v-for="(filterValue ,filterId) in selectedFilters" :filterId="name">
      <range-filter v-if="filterData[filterId].type === 'range'" :key="filterId" :filter-id="filterId"></range-filter>
      <multi-choice-filter
        v-if="filterData[filterId].type === 'multichoice'"
        :key="filterId"
        :filter-id="filterId"
      ></multi-choice-filter>
      <date-range-filter v-if="filterData[filterId].type === 'date'" :key="filterId" :filter-id="filterId"></date-range-filter>
      <ticker-filter v-if="filterData[filterId].type === 'ticker'" :key="filterId" :filter-id="filterId"></ticker-filter>
      <level-filter v-if="filterData[filterId].type === 'level'" :key="filterId" :filter-id="filterId"></level-filter>
      <choice-filter v-if="filterData[filterId].type === 'choice'" :key="filterId" :filter-id="filterId"></choice-filter>
    </template>
    <sort-box></sort-box>

    <v-tooltip open-delay="500" bottom >
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          x-small
          fab
          class="mr-1 mb-1 border"
          v-bind="attrs"
          @click="resetFilters"
          v-on="on"
        >
          <v-icon size="20" >mdi-window-close</v-icon>
        </v-btn>
      </template>
      <span>{{ $t('Clear filters') }}</span>
    </v-tooltip>

    <v-tooltip open-delay="500" bottom >
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          outlined
          rounded
          color="grey_ll1_dd2"
          class="px-1 mr-1 mb-1 dotted"
          v-bind="attrs"
          style="height: 30px"
          @click.stop="showMoreFilters = true"
          v-on="on"
        >
          <v-icon size="20">mdi-plus</v-icon> {{ $t('More filters') }}
        </v-btn>
      </template>
      <span>{{ $t('More filters') }}</span>
    </v-tooltip>

    <v-dialog
      v-model="showMoreFilters"
      :transition="false"
      max-width="1200"
    >
      <v-card>
        <v-card-text class="mb-0 pb-0" >
          <v-tabs v-model="tabs" color="info" centered >
            <v-tab
              v-for="(value, name) in filterGroups"
              :key="name"
              :href="`#tab-${name}`"
            >
              {{ $t('filters.group.' + name) }}
            </v-tab>
          </v-tabs>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-text style="min-height:300px;" class="pt-2" >
          <v-tabs-items v-model="tabs">
            <v-tab-item
              v-for="(filtersInGroup, name) in filterGroups"
              :key="name"
              :reverse-transition="false"
              :transition="false"
              :value="'tab-' + name"
            >
              <v-container fluid>
                <v-row>
                  <v-col v-for="filterObj in filtersInGroup" :key="filterObj.filterId" cols="3" class="py-0">
                    <v-checkbox
                      v-if="filters[filterObj.filterId]"
                      v-model="selectedIds"
                      :label="$t(`filters.${filterObj.filterId}.label`)"
                      class="my-0"
                      :value="filterObj.filterId"
                    ></v-checkbox>
                  </v-col>
                </v-row>
              </v-container>
            </v-tab-item>
          </v-tabs-items>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="green darken-1"
            text
            @click="showMoreFilters = false"
          >Ok
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>

import formData from '../../utils/filters/formData'

import { mapFields } from 'vuex-map-fields'
import filterSerialize from '../../utils/filters/filterSerialize'
import SortBox from './filters/SortBox'
import DateRangeFilter from './filters/DateRangeFilter'
import MultiChoiceFilter from './filters/MultiChoiceFilter'
import TickerFilter from './filters/TickerFilter'
import RangeFilter from './filters/RangeFilter'
import LevelFilter from './filters/LevelFilter'
import { mapActions, mapGetters, mapState } from 'vuex'
import FilterPresetsList from './FilterPresetsList'
import ChoiceFilter from '@/components/screener/filters/ChoiceFilter'

export default {
  components: {
    ChoiceFilter,
    TickerFilter,
    MultiChoiceFilter,
    DateRangeFilter,
    RangeFilter,
    LevelFilter,
    SortBox,
    FilterPresetsList
  },

  data() {
    return {
      tabs: null,
      filterGroups: {},
      filterData: formData,
      showMoreFilters: false,
      allowSerialize: true,
      filtersPreset: '{}',
      allowSearch: false
    }
  },
  computed: {
    selectedIds: {
      get() {
        return Object.keys(this.selectedFilters)
      },
      set(selected) {
        this.$store.commit('screener/RESET_SELECTED')
        selected.forEach((filterId) => {
          this.$store.commit('screener/SET_FILTER', {
            filterId: filterId,
            value: true,
            prop: 'selected'
          })
        })
      }
    },
    activeFiltersPresetFilters() {
      return  this.activeFiltersPreset ? { ...this.activeFiltersPreset.filters, ...this.activeFiltersPreset.sorting } : null
    },
    ...mapState('screener', { activeFiltersPreset:'activeFiltersPreset', filters: 'filters' }),
    ...mapGetters('auth', ['authorized']),
    ...mapGetters('screener', ['selectedFilters']),
    ...mapFields('screener', ['filtersQuery', 'page', 'autoSearch', 'sorting']),
    ...mapFields('auth', { activeFilterPresetId: 'profile.activeFilterPresetId' }),
    filtersString() {
      return JSON.stringify(this.selectedFilters)
    },
    watchableFilters() {
      return { ...this.selectedFilters, ...this.sorting }
    }
  },
  methods: {
    resetFilters() {
      this.reset()
      this.activeFilterPresetId = null
    },
    ...mapActions('screener', ['reset','search']),
    setFilters(filters) {
      this.$store.commit('screener/SET_FILTERS', filters)
    },
    setFiltersFromActivePreset() {
      if (this.activeFiltersPreset) {
        this.setFilters( { ...filterSerialize.getDefaultFilters(), ...JSON.parse(this.activeFiltersPreset.filters) })
        this.sorting = JSON.parse(this.activeFiltersPreset.sorting)
      }
    }
  },
  watch: {
    activeFiltersPresetFilters: {
      handler(activeFiltersPreset) {
        if (activeFiltersPreset) {
          this.setFiltersFromActivePreset()
          this.search()
        }
      },
      deep: true
    },
    selectedFilters: {
      handler() {
        localStorage.filters = JSON.stringify(this.selectedFilters)
      },
      deep: true
    },
    sorting: {
      handler() {
        localStorage.sorting = JSON.stringify(this.sorting)
      },
      deep: true
    }
  },

  created() {
    this.filterGroups = filterSerialize.getGroupedFilters()
    this.allowSearch = true
  }
}
</script>
