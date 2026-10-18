<template>
  <v-window v-model="windowPage" vertical>
    <v-window-item reverse-transition="fade-transition" transition="fade-transition" value="Screener">
      <screener></screener>
    </v-window-item>
    <v-window-item reverse-transition="fade-transition" transition="fade-transition" value="Quote">
      <quote></quote>
    </v-window-item>
  </v-window>
</template>
<script>
import Quote from '@/components/quote/Quote'
import Screener from '@/components/screener/Screener'
// import goTo from 'vuetify/es5/services/goto'
import { mapState } from 'vuex'
import ScrollTo  from 'vue-scrollto'
import config from '@/configs'
export default {
  name: 'ScreenerView',
  components: {
    Quote,
    Screener
  },
  data() {
    return {
      rememberScroll: 0,
      scrollY: 0
    }
  },
  computed: {
    ...mapState('screener', ['focusedTicker']),
    windowPage() {
      return this.$route.name
    }
  },
  watch: {
    $route(to, from) {
      if (to.name === 'Quote' && from.name === 'Screener') {
        this.rememberScroll  = this.scrollY
      }
      if (to.name === 'Screener' && from.name === 'Quote') {

        setTimeout(() => {
          if (this.focusedTicker) {
            ScrollTo.scrollTo( '#s' + this.focusedTicker, 200,
              {
                offset: -80
              }

            )
          }

        },500)
      }
    }
  },
  mounted() {
    if (this.$route.name === 'Quote') {
      // this.setQuote(this.$route.params.ticker)
    }
  },
  created () {
    window.addEventListener('scroll', this.handleScroll)
  },
  destroyed () {
    window.removeEventListener('scroll', this.handleScroll)
  },
  methods: {
    handleScroll () {
      this.scrollY = window.scrollY
    }
  },
  head: {
    title: {
      inner: 'Screener'
    }
  }
}
</script>

<style scoped>

</style>
