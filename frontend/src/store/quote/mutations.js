import * as types from './mutation_types'
export default {
  [types.SET_QUOTE](state, quote) {
    state.quote = quote
  },
  [types.SET_ERROR](state, error) {
    state.error = error
  },
  [types.TOGGLE_LOADING](state) {
    Object.assign(state, { isLoading: !state.isLoading })
  }

}
