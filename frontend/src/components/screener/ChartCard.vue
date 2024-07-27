<template>
  <v-card
    :id="'s'+ quote.id"
    :max-width="cardWidth+2"
    :min-width="cardWidth+2"
    class="mx-auto mb-4 chart-card"
    style="overflow: hidden"
    :loading="loading"
    :disabled="loading"
  >
    <v-card-actions >
      <v-btn text :to="`/quote/${quote.id}`">
        <quote-field-value
          field-id="id"
          external-class="card-ticker"
          :field-value="quote.id"
          :exchange="quote.exchange"
          :index="quote.index"
        >
        </quote-field-value>
      </v-btn>
      <watch-list-toggle :ticker="quote.id"></watch-list-toggle>
      <span style="line-height: 1" class="text--secondary ml-1">
        <span>
          {{ quote.sector ? `${quote.sector},`: '' }}
          <quote-field-value
            v-if="quote.exchange"
            field-id="exchange"
            :field-value="quote.exchange"
            :exchange="quote.exchange"
            :index="quote.index"
          >
          </quote-field-value>{{ quote.exchange ? ',': '' }}
          {{ quote.country }}
        </span><br>
        <span class="text--disabled text-uppercase font-weight-medium" style="font-size: 12px">{{ quote.name }}</span>
      </span>
      <v-spacer></v-spacer>

      <span v-if="quote.ch" class="text--disabled">
        <quote-field-value
          field-id="ch"
          :field-value="quote.ch"
          :exchange="quote.exchange"
          :index="quote.index"
        >
        </quote-field-value>
        (<quote-field-value
          v-if="quote.chp"
          field-id="chp"
          :field-value="quote.chp"
          :exchange="quote.exchange"
          :index="quote.index"
        >
        </quote-field-value>)
      </span>
    </v-card-actions>

    <v-card-text class="py-1">
      <span v-for="(fieldId, index) in activeChartPreset.displayFeedFields" :key="`field-${index}`" class="mr-2">
        {{ $t(`filters.${fieldId}.label`) }}:
        <quote-field-value
          :field-id="fieldId"
          :field-value="quote[fieldId]"
          :exchange="quote.exchange"
          :index="quote.index"
        >
        </quote-field-value>
      </span>
    </v-card-text>

    <img
      @click="toggleTicker(quote.id)"
      v-for="(image, index) in images"
      :key="index"
      class="d-inline-block"
      :width="image.width"
      :height="image.height"
      :src="image.src"
      contain
    />
  </v-card>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'
import QuoteFieldValue from '@/components/quote/QuoteFieldValue'
import WatchListToggle from '@/components/watchlist/WatchListToggle'
import moment from 'moment'
export default {
  name: 'ChartCard',
  components: {
    QuoteFieldValue,
    WatchListToggle
  },
  props: {
    quote: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      //TODO: move to env
      baseUrl: process.env.VUE_APP_ENTRYPOINT + '/api/chart?',
      realCharOneSrc: null,
      loadingOne: false
    }
  },
  computed: {
    loading() {
      return this.isLoading && this.quote.id === this.toggledTicker
    },
    images() {
      const images = [{
        src: this.chartOneSrc,
        width: this.activeChartPreset.chartOne.width,
        height: this.activeChartPreset.chartOne.height
      }]

      if (this.activeChartPreset.chartTwo)
        images.push({
          src: this.chartTwoSrc,
          width: this.activeChartPreset.chartTwo.width,
          height: this.activeChartPreset.chartTwo.height
        })
      if (this.activeChartPreset.chartThree)
        images.push({
          src: this.chartThreeSrc,
          width: this.activeChartPreset.chartThree.width,
          height: this.activeChartPreset.chartThree.height
        })

      return images
    },

    chartOneSrc() {
      return this.getChartSrc(this.activeChartPreset.chartOne)
    },
    chartTwoSrc() {
      return this.getChartSrc(this.activeChartPreset.chartTwo)
    },
    chartThreeSrc() {
      return this.getChartSrc(this.activeChartPreset.chartThree)
    },
    lightenInverse() {
      return this.$vuetify.theme.dark ? 'darken' : 'lighten'
    },
    ...mapState('screener', ['activeChartPreset']),
    ...mapGetters('screener', ['c1w','c2w','c3w','cardWidth']),
    ...mapState('watchlist', ['isLoading','toggledTicker'])
  },
  created() {
    this.$gtag.event('chartCard')
  },
  methods: {
    ...mapActions('watchlist', {
      toggleTicker: 'toggleTicker'
    }),
    loadOne() {
      this.loadingOne = true
    },
    getChartSrc(chartObj) {
      if (chartObj === null || chartObj === undefined) return null
      const uri = Object.entries(chartObj).reduce((parts, pair) => {
        const [key, value] = pair

        if (key !== '@id' && key !== '@type') {
          if (value !== 'null' && value !== null &&  value !== '0'  )
            parts.push(`${key}=${encodeURIComponent(value)}`)
        }

        return parts
      }, [`ticker=${this.quote.id}`])

      let lstbr = '&t=' +  moment().valueOf()

      if (
        this.quote.op &&
        this.quote.hi &&
        this.quote.lo &&
        this.quote.price &&
        this.quote.vol
      ) {
        lstbr = lstbr + `&lstbr=${this.quote.op},${this.quote.hi},${this.quote.lo},${this.quote.price},${this.quote.vol}`
      }

      return this.baseUrl + uri.join('&') + lstbr
    }
  }
}
</script>

<style >
    .card-ticker {
      font-size: 18px !important;
    }
    .chart-card .v-skeleton-loader__image {
      height: 100% !important;
    }
   .chart-img {
     /*display: inline-flex !important;*/
     /*float:left;*/
   }
</style>
