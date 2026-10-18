<template>
  <v-main>
    <v-container class="mx-auto" style="width: 500px">
      <v-responsive  class="mx-auto text-center mb-2">
          <logo></logo>
      </v-responsive>
      <router-view/>
    </v-container>
  </v-main>
</template>

<script>
import Logo from '@/components/Logo'
import config from '@/configs'
export default {
  components: {
    Logo
  },
  name: 'CentredLayout',
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

</style>
