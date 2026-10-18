import * as actions from './actions'
import mutations from './mutations'
// import jwtDecode from 'jwt-decode';
import * as jwtDecode from 'jwt-decode'
import { getField } from 'vuex-map-fields'
const getters = {
  getField,
  authorized: (state) => {
    const token = state.token || null

    return token !== null
  },
  showLoginDialog: (state) => state.showLoginDialog,
  jwtDecoded: (state) => {
    const token = state.token || null

    if (token !== null) {
      return jwtDecode(state.token)
    }
  },
  avatarImage: (state) => {
    return state.profile.avatar ? state.profile.avatar : '/images/avatars/avatar16.svg'
  },
  isPremium:  (state) => {
    const token = state.token || null

    return !!(token && state.profile.premium)
  },
  roles: (state) => state.roles,
  language: (state) => state.language,
  error: (state) => state.error
}

export default {
  namespaced: true,
  state: {
    step: 1,
    showLoginDialog: false,
    ticker: 'SPY',
    pageWindow: 'screener',
    quoteWindow: 'quote-window-chart',
    profile: {
      avatar: null,
      email: null,
      primaryDrawerOn: !!localStorage.getItem('primaryDrawerOn'),
      lightMode: !!localStorage.getItem('lightMode'),
      watchListByTabs: false,
      language: null,
      newTicker: null,
      historyQuotes: [],
      activeFilterPresetId: null,
      activeChartPresetId: null,
      activeTablePresetId: null,
      premiumExpiration: null,
      watchlistId: null,
      premium: false
    },
    needUpdateProfile: true,
    token: localStorage.getItem('token'),
    roles: [],
    emailError: null,
    passwordError: null
  },
  actions,
  getters,
  mutations: {
    ...mutations
  }
}
