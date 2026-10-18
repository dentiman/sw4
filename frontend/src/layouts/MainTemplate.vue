<template>
  <div>
    <v-navigation-drawer
      v-if="authorized"
      :mini-variant="primaryDrawerOn"
      mini-variant-width="62"
      :width="navWidth"
      app
      permanent
      clipped
      floating
      overflow
    >
      <v-list-item class="px-1">
        <!--        <v-progress-circular-->
        <!--          v-if="primaryDrawerOn ===false"-->
        <!--          :rotate="180"-->
        <!--          :size="70"-->
        <!--          :width="15"-->
        <!--          :value="10"-->
        <!--          color="teal"-->
        <!--          class="mr-2"-->
        <!--        >-->
        <!--          {{ 10 }}-->
        <!--        </v-progress-circular>-->

        <!--        <v-list-item-title v-if="primaryDrawerOn ===false">John Leider</v-list-item-title>-->
        <v-btn
          icon
          @click.stop="primaryDrawerOn = !primaryDrawerOn"
        >
          <v-icon>{{ primaryDrawerOn ? 'mdi-chevron-right' : 'mdi-chevron-left' }}</v-icon>
        </v-btn>
      </v-list-item>

      <v-divider></v-divider>
      <v-card color="transparent" flat class="rounded-0">
        <v-tabs-items v-model="navListTabs" style="background-color:transparent">
          <v-tab-item
            :reverse-transition="false"
            :transition="false"
            style="background-color:transparent"
            value="watchlist"
          >
            <watch-list-navigation v-if="authorized"></watch-list-navigation>
          </v-tab-item>
          <v-tab-item :reverse-transition="false" :transition="false" value="charts">
            <chart-preset-setting-box v-if="primaryDrawerOn === false"></chart-preset-setting-box>
          </v-tab-item>
        </v-tabs-items>
      </v-card>

      <template v-slot:append>
        <notification-box></notification-box>
        <v-btn
          v-show="fab"
          v-scroll="onScroll"
          class="mb-0 mx-0 elevation-0 rounded-0"
          height="100px"
          block
          @click="toTop"
        >
          <v-icon size="30">mdi-chevron-double-up</v-icon>
        </v-btn>
      </template>
    </v-navigation-drawer>

    <v-app-bar
      id="main-app-bar"
      flat
      app
      clipped-left
      class="pt-0 border-b"
    >

      <v-toolbar
        id="app-left-toolbar"
        flat
        color="transparent"
        max-width="565"
        min-width="565"
      >

        <v-toolbar-title class="text-h5 mr-2 pl-0" style="margin-left: 10px" size="38">
          <logo></logo>
        </v-toolbar-title>
        <v-tooltip v-if="$route.name === 'Quote'" bottom open-delay="500">
          <template v-slot:activator="{ on }">
            <v-btn
              class="mr-1  ml-0"
              color="primary"
              elevation="0"
              icon
              to="/"
              v-on="on"
            >
              <v-icon >mdi-chevron-left</v-icon>
            </v-btn>
          </template>
          <span>{{ $t('Back to Screener') }}</span>
        </v-tooltip>

        <v-bottom-navigation
          v-if="$route.name === 'Quote'"
          v-model="quoteWindow"
          background-color="transparent"
          color="primary"
          width="200"
          class="elevation-0"
          height="62"
        >
          <v-btn :color="quoteWindow === 'quote-window-chart' ? 'falcon_200_300': ''" value="quote-window-chart" >
            <span>{{ $t('Chart') }}</span>
            <v-icon>mdi-chart-bar</v-icon>
          </v-btn>

          <v-btn :color="quoteWindow === 'quote-window-info' ? 'falcon_200_300': ''" value="quote-window-info" >
            <span>{{ $t('Company Info') }}</span>
            <v-icon>mdi-folder-information-outline</v-icon>
          </v-btn>
        </v-bottom-navigation>
        <watch-list-toggle v-if="$route.name === 'Quote' && ticker && authorized" :ticker="ticker"></watch-list-toggle>
      </v-toolbar>

      <v-spacer></v-spacer>
      <ticker-search></ticker-search>
      <v-spacer></v-spacer>

      <v-toolbar
        flat
        color="transparent"
      > <v-spacer ></v-spacer>
        <v-btn v-if="!isPremium" to="/premium" color="info" class="mr-1">{{ $t('premium.get_started.buy') }}</v-btn>

        <account-menu v-if="authorized"></account-menu>

        <div v-if="!authorized">
          <v-btn  text to="/login" >{{ $t('register.signIn') }}</v-btn>
          <v-btn  text to="/register">
            {{ $t('register.register') }}
          </v-btn>

          <v-menu offset-y>
            <template v-slot:activator="{ on }">
              <v-btn text v-on="on">
                {{ $i18n.locale | uppercase }}
              </v-btn>
            </template>
            <v-list>
              <v-list-item @click="changeLanguage('ru')">
                <v-list-item-title>RU</v-list-item-title>
              </v-list-item>
              <v-list-item @click="changeLanguage('en')">
                <v-list-item-title>EN</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
          <v-btn icon @click="lightMode = !lightMode">
            <v-icon v-if="lightMode === false">mdi-brightness-4</v-icon>
            <v-icon v-if="lightMode === true">mdi-brightness-7</v-icon>
          </v-btn>
        </div>
      </v-toolbar>

    </v-app-bar>

    <v-container class="pa-0" fluid>
      <router-view/>
    </v-container>

    <v-footer :inset="true" app style="font-size: 12px !important;">
      <span class="px-4 text--disabled ">© stock-watcher.com {{ new Date().getFullYear() }}. {{ $t('label.all-rights-reserved') }}</span>
      <v-divider vertical class="mr-2"></v-divider>
      <v-icon class="mr-1" size="12">mdi-email-outline</v-icon>
      <a href="mailto:support@stock-watcher.com" class="text-decoration-none text--disabled">support@stock-watcher.com</a>
      <v-divider vertical class="mx-2"></v-divider>
      <router-link to="/terms" class="text-decoration-none text--disabled">{{ $t('premium.terms') }}</router-link>
      <v-divider vertical class="mx-2"></v-divider>
      <router-link to="/policy" class="text-decoration-none text--disabled">{{ $t('premium.policy') }}</router-link>
      <v-divider vertical class="mx-2"></v-divider>
      <router-link to="/about" class="text-decoration-none text--disabled">{{ $t('premium.about_us') }}</router-link>
      <v-divider vertical class="mx-2"></v-divider>
      {{ version }}
      <!--      <router-link v-for="(link, i) in links" :key="i" :to="link.to" class="text-decoration-none text&#45;&#45;disabled mr-1">{{ link.label }}</router-link>-->
    </v-footer>
  </div>
