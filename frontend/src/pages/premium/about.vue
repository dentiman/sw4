<template>
  <div>
    <v-sheet class="bg-sheet pb-0 mb-0" dark>
      <landing-menu></landing-menu>
      <v-container class="py-3 py-sm-3 text-center">
        <v-responsive max-width="800" class="mx-auto">
          <logo></logo>
          <h3 class="text-h4 text-sm-h4 my-4 text-white" style="color: white !important;">{{ $t('premium.about_us') }}</h3>
        </v-responsive>
      </v-container>
    </v-sheet>
    <v-card max-width="700" class="mx-auto " style="margin-top: -50px" >
      <v-responsive   class="mx-auto text-center" >
        <div class="text-h5 primary--text my-1">Stock-Watcher {{ $t('premium.about_text_1') }}</div>
      </v-responsive>
      <v-responsive>
        <div class="ma-2">{{ $t('premium.about_text_2') }}</div>
        <div class="ma-2 ">
          {{ $t('premium.about_text_3') }}
          <span class="error--text">{{ $t('premium.about_text_4') }}</span>
        </div>

        <div class="mx-2 my-1">{{ $t('premium.about_text_5') }}</div>
        <div class="mx-2 my-1">{{ $t('premium.about_text_6') }}</div>
        <div class="mx-2">{{ $t('premium.about_text_7') }} <router-link to="/premium">{{ $t('premium.about_text_8') }}</router-link> </div>
      </v-responsive>

      <v-divider class="my-1"></v-divider>
      <div class="mx-2 my-1">{{ $t('premium.about_text_9') }}</div>
      <div class="mx-2 my-1">{{ $t('premium.about_text_10') }}  <a href="mailto:support@stock-watcher.com" class="text-decoration-none secondary--text text--lighten-2">support@stock-watcher.com</a></div>

      <v-divider class="my-2"></v-divider>
      <div class="mx-2 my-1">{{ $t('premium.about_text_11') }}</div>
      <div class="mx-2 my-1">{{ $t('premium.about_text_12') }}</div>
      <v-card-title class="text-center">

      </v-card-title>

    </v-card>
    <Footer/>
  </div>
</template>

<script>
import LandingMenu from '@/layouts/LandingMenu'
import Logo from '@/components/Logo'
import Footer from '@/layouts/Footer'
import axios from '@/utils/interceptor'
import NotificationBox  from '@/components/notifications/NotificationBox'
export default {
  name: 'About',
  components: {
    Logo,
    LandingMenu,
    Footer
  },
  data () {
    return {

    }
  },
  computed: {
    language() {
      return this.$store.state.auth.profile.language
    }
  },
  created() {

  },
  methods: {

  }
}
</script>

<style scoped>
.bg-sheet {
  margin-bottom: 120px;
  background: linear-gradient(90deg, rgb(54 90 127) 0%, rgb(11 20 38) 100%);
}
</style>
