import axios from 'axios'
import store from '../store'
axios.defaults.timeout = 5000
axios.interceptors.request.use(
  (config) => {

    config.baseURL =  process.env.VUE_APP_ENTRYPOINT
    const token = store.getters['auth/jwtDecoded'] || null
    const authorized = token && token.exp > Date.now() / 1000

    if (authorized) {
      config.headers.common['Authorization'] =
        'Bearer ' + store.state.auth.token
    }
    config.headers.common['Access-Control-Allow-Origin'] = '*'

    // if (process.env.NODE_ENV !== 'production') {
    //   if (config.url.indexOf('?') > -1) {
    //     config.url = config.url + '&XDEBUG_SESSION_START=PHPSTORM'
    //   } else {
    //     config.url = config.url + '?XDEBUG_SESSION_START=PHPSTORM'
    //   }
    // }

    return config
  },
  (error) => {

    // store.commit('general/' + types.LOADING_STOP)

    return Promise.reject(error)
  }
)

axios.interceptors.response.use(
  (data) => {
  //  store.commit('general/' + types.LOADING_STOP)

    return data
  },
  (error) => {
    store.commit('notifications/error',error )

    if (
      error.response &&
      error.response.status &&
      error.response.status === 401 &&
      error.response.data.message !== 'Invalid credentials.'
    ) {
      store.commit('auth/AUTH_RESET')
    //  window.location.href = '/login'
    }

    return Promise.reject(error)
  }
)

export default axios
