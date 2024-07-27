<template>
  <v-app>
    <v-main v-if="isLoading">
      <v-container fluid fill-height>
        <v-row align="center" justify="center">
          <v-card flat width="200">
            <v-progress-linear
              color="primary"
              indeterminate
              rounded
              height="6"
            ></v-progress-linear>
          </v-card>
        </v-row>
      </v-container>
    </v-main>
    <router-view v-if="isLoading === false" />
  </v-app>
</template>

<script>

import { mapFields } from 'vuex-map-fields'

export default {
  data() {
    return {
      isLoading: true
    }
  },
  computed: {
    ...mapFields('auth', { language: 'profile.language' }),
    authorized() { return this.$store.getters['auth/authorized']},
    token() { return this.$store.getters['auth/jwtDecoded'] },
    profile() { return this.$store.state.auth.profile},
    lightMode() { return this.profile.lightMode}
  },
  watch: {
    // After login need load profile
    authorized(value) {
      if (value) {
        this.$store.dispatch('auth/loadProfile')
      }
    },
    // when some field changed in profile - need update in DB
    profile: {
      handler() {
        this.$store.dispatch('auth/updateProfile')
      },
      deep: true
    },
    lightMode(value) {
      if (value === true) {
        localStorage.setItem('lightMode', '1')
        this.$vuetify.theme.dark = false
      } else {
        localStorage.removeItem('lightMode')
        this.$vuetify.theme.dark = true
      }
    }
  },
  created() {
    this.$gtag.event('appView', { method: 'Google' })
    const token = this.$store.getters['auth/jwtDecoded']

    if (token && token.exp < Date.now() / 1000) {
      this.$store.commit('auth/AUTH_RESET')
      this.isLoading = false
      this.setLanguage()
    } else if (token) {
      this.$store.dispatch('auth/loadProfile').then(() => {
        this.setLanguage()
        this.isLoading = false
      })
    } else {
      this.setLanguage()
    }
    this.isLoading = false
    if (this.lightMode === true) {
      this.$vuetify.theme.dark = false
    } else {
      //by default
      this.$vuetify.theme.dark = true
    }
  },
  methods: {
    getBrowserLanguage() {
      const rus = ['ru','ua','be']

      if (rus.includes(navigator.language))  return   'ru'

      return 'en'
    },
    setLanguage() {
      if (this.profile.language) {
        this.$i18n.locale = this.profile.language

        return
      } else if (localStorage.getItem('locale')) {

        this.$i18n.locale = localStorage.getItem('locale')

        return
      }

      this.$i18n.locale = this.getBrowserLanguage()

    }
  }
}
</script>
