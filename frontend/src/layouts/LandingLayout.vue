<template>
  <v-layout>
    <v-main>
      <v-sheet class="bg-sheet pb-8" dark>
        <landing-menu></landing-menu>
        <router-view></router-view>
      </v-sheet>
    </v-main>
  </v-layout>
</template>

<script>

import config from '@/configs'
import LandingMenu from '@/layouts/LandingMenu'
export default {
  name: 'LandingLayout',
  components: {
    LandingMenu
  },
  head: {
    link: [
      // { rel: 'icon', href: require('./assets/images/logos/logo.png'), sizes: '32x32', type: 'image/png' },
      // adds config/icons into the html head tag
      ...config.icons.map((href) => ({ rel: 'stylesheet', href }))
    ]
  }
}
</script>

<style scoped>
.bg-sheet {
  margin-bottom: 120px;
  background: linear-gradient(90deg, rgb(54 90 127) 0%, rgb(11 20 38) 100%);
}

@media screen and (min-width: 700px) {
  .bg-sheet {
    margin-bottom: 220px;
  }
}
</style>
