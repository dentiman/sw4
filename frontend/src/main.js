import Vue from 'vue'
import App from './App.vue'

// VUEX - https://vuex.vuejs.org/
import store from './store'

// VUE-ROUTER - https://router.vuejs.org/
import router from './router'

// PLUGINS
import vuetify from './plugins/vuetify'
import './plugins/vue-head'
// import './plugins/vue-gtag'

// FILTERS
import './filters/capitalize'
import './filters/lowercase'
import './filters/uppercase'
import './filters/placeholder'
import './filters/trim'
import './filters/formatDate'
import Vuelidate from 'vuelidate'
// STYLES
// Main Theme SCSS
import './assets/scss/theme.scss'
import VueGtag from 'vue-gtag'
// Set this to false to prevent the production tip on Vue startup.
Vue.config.productionTip = false
import i18n from './i18n'
Vue.use(Vuelidate)
Vue.use(VueGtag, {
  config: { id: 'UA-29603504-1' }
})

/*
|---------------------------------------------------------------------
| Main Vue Instance
|---------------------------------------------------------------------
|
| Render the vue application on the <div id="app"></div> in index.html
|
| https://vuejs.org/v2/guide/instance.html
|
*/
export default new Vue({
  i18n,
  vuetify,
  router,
  store,
  render: (h) => h(App)
}).$mount('#app')
