import * as types from './mutation_types'
import { updateField } from 'vuex-map-fields'

export default {
  updateField,
  [types.SET_QUOTE](state, ticker) {
    state.ticker = ticker
    state.pageWindow =  'quote'
  },
  [types.AUTH_UPDATE_TOKEN](state, data) {
    localStorage.setItem('token', data.token)
    //  localStorage.setItem('roles', data.roles)
    state.token = data.token
    state.error = null
    state.showLoginDialog = false
  //  state.roles = data.roles
  },
  [types.AUTH_SET_ERROR](state, error) {
    state.error = error
  },
  [types.AUTH_SET_NEED_UPDATE_PROFILE](state, bool) {
    state.needUpdateProfile = bool
  },
  [types.AUTH_SET_STEP](state, step) {
    state.step = step
  },
  [types.AUTH_SET_EMAIL_ERROR](state, error) {
    state.emailError = error
  },
  [types.AUTH_SET_PASSWORD_ERROR](state, error) {
    state.passwordError = error
  },
  [types.AUTH_TOGGLE_DIALOG](state, boolean) {
    state.showLoginDialog = boolean
  },
  [types.AUTH_RESET](state) {
    localStorage.removeItem('token')
    state.token = null
    state.error = null
    state.roles =  []
    state.profile = {
      primaryDrawerOn: !!localStorage.getItem('primaryDrawerOn'),
      lightMode: !!localStorage.getItem('lightMode'),
      watchListByTabs: false,
      language: 'en',
      newTicker: null,
      historyQuotes: [],
      activeFilterPresetId: null,
      activeChartPresetId: null,
      activeTablePresetId: null,
      premiumExpiration: null,
      watchlistId: null,
      premium: false
    }
  },
  [types.LOAD_PROFILE](state, data) {
    const old =  state.profile

    state.profile = { ...old, ...data }
  },
  [types.ADD_TICKER_TO_HISTORY]: (state, ticker) => {
    // state.toggledTicker = ticker
    // state.continueWidthTicker =  null
    // let index = state.profile.history.indexOf(ticker);
    // if (index > -1) {
    //   state.profile.history.splice(index, 1)
    //   state.profile.history.unshift(ticker)
    //   return
    // }
    // if(state.profile.history.length >= 10) {
    //   state.profile.history.pop()
    // }
    state.profile.newTicker = ticker
  }
}
