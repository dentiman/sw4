<template>
  <v-menu
    :close-on-content-click="false"
    bottom
    max-width="290"
    left
    nudge-bottom="10"
    offset-y
  >
    <template v-slot:activator="{ on }">
      <v-btn icon v-on="on" >
        <v-avatar
          class="mx-1"
          size="32"
        ><v-img :src="avatar" alt="avatar" /></v-avatar>
      </v-btn>

    </template>
    <v-card
      outlined
      width="270"
      class="h-full d-flex flex-column"
    >
      <v-card-actions>
        <v-btn
          icon
          @click="changeLanguage('ru')"
        >
          <v-avatar
            :color="$i18n.locale === 'ru'? 'primary': ''"
            size="32"
          >
            <span :class="$i18n.locale === 'ru'? 'white--text text-h7': 'text-h7'">RU</span>
          </v-avatar>
        </v-btn>

        <v-btn
          icon
          @click="changeLanguage('en')"
        >
          <v-avatar
            :color="$i18n.locale === 'en'? 'primary': ''"
            size="32"
          >
            <span :class="$i18n.locale === 'en'? 'white--text text-h7': 'text-h7'">EN</span>
          </v-avatar>
        </v-btn>

        <v-spacer></v-spacer>
        <v-btn icon @click="lightMode = !lightMode">
          <v-icon v-if="lightMode === false">mdi-brightness-4</v-icon>
          <v-icon v-if="lightMode === true">mdi-brightness-7</v-icon>
        </v-btn>
      </v-card-actions>

      <v-sheet class="py-0 text-center">
        <div class="pa-3 flex-grow-1">
          <div class="d-flex align-center flex-column">
            <v-avatar small size="90">
              <v-img :src="avatar" alt="avatar" />
            </v-avatar>
            <div class="text-center mt-2">
              <span class="font-weight-bold text-body-2">{{ profile.email }}</span>
              <!--              <div class="secondary&#45;&#45;text text&#45;&#45;lighten-1 text-caption">-->
              <!--                {{ profile.email }}-->
              <!--              </div>-->

            </div>
          </div>
        </div>
      </v-sheet>

      <div v-if="isPremium && profile.premiumExpiration">
        <div class="text-center">
          <v-chip color="secondary" class="mb-n3 overline font-weight-bold" small>{{ $t('label.premium') }}</v-chip>
        </div>
        <v-divider></v-divider>
        <v-sheet class="pa-2" color="surface">
          <div class="text-center">
            <span class="secondary--text text--lighten-1 text-caption">
              {{ $t('premium.expired_at') }}
            </span>
            <span class="font-weight-bold text-body-2">{{ profile.premiumExpiration | formatDate('yyyy-MM-dd') }}</span>
            <div>
              <v-btn
                class="mt-1"
                color="secondary"
                outlined
                small
                to="/buy"
              >{{ $t('premium.extend') }}</v-btn></div>
          </div>
        </v-sheet>
      </div>
      <v-divider></v-divider>
      <v-btn
        class="flex-grow-1"
        tile
        height="48"
        text
        @click="logout"
      >
        {{ $t('register.logout') }}
      </v-btn>
    </v-card>

  </v-menu>
</template>

<script>
import { mapState,mapGetters } from 'vuex'
import { mapFields } from 'vuex-map-fields'

export default {
  name: 'AccountMenu',
  computed: {
    avatar() {
      return this.$store.getters['auth/avatarImage']
    },
    ...mapState('auth', { profile: 'profile' }),
    ...mapGetters('auth', { isPremium: 'isPremium' }),
    ...mapFields('auth', {
      primaryDrawerOn: 'profile.primaryDrawerOn',
      lightMode: 'profile.lightMode',
      language: 'profile.language'
    })
  },
  methods: {
    changeLanguage(locale) {
      this.language =  locale
      this.$i18n.locale = locale
      localStorage.setItem('locale',locale)
    },
    logout() {
      this.$store.commit('auth/AUTH_RESET')
    }
  }
}
</script>

<style scoped>

</style>
