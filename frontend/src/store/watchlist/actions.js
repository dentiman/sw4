import * as types from './mutation_types'
//import * as api from '../../../utils/api';
import axios from '@/utils/interceptor'

export const  save = ({ commit, state }) => {
  commit(types.TOGGLE_LOADING)
  commit(types.SET_ERROR, '')
  const item = {
    name: state.name,
    green: state.green,
    red: state.red,
    blue: state.blue,
    continueAt: state.continueAt
  }

  if (state.id) {
    axios.put(state.id,{ id: state.id, ...item })
      .then((response) => response.data)
      .then((data) => {
        commit(types.TOGGLE_LOADING)
        commit(types.CLEAR_TOGGLED_TICKER)
        commit(types.SET_ITEM, data)

      })
      .catch((e) =>  {
        commit(types.SET_ERROR,e)
        commit(types.TOGGLE_LOADING)
      } )
  } else {
    axios.post('api/watchlists',item)
      .then((response) => response.data)
      .then((data) => {
        commit(types.TOGGLE_LOADING)
        commit(types.CLEAR_TOGGLED_TICKER)
        commit(types.SET_ITEM, data)

      })
      .catch((e) => {
        commit(types.SET_ERROR,e)
        commit(types.TOGGLE_LOADING)
      } )
  }

}
export const  load = ({ commit, state }, id) => {
  commit(types.TOGGLE_LOADING)
  commit(types.SET_ERROR, '')
  axios
    .get( id)
    .then((response) => response.data)
    .then((data) => {
      commit(types.TOGGLE_LOADING)
      commit(types.CLEAR_TOGGLED_TICKER)
      commit(types.SET_ITEM, data)
      loadQuotes({ commit, state })
    })
    .catch((e) => {
      commit(types.SET_ERROR,e)
      commit(types.TOGGLE_LOADING)
    } )
}

export const  toggleTicker = ({ commit, state, getters }, ticker) => {
  if (!ticker) return
  if (getters.askToContinue ) {
    commit(types.SHOW_DIALOG,ticker)

    return
  }
  commit(types.TOGGLE_TICKER, ticker)
  save({ commit, state })
  if (state.needLoadQuotes) loadQuotes({ commit, state })
}
export const  move = ({ commit, state }, payload) => {
  commit(types.REMOVE, payload.ticker)
  commit(types.MOVE, payload)
  save({ commit, state })
}

export const  remove = ({ commit, state }, ticker) => {
  commit(types.REMOVE, ticker)
  save({ commit, state })
}

// export const  clearList = ({ commit, state }, list) => {
//     commit(types.CLEAR_LIST, list);
//     save({ commit, state })
// };
export const  continueCurrentWatchlistToday = ({ commit, state }) => {
  commit(types.SET_CONTINUE_AT)
  save({ commit, state })
}

export const loadQuotes  = ({ commit, state }) => {
  const all = [...state.green,...state.red,...state.blue]

  axios
    .get('api/quote_watchlist?id[]=' + all.join('&id[]='))
    .then((response) => response.data)
    .then((data) => {
      commit(types.SET_QUOTES,  data['hydra:member'])
    })
    .catch(
      //  commit(types.SET_ERROR)
    ).finally(() => {
      commit(types.SET_NEED_LOAD_QUOTES_FALSE)
    }
    )
}
