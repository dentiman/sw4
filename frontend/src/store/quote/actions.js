import * as types from './mutation_types'
//import * as api from '../../../utils/api';
import axios from '@/utils/interceptor'

export const  getQuote = ({ commit,state }) => {
  commit(types.TOGGLE_LOADING)
  axios
    .get( `api/quote/${state.ticker}`)
    .then((response) => response.data)
    .then((data) => {
      commit(types.TOGGLE_LOADING)
      commit(types.SET_QUOTE, data)
    })
    .catch(
      //  commit(types.SET_ERROR)
    )

}
