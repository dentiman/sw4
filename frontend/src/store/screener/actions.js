import * as types from './mutation_types'
//import * as api from '../../../utils/api';
import axios from '@/utils/interceptor'
import filterSerialize from '@/utils/filters/filterSerialize'
import store from '@/store'

export const  search = ({ commit, state, getters }, nextPage = false) => {
  commit(types.TOGGLE_LOADING)
  if (!nextPage) commit(types.RESET_PAGE)
  // global.console.log(data);
  const filtersQuery = filterSerialize.serialize(getters.selectedFilters, state.sorting)

  let url = 'quote'

  global.console.log(store.state.view)
  if (state.view === 'tickers') url = 'quote_tickers'
  axios
    .get( `api/${url}?${filtersQuery}&page=${state.page}`)
    .then((response) => response.data)
    .then((data) => {
      commit(types.TOGGLE_LOADING)
      commit(types.RESET)
      commit(types.SET_TOTAL_ITEMS, data['hydra:totalItems'])

      data['hydra:member'].forEach((item) => {
        commit(types.ADD_ITEM, item)
      })

    })
    .catch(
      commit(types.SET_ERROR)
    )

}

export const setView = ({ commit }, value) => {
  commit(types.SET_VIEW, value)
}

export const resetTablePreset = ({ commit }) => {
  commit(types.RESET_TABLE_PRESET)
}

export const reset = ({ commit, state, getters }) => {
  commit(types.RESET_SORTING)
  commit(types.RESET_ACTIVE_FILTERS_PRESET)
  const selectedBefore = Object.keys(getters.selectedFilters)

  commit(types.SET_FILTERS, filterSerialize.getDefaultFilters())
  commit(types.SET_SELECTED,selectedBefore)
  search({ commit, state, getters })
}
