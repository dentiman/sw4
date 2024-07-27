<template>
  <v-btn
    icon
    large
    :color="color"
    :loading="loading"
    :disabled="loading"
    @click="toggleTicker(ticker)"
  >
    <v-icon :color="color" size="32">mdi-bookmark</v-icon>
  </v-btn>
</template>
<script>
import { mapActions, mapState } from 'vuex'

export default {
  name: 'WatchListToggle',
  props: {
    ticker: {
      require: true
    }
  },
  methods: {
    ...mapActions('watchlist', {
      toggleTicker: 'toggleTicker'
    })
  },
  computed: {
    loading() {
      return this.isLoading && this.ticker === this.toggledTicker
    },
    color() {
      if (this.green.indexOf(this.ticker) > -1) { return 'green'}
      if (this.blue.indexOf(this.ticker) > -1) { return 'blue'}
      if (this.red.indexOf(this.ticker) > -1) { return 'red'}

      return 'grey'
    },
    ...mapState('watchlist', ['green','red','blue', 'isLoading','toggledTicker'])
  }
}
</script>

<style scoped>

</style>
