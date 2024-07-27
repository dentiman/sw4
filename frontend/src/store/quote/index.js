import * as actions from './actions'
import mutations from './mutations'
import { getField, updateField } from 'vuex-map-fields'

const getters = {
  getField
}

export default {
  namespaced: true,
  state: {
    quote: null,
    isLoading: false,
    ticker: 'SPY'
  },
  actions,
  getters,
  mutations: {
    ...mutations,
    updateField
  }
}
