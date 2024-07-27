import * as actions from './actions'
import mutations from './mutations'
import { getField, updateField } from 'vuex-map-fields'
import { defaultPresetItem } from '@/utils/chartLayout'
import filterSerialize from '@/utils/filters/filterSerialize'
const getters = {
  emptyResult: (state) => {
    return !state.items.length > 0
  },
  isChartSettingMode: (state) => {
    return state.navListTabs === 'charts'
  },
  selectedFilters: (state) => {

    // return {}
    return Object.entries(state.filters).reduce((selected, pair) => {
      const [key, value] = pair

      if (value.selected === true) {
        selected[key] = value
      }

      return selected
    }, {})
  },
  screenerResultItems: (state) => state.items,
  pageCount: (state) => Math.ceil(state.totalItems / 25),
  getField,
  columnsForDisplay: (state) => {
    if (state.activeTablePreset && state.activeTablePreset['@id']) {
      return ['id', ...state.activeTablePreset.fields]
    }

    return  ['id', ...state.defaultTableFields]
  } ,
  isAutoShowResults:  (state) => state.view === 'table',
  c1w: (state) => {
    return state.activeChartPreset.chartOne.width
  },
  c2w: (state) => {
    return state.activeChartPreset.chartTwo ? state.activeChartPreset.chartTwo.width : 0
  },
  c3w: (state) => {
    return state.activeChartPreset.chartThree ? state.activeChartPreset.chartThree.width : 0
  },
  screenerAreaWith: (state) => {
    return state.windowWidth - 300
  },
  cardWidth: (state, getters) => {
    if (state.activeChartPreset.chartTwo && state.activeChartPreset.chartThree) {

      if (Math.max(getters.c1w,getters.c2w,getters.c3w) > getters.screenerAreaWith) return Math.max(getters.c1w,getters.c2w,getters.c3w)
      if (getters.c1w + getters.c2w + getters.c3w < getters.screenerAreaWith) return getters.c1w + getters.c2w + getters.c3w
      if (getters.c1w + getters.c2w < getters.screenerAreaWith && getters.c1w + getters.c2w > getters.c3w) return getters.c1w + getters.c2w
      if (getters.c1w + getters.c2w < getters.screenerAreaWith && getters.c1w + getters.c2w <= getters.c3w) return getters.c3w

      if (getters.c3w + getters.c2w < getters.screenerAreaWith && getters.c3w + getters.c2w > getters.c1w) return getters.c3w + getters.c2w
      if (getters.c3w + getters.c2w < getters.screenerAreaWith && getters.c3w + getters.c2w <= getters.c1w) return getters.c1w

      if (Math.min(getters.c1w + getters.c2w,getters.c3w + getters.c2w) > getters.screenerAreaWith) return Math.max(getters.c1w,getters.c2w,getters.c3w)
      // if(Math.max(getters.c1w+getters.c2w,getters.c3w+getters.c2w) > getters.screenerAreaWith) return Math.min(getters.c1w+getters.c2w,getters.c3w+getters.c2w)

      if (getters.c1w + getters.c2w + getters.c3w > getters.screenerAreaWith) return Math.max(getters.c1w + getters.c2w,getters.c3w + getters.c2w)

      return getters.c1w + getters.c2w + getters.c3w
    }

    if (state.activeChartPreset.chartTwo || state.activeChartPreset.chartThree) {
      const c2or3w = Math.max(getters.c2w,getters.c3w)

      if (Math.max(getters.c1w,c2or3w) > getters.screenerAreaWith) return Math.max(getters.c1w,c2or3w)

      if (getters.c1w + c2or3w > getters.screenerAreaWith) return Math.max(getters.c1w,c2or3w)

      return getters.c1w + c2or3w
    }

    return state.activeChartPreset.chartOne.width
  }
}

export default {
  namespaced: true,
  state: {
    windowWidth: 0,
    navListTabs: 'watchlist',
    showTableSettingDialog: false,
    error: null,
    screenerTab: 'screener' ,
    isLoading: false,
    focusedTicker: null,
    view: localStorage.getItem('view') ||  'table',
    items: [],
    totalItems: 0,
    page: 1,
    defaultTableFields: ['vol','sector','exchange','chp','ch','atr','price'],
    // filtersQuery: 'price[lte]=10&price[gte]=1',
    activeFiltersPreset: null,
    activeChartPreset: { ...defaultPresetItem() } ,
    activeTablePreset: {
      name: null,
      fields: ['vol','sector','exchange','chp','ch','atr','price']
    },
    filters: { ...filterSerialize.getDefaultFilters(), ...JSON.parse(localStorage.getItem('filters') ? localStorage.getItem('filters') : '{}' ) },
    autoSearch: true,
    sorting: localStorage.getItem('sorting') ?  JSON.parse(localStorage.getItem('sorting')) : {
      sort1: { filterId: 'chp', order: 'desc' },
      sort2: { filterId: null, order: 'desc' }
    }
  },
  actions,
  getters,
  mutations: {
    ...mutations,
    updateField
  }
}
