<template>
  <div>
    <v-resizable
      v-if="isReady"
      :height="topChartHeight"
      @resize:move="eHandler"
      @resize:start="eHandler"
      @resize:end="resizeEnd"
    >
      <div class="resizable-content">
        <div class="trading-chart-nav ml-1">
          <v-menu offset-y>
            <template v-slot:activator="{ on }">
              <v-btn text v-on="on">{{ tfLabels[topTimeframe] }}</v-btn>
            </template>
            <v-list dense>
              <v-list-item v-for="tf in timeframes" :key="tf.tf" @click="topTimeframe = tf.tf" >
                <v-list-item-title>{{ tf.label }}</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </div>
        <history-chart
          id="chartTop"
          :timeframe="topTimeframe"
          :height="topChartHeight"
          :title="ticker"
          :width="width"
          :ticker="ticker"
        ></history-chart>
      </div>
    </v-resizable>
    <div v-if="isReady">
      <div class="trading-chart-nav ml-1">
        <v-menu offset-y>
          <template v-slot:activator="{ on }">
            <v-btn text v-on="on">{{ tfLabels[bottomTimeframe] }}</v-btn>
          </template>
          <v-list dense>
            <v-list-item v-for="tf in timeframes" :key="tf.tf" @click="bottomTimeframe = tf.tf" >
              <v-list-item-title>{{ tf.label }}</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </div>
      <history-chart
        id="chartBottom"
        :timeframe="bottomTimeframe"
        :height="bottomChartHeight"
        :title="ticker"
        :width="width"
        :ticker="ticker"
      ></history-chart>
    </div>
  </div>
</template>

<script>

import { mapFields } from 'vuex-map-fields'
import { mapActions, mapState } from 'vuex'
import HistoryChart from '@/components/quote/HistoryChart'
import axios from '@/utils/interceptor'
import { exchanges } from '@/utils/filters/helper'
import QuoteFieldValue from '@/components/quote/QuoteFieldValue'
import VResizable from 'vue-resizable'

export default {
  name: 'QuoteChartsCart',
  components: {
    HistoryChart,
    VResizable
  },
  props: {
    ticker: {
      default: 'SPY',
      type: String
    },
    width: {
      default: 1200,
      type: Number
    }
  },
  data() {
    return {
      topTimeframe: localStorage.getItem('topTimeframe') || 'd',
      bottomTimeframe: localStorage.getItem('bottomTimeframe') || '5',
      tfLabels: {
        d: 'Daily',
        1: '1 min',
        2: '2 min',
        3: '3 min',
        4: '4 min',
        5: '5 min',
        15: '15 min',
        30: '30 min',
        60: '60 min'
      },
      timeframes: [
        { tf: 'd', label: 'Daily' },
        { tf: '1', label: '1 min' },
        { tf: '2', label: '2 min' },
        { tf: '3', label: '3 min' },
        { tf: '5', label: '5 min' },
        { tf: '15', label: '15 min' },
        { tf: '30', label: '30 min' },
        { tf: '60', label: '60 min' }
      ],
      resizableChartHeight: localStorage.getItem('resizableChartHeight') * 1 || 0,
      cardHeight: 0
    }
  },
  computed: {
    topChartHeight() {
      return  this.resizableChartHeight
    },
    bottomChartHeight() {
      return  this.cardHeight - this.resizableChartHeight
    },
    isReady() {
      return this.cardHeight > 0 && this.resizableChartHeight > 0
    }

  },
  watch: {
    topTimeframe(value) {
      localStorage.setItem('topTimeframe',value)
    },
    bottomTimeframe(value) {
      localStorage.setItem('bottomTimeframe',value)
    }
  },
  created() {
  },
  mounted() {
    window.addEventListener('resize', this.calculateHeight())
    this.calculateHeight()
    if (this.resizableChartHeight === 0 ) {
      this.resizableChartHeight = Math.ceil(this.cardHeight / 2)
    }

  },
  methods: {
    reset() {

    },
    eHandler(data) {
      this.resizableChartHeight = data.height

    },
    resizeEnd(data) {
      this.resizableChartHeight = data.height
      localStorage.setItem('resizableChartHeight',data.height)

    },
    calculateHeight() {
      this.cardHeight = window.innerHeight - 100
    }
  }

}
</script>

<style>
.resizable-b {
  border-top: 2px solid rgba(127,127,127,0.6);
}
.resizable-b:hover {
  border-top: 2px solid dodgerblue;
}
.resizable-content {
  height: 100%;
  width: 100%;
}

.trading-chart-nav {
  z-index: 5;
  position: absolute;
  margin-top: 40px;
}
</style>