</template>

<script>

import { mapActions, mapGetters, mapState } from 'vuex'
import { mapFields } from 'vuex-map-fields'
import WatchListNavigation from '@/components/watchlist/WatchListNavigation'
import TickerSearch from '@/components/quote/TickerSearch'
import WatchListToggle from '@/components/watchlist/WatchListToggle'
import NotificationBox  from '@/components/notifications/NotificationBox'
import ChartPresetSettingBox  from '@/components/chart_presets/ChartPresetSettingBox'
import Logo from '@/components/Logo'
import AccountMenu from '@/components/account/AccountMenu'
export default {
  name: 'MainTemplate',
  components: {
    AccountMenu,
    ChartPresetSettingBox,
    NotificationBox,
    TickerSearch,
    WatchListNavigation,
    WatchListToggle,
    Logo
  },
  data() {
    return {
      fab: false,
      watchListDialog: false,
      links: [{
        label: this.$t('premium.policy'),
        to: '/policy'
      }, {
        label: this.$t('premium.terms'),
        to: '/terms'
      }]
    }
  },
  computed: {
    version() {
      return process.env.VUE_APP_VERSION
    },
    navWidth() {
      if (this.navListTabs === 'charts') return 400

      return 300
    },
    ...mapState('screener', ['focusedTicker']),
    ...mapFields('screener', ['navListTabs']),
    ...mapFields('auth', ['pageWindow', 'quoteWindow', 'ticker']),
    ...mapFields('auth', {
      primaryDrawerOn: 'profile.primaryDrawerOn',
      lightMode: 'profile.lightMode',
      language: 'profile.language'
    }),
    ...mapGetters('auth', { authorized: 'authorized', isPremium: 'isPremium' }),
    ...mapState('auth', { profile: 'profile', needUpdateProfile: 'needUpdateProfile' })

  },
  methods: {
    changeLanguage(locale) {
      this.language =  locale
      this.$i18n.locale = locale
      localStorage.setItem('locale',locale)
    },
    onScroll (e) {
      if (typeof window === 'undefined') return
      const top = window.pageYOffset ||   e.target.scrollTop || 0

      this.fab = top > 20
    },
    toTop () {
      this.$vuetify.goTo(0)
    },
    ...mapActions('auth', ['updateProfile', 'setQuote', 'logout', 'loadProfile']),
    logout() {
      this.$store.commit('auth/AUTH_RESET')
    }
  },
  watch: {
    // eslint-disable-next-line
    $route(to, from) {
      if (to.name === 'Quote') {
        this.setQuote(to.params.ticker)
        this.$store.commit('screener/SET_FOCUSED_TICKER', to.params.ticker)

      }
    },
    profile: {
      handler() {
        this.updateProfile()
      },
      deep: true
    },
    primaryDrawerOn(value) {
      if (value === true) {
        localStorage.primaryDrawerOn = '1'
      } else {
        localStorage.removeItem('primaryDrawerOn')
      }
    }
  }
}
</script>

<style>

#app-left-toolbar .v-toolbar__content {
  padding-left: 0px !important;
}

.qf-ticker {
  text-decoration: unset !important;
}

/*#scroll-left-container {*/
/*  background-color: black;*/
/*  height: 100%;*/

/*  position: absolute;*/
/*  width: 256px;*/
/*}*/

/*#main-app-bar .v-input__control {*/
/*  min-height: 32px;*/
/*}*/

.theme--light ::-webkit-scrollbar {
  width: 12px;
}

.theme--light ::-webkit-scrollbar-track {
  background: #e6e6e6;
  border-left: 1px solid #dadada;
}

.theme--light ::-webkit-scrollbar-thumb {
  background: #b0b0b0;
  border: solid 2px #e6e6e6;
  border-radius: 7px;
}

.theme--light ::-webkit-scrollbar-thumb:hover {
  background: #a3a2a2;
}

.theme--dark ::-webkit-scrollbar {
  width: 12px;
}

.theme--dark ::-webkit-scrollbar-track {
  background: #313335;
  border-left: 1px solid #313335;
}

.theme--dark ::-webkit-scrollbar-thumb {
  background: #3C3F41;
  border: solid 2px #313335;
  border-radius: 7px;
}

.theme--dark ::-webkit-scrollbar-thumb:hover {
  background: #474a4c;
}

/*.bg-holder.corner-1 {*/
/*  background-image: url("asset/img/illustrations/corner-1.png");*/
/*}*/

/*#main-app-bar .v-toolbar__content {*/
/*  margin: auto;*/
/*  max-width: 1200px;*/
/*  padding: 0px;*/
/*}*/

/*.v-tabs-items {*/
/*    background-color: transparent !important;*/
/*}*/
</style>
