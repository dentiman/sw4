import * as actions from './actions'
import mutations from './mutations'
import { getField, updateField } from 'vuex-map-fields'
import moment from 'moment'
const getters = {
  getField,
  isEmpty: (state) => {
    return state.green.length === 0 &&
          state.red.length === 0 &&
          state.blue.length === 0
  },
  askToContinue: (state) => {
    if (state.createdAt === null) return false
    if (moment().format('DD/MM/YYYY') === moment(state.createdAt).format('DD/MM/YYYY') ) return  false
    if (state.continueAt && moment().format('DD/MM/YYYY') === moment(state.continueAt).format('DD/MM/YYYY')) return false

    return true
  }
}

export default {
  namespaced: true,
  state: {
    isLoading: false,
    green: [],
    red: [],
    blue: [],
    id: null,
    name: moment().format('DD/MM/YYYY'),
    toggledTicker: null,
    //need for put in wl when ask to continue
    continueWidthTicker: null,
    quotes: [],
    needLoadQuotes: false,
    error: null,
    continueAt: null,
    createdAt: null,
    showDialog: false
  },
  actions,
  getters,
  mutations: {
    ...mutations,
    updateField
  }
}
