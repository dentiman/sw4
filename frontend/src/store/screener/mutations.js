import * as types from './mutation_types'
import { defaultPresetItem, chartLayout } from '@/utils/chartLayout'
export default {
  [types.RESET_PAGE](state) {
    state.page = 1
  },
  [types.SET_FOCUSED_TICKER](state, ticker) {
    state.focusedTicker = [ticker]
  },
  [types.RESET](state) {
    state.items = []
  },
  [types.SET_ERROR](state, error) {
    state.error = error
  },
  [types.SET_TOTAL_ITEMS](state, value) {
    state.totalItems = value
  },
  [types.SET_CHART_SETTING_MODE](state) {
    state.navListTabs =  'charts'
  },
  [types.EXIT_CHART_SETTING_MODE](state) {
    state.navListTabs =  'watchlist'
  },
  [types.SET_DEFAULT_CHART_PRESET](state) {
    state.activeChartPreset =  defaultPresetItem()
  },
  [types.SET_CHART_PRESET_PROPERTY](state, payload) {
    state.activeChartPreset[payload.chartNumber][payload.property]  =  payload.value
  },
  [types.SET_CHART_ONE_TIMEFRAME](state, payload) {
    if (! payload) { return }
    state.activeChartPreset.chartOne.timeFrame  =  payload
  },
  [types.SET_CHART_TWO_TIMEFRAME](state, payload) {
    if (! payload) {
      state.activeChartPreset.chartTwo = null

      return
    }
    if (!state.activeChartPreset.chartTwo) {
      state.activeChartPreset.chartTwo = chartLayout()
    }
    state.activeChartPreset.chartTwo.timeFrame = payload
  },
  [types.SET_CHART_THREE_TIMEFRAME](state, payload) {
    if (! payload) {
      state.activeChartPreset.chartThree = null

      return
    }
    if (!state.activeChartPreset.chartThree) {
      state.activeChartPreset.chartThree = chartLayout()
    }
    state.activeChartPreset.chartThree.timeFrame = payload
  },
  [types.SET_SORTING1](state, value) {
    state.sorting.sort1.filterId = value
  },
  [types.RESET_SELECTED](state) {
    Object.keys(state.filters).forEach((filterId) => {
      state.filters[filterId].selected = false
    })
  },
  [types.SET_SELECTED](state, payload) {
    payload.forEach((filterId) => {
      state.filters[filterId].selected = true
    })
  },
  [types.SET_FILTER](state, payload) {
    state.filters[payload.filterId][payload.prop] = payload.value
  },
  [types.SET_FILTERS]: (state, filters) => {
    Object.assign(state, { filters })
  },
  [types.SET_SORTING2](state, value) {
    state.sorting.sort2.filterId = value
  },
  [types.RESET_ACTIVE_FILTERS_PRESET](state) {
    state.activeFiltersPreset = null
  },
  [types.RESET_TABLE_PRESET](state) {
    state.activeTablePreset = {
      name: null,
      fields: [...state.defaultTableFields]
    }
  },
  [types.SET_SORTING1_DIRECTION](state, value) {
    state.sorting.sort1.order = value
  },
  [types.RESET_SORTING](state) {
    state.sorting =  {
      sort1: { filterId: 'chp', order: 'desc' },
      sort2: { filterId: null, order: 'desc' }
    }
  },
  [types.SET_SORTING2_DIRECTION](state, value) {
    state.sorting.sort2.order = value
  },

  [types.SET_VIEW](state, value) {
    state.view = value
    localStorage.view = value
  },
  [types.TOGGLE_LOADING](state) {
    Object.assign(state, { isLoading: !state.isLoading })
  },
  [types.RESET]: (state) => {
    state.items = []
  },
  [types.ADD_ITEM]: (state, item) => {
    state.items.push(item)
  }
}
