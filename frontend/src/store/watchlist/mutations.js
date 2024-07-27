import * as types from './mutation_types'
import moment from 'moment'

export default {
  [types.SET_ERROR](state, error) {
    state.error = error
  },
  [types.TOGGLE_LOADING](state) {
    Object.assign(state, { isLoading: !state.isLoading })
  },
  [types.SHOW_DIALOG](state, toggledTicker) {
    Object.assign(state, { showDialog: true })
    Object.assign(state, { continueWidthTicker: toggledTicker })
  },
  [types.SET_CONTINUE_AT](state) {
    Object.assign(state, { continueAt: moment().format() })
  },

  [types.CLEAR_All_WATCHLIST](state) {
    state.id = null
    state.name =  moment().format('DD/MM/YYYY')
    state.green =  []
    state.red =  []
    state.blue =  []
    state.createdAt =  null
    state.continueAt =  null
    state.toggledTicker =  null
  },
  [types.SET_NEED_LOAD_QUOTES_FALSE](state) {
    state.needLoadQuotes = false
  },
  [types.CLEAR_TOGGLED_TICKER](state) {
    state.toggledTicker = null
  },
  [types.SET_QUOTES](state, quotes) {
    state.quotes = quotes
  },
  [types.SET_ITEM]: (state, item) => {
    state.id = item['@id']
    state.name = item['name']
    state.green = item['green']
    state.red = item['red']
    state.blue = item['blue']
    state.createdAt = item['createdAt']
    state.continueAt = item['continueAt']
  },
  [types.MOVE]: (state, payload) => {
    state[payload.color].push(payload.ticker)
  },
  [types.REMOVE]: (state, ticker) => {
    let index = state.green.indexOf(ticker)

    if (index > -1) { state.green.splice(index, 1)}
    index = state.red.indexOf(ticker)
    if (index > -1) { state.red.splice(index, 1)}
    index = state.blue.indexOf(ticker)
    if (index > -1) { state.blue.splice(index, 1)}
  },
  [types.TOGGLE_TICKER]: (state, ticker) => {
    state.toggledTicker = ticker
    state.continueWidthTicker =  null
    let index = state.green.indexOf(ticker)

    if (index > -1) {
      state.green.splice(index, 1)
      state.red.push(ticker)

      return
    }

    index = state.red.indexOf(ticker)
    if (index > -1) {
      state.red.splice(index, 1)
      state.blue.push(ticker)

      return
    }

    index = state.blue.indexOf(ticker)
    if (index > -1) {
      state.blue.splice(index, 1)

      return
    }
    state.green.push(ticker)
    state.needLoadQuotes = true
  }
}
