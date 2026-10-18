<template>
  <v-card
    :loading="isLoading"
    :disabled="isLoading"
    flat
    class="ma-0"
    color="transparent"
  >
    <div class="trading-chart-button">
      <v-btn small icon @click="reset">
        <v-icon style="font-size: 20px">mdi-autorenew</v-icon>
      </v-btn>
    </div>
    <trading-vue
      :id="id"
      ref="tradingVue"
      :key="id"
      :timezone="-5"
      :chart-config="chartConfig"
      :title-txt="title"
      :width="width"
      :height="height"
      :data="chart"
      :index-based="true"
      :color-back="colors.back"
      :color-grid="colors.grid"
      :color-text="colors.text"
      :color-cross="colors.cross"
      :color-candle-dw="colors.candle_dw"
      :color-candle-up="colors.candle_up"
      :color-wick-dw="colors.wick_dw"
      :color-title="title"
    >
    </trading-vue>
  </v-card>
</template>

<script>
import TradingVue from 'trading-vue-js'
import axios from '@/utils/interceptor'

export default {
  name: 'HistoryChart',
  components: {
    TradingVue
  },
  props: {
    id: {
      default: 'NONE',
      type: String
    },
    title: {
      default: 'NONE',
      type: String
    },
    timeframe: {
      default: 'd',
      type: String
    },
    width: {
      default: 1200,
      type: Number
    },
    height: {
      default: 700,
      type: Number
    },
    ticker: {
      default: 'SPY',
      type: String
    }
  },
  data() {
    return {
      range: {},
      isLoading: false,
      bars: [],
      chartConfig: {
        DEFAULT_LEN: 250,
        // CANDLEW: 0.8,
        GRIDX: 100,
        VOLSCALE: 0.3
      }
    }
  },
  computed: {
    colors() {
      return  this.$vuetify.theme.dark ? {
        back: 'transparent',
        grid: '#303741',
        title: '#a9b2bf',
        text: '#b7b7b7',
        cross: '#5f6773',
        candle_up: '#249E92',
        candle_dw: '#E34F4C',
        wick_dw: '#E34F4C'

      } : {
        back: '#fff',
        grid: '#eee',
        title: '#5e6e82',
        text: '#333',
        cross: '#838c99',
        candle_up: '#249E92',
        candle_dw: '#E34F4C',
        wick_dw: '#E34F4C'
      }
    },
    chart() {
      return {
        chart: {   // Mandatory
          tf: this.timeframe,
          type: 'Candles',
          data: this.bars,
          settings: { }
        }
      }
    }
  },

  watch: {
    timeframe() {
      this.loadData()
    },
    ticker() {
      this.loadData()
    }
  },
  created() {
    this.loadData()
  },
  methods: {
    test2() {
      this.$refs.tradingVue.setRange(
        200,
        250
      )
      this.range =   this.$refs.tradingVue.$refs.chart.ti_map.smth2i(1.1)

      //   1593307076354.5706, 1612786281451.5234
    },
    reset() {
      this.$refs['tradingVue'].resetChart()
    },
    loadData() {
      this.isLoading = true
      axios
        .get( `api/chart/bars/${this.ticker}/${this.timeframe}`)
        .then((response) => response.data)
        .then((data) => {
          this.bars = data
          this.isLoading = false
          //  this.$refs['tradingVue'].resetChart()
          //  this.$refs.tradingVue.goto(3)

          // this.test =  this.$refs.tradingVue.$refs.chart.ti_map.i2t(0)
        //  this.test =  this.$refs.tradingVue.getRange()
        })
        .catch((err) => {
          this.bars = []
          global.console.log(err)
        })
        .finally(() => (this.isLoading = false))
    }
  }

}
</script>

<style>
.trading-vue-legend {
  z-index: 5 !important;
}
  .trading-chart-button {
    z-index: 5;
    position: absolute;
    margin-top: 45px;
    margin-left: 80px;
  }

</style>
