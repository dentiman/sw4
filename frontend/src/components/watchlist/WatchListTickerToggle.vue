<template>
  <v-menu
    v-model="show"
    top
    :offset-y="true"
    origin="top center"
    :close-on-click="true"
  >
    <template v-slot:activator="{ on, attrs }">
      <a
        v-bind="attrs"
        :class="`ma-1 wl-item ${show ? `${color}--text`: 'text--secondary' } `"
        v-on="on"
      >{{ ticker }}
      </a>
    </template>
    <v-card flat color="secondary pa-2" :width="130">
      <v-btn icon @click="moveOrRemove(ticker, 'green')" >
        <v-icon color="green">{{ color === 'green' ? 'mdi-window-close': 'mdi-bookmark' }}</v-icon>
      </v-btn>
      <v-btn icon @click="moveOrRemove(ticker, 'red')" >
        <v-icon color="red">{{ color === 'red' ? 'mdi-window-close': 'mdi-bookmark' }}</v-icon>
      </v-btn>
      <v-btn icon @click="moveOrRemove(ticker, 'blue')" >
        <v-icon color="blue">{{ color === 'blue' ? 'mdi-window-close': 'mdi-bookmark' }}</v-icon>
      </v-btn>
    </v-card>
  </v-menu>
</template>
<script>
import { mapActions } from 'vuex'

export default {
  name: 'WatchListTickerToggle',
  props: {
    ticker: {
      require: true
    },
    color: {
      require: true
    }
  },
  methods: {
    moveOrRemove(ticker, color) {
      if (color === this.color) {
        this.remove(ticker)

        return
      }
      this.move({ ticker: ticker, color: color })
    },
    ...mapActions('watchlist', ['toggleTicker','move','remove'])
  },
  data() {
    return {
      show: false
    }
  },
  computed: {

  }
}
</script>

<style scoped>

</style>
