import Vue from 'vue'

// For full framework
// import Vuetify from 'vuetify/lib/framework'
// For a-la-carte components - https://vuetifyjs.com/en/customization/a-la-carte/
import Vuetify from 'vuetify/lib'

import * as directives from 'vuetify/lib/directives'
import config from '../configs'
import VuetifyConfirm from 'vuetify-confirm'

Vue.use(Vuetify, {
  directives
})

const vuetify = new Vuetify({
  theme: {
    dark: config.theme.globalTheme === 'dark',
    options: {
      customProperties: true
    },
    themes: {
      dark: config.theme.dark,
      light: config.theme.light
    }
  }
})

Vue.use(VuetifyConfirm, {
  vuetify
})
export default vuetify
