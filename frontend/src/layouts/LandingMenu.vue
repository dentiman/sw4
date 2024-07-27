<template>
  <div>
    <v-navigation-drawer app temporary>
      <v-list dense nav>
        <v-subheader class="text-uppercase font-weight-bold">Menu</v-subheader>
        <v-list-item v-for="(item, index) in menu" :key="index" :to="item.link">
          <v-list-item-content>
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>

      <template v-slot:append>
        <div class="pa-2">
          <v-btn block color="primary">
            Log In
          </v-btn>
        </div>
      </template>
    </v-navigation-drawer>

    <v-btn
      class="d-md-none drawer-button"
      rounded
    >
      <v-icon right>mdi-menu</v-icon>
    </v-btn>

    <v-app-bar color="transparent" flat height="80">
      <v-container class="py-0 px-0 px-sm-2 fill-height">

        <v-spacer></v-spacer>
        <div class="d-none d-md-block">
          <v-btn
            v-for="(item, index) in menu"
            :key="index"
            :to="item.link"
            text
            class="mx-1"
          >
            {{ item.title }}
          </v-btn>
        </div>
        <v-menu >
          <template v-slot:activator="{ on }">
            <v-btn text v-on="on">
              {{ language | uppercase }}
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
        <v-spacer></v-spacer>

      </v-container>
    </v-app-bar>
  </div>
</template>

<script>

import { mapGetters } from 'vuex'
import { mapFields } from 'vuex-map-fields'

export default {
  name: 'LandingMenuVue',
  computed: {
    ...mapGetters('auth', { authorized: 'authorized', isPremium: 'isPremium' }),
    ...mapFields('auth', { language: 'profile.language' })
  },
  data() {
    return {
      menu: [
        { link: '/', title: this.$t('label.screener') },
        {
          title: this.$t('premium.policy'),
          link: '/policy'
        }, {
          title: this.$t('premium.terms'),
          link: '/terms'
        }, {
          title: this.$t('label.premium'),
          link: '/premium'
        },
        {
          title: this.$t('premium.about_us'),
          link: '/about'
        }
      ]
    }
  },
  methods: {
    changeLanguage(locale) {
      this.language =  locale
      this.$i18n.locale = locale
    }
  }
}
</script>

<style scoped>

</style>
