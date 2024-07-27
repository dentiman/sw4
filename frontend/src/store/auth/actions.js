import * as types from './mutation_types'
//import * as api from '../../../utils/api';
import axios from '@/utils/interceptor'

export const setQuote = ({ commit }, ticker) => {
  commit(types.SET_QUOTE,ticker)
  commit(types.ADD_TICKER_TO_HISTORY,ticker)
}

export const  exist = ({ commit }, data) => {
  commit( types.AUTH_SET_EMAIL_ERROR, null)

  return axios
    .post( 'user/exist', data)
    .then((response) => response.data)
  // eslint-disable-next-line
        .then(data => {
      commit( types.AUTH_SET_STEP, 2)
    })
    .catch((e) => {
      const { data } = e.response

      if (data.violations) {
        data.violations.map((violation) => {
          if (violation.propertyPath === 'email') {
            commit( types.AUTH_SET_EMAIL_ERROR, violation.message)
          }
        })
      }
    })
}

export const  loadProfile = ({ commit }) => {
  commit( types.AUTH_SET_ERROR, null)

  return axios
    .get( 'api/user/profile')
    .then((response) => response.data)
    .then((data) => {
      commit(types.LOAD_PROFILE, data)
    })
    .catch((e) => {
      commit( types.AUTH_SET_ERROR, e.message)
    })
}

export const  updateProfile = ({ commit, state }) => {
  commit( types.AUTH_SET_ERROR, null)
  if (state.profile['@id'] &&  state.needUpdateProfile === true)
  {
    commit( types.AUTH_SET_NEED_UPDATE_PROFILE, false)

    return axios
      .patch( state.profile['@id'], state.profile)
      .then((response) => response.data)
      .then((data) => {
        commit(types.LOAD_PROFILE, data)
        setTimeout(() => {
          commit( types.AUTH_SET_NEED_UPDATE_PROFILE, true)
        },1000)
      })
      .catch((e) => {
        commit( types.AUTH_SET_ERROR, e.message)
      })
  }

}

export const  login = ({ commit }, data) => {
  commit( types.AUTH_SET_PASSWORD_ERROR, null)

  return axios
    .post( 'api/authentication-token', data)
    .then((response) => response.data)
    .then((data) => {
      commit(types.AUTH_UPDATE_TOKEN, data)
    })
    .catch((e) => {
      commit( types.AUTH_SET_PASSWORD_ERROR, e.message)
    })

}

export const  logout = ({ commit }) => {
  commit(types.AUTH_RESET)
}
